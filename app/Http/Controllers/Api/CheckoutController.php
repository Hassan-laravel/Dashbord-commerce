<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // للإضافة في السجلات
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;

// ==========================================
// 🚀 استدعاء مكتبة Stripe
// ==========================================
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // تسجيل البيانات القادمة للتأكد (Debugging)
        // Log::info('Checkout Data:', $request->all());

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string',
            'customer_city' => 'required|string',
            'customer_address' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,stripe,paypal',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'customer_email' => 'required|email',
            'locale' => 'nullable|string', // إضافة التحقق من اللغة إذا تم إرسالها
        ]);

        try {
            DB::beginTransaction();

            // حساب المجموع من العناصر المرسلة
            $subTotal = collect($request->items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // إنشاء الطلب
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_city' => $validated['customer_city'],
                'customer_address' => $validated['customer_address'],
                'notes' => $validated['notes'] ?? null,

                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',

                'shipping_price' => 0,
                'tax_price' => 0,
                'discount' => 0,
                'total_price' => $subTotal, // الإجمالي
                'customer_email' => $validated['customer_email'], // حفظ البريد الإلكتروني
                'locale' => $request->locale ?? 'en', // حفظ اللغة إذا تم إرسالها
                'user_id' => auth('sanctum')->check() ? auth('sanctum')->id() : null,
            ]);

            // إنشاء العناصر
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            // ==========================================
            // 🚀 إرسال البريد الإلكتروني
            // ==========================================
            try {
                Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
            } catch (\Exception $e) {
                \Log::error('Mail sending failed: ' . $e->getMessage());
            }

            // ==========================================
            // 🚀 معالجة بوابات الدفع (Stripe)
            // ==========================================
            if ($validated['payment_method'] === 'stripe') {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $lineItems = [];
                foreach ($validated['items'] as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd', // يمكنك تغيير العملة هنا إذا أردت
                            'product_data' => [
                                'name' => $item['product_name'],
                            ],
                            // Stripe يتعامل بالسنتات، لذلك نضرب السعر في 100 ونحوله لعدد صحيح
                            'unit_amount' => (int)($item['price'] * 100),
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }

                $checkoutSession = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    // الروابط التي سيعود إليها العميل بعد الدفع (في الفرونت-إند)
                    'success_url' => env('FRONTEND_URL') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => env('FRONTEND_URL') . '/checkout/cancel',
                    'client_reference_id' => $order->id, // نربط الجلسة برقم الطلب الخاص بنا
                ]);

                // إرجاع رابط الدفع للفرونت إند ليقوم بتحويل العميل
                return response()->json([
                    'success' => true,
                    'message' => 'Redirecting to payment gateway...',
                    'checkout_url' => $checkoutSession->url,
                ], 200);
            }

            // ==========================================
            // 🚀 الاستجابة في حالة الدفع عند الاستلام (COD)
            // ==========================================
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_number' => $order->number ?? $order->id, // استخدمنا fallback لـ id إذا كان number غير موجود
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }
    // ==========================================
    // 🚀 دالة استقبال الـ Webhook من Stripe
    // ==========================================
    public function handleStripeWebhook(Request $request)
    {
        // 1. إعداد مفتاح Stripe السري
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // 2. المفتاح السري الخاص بالـ Webhook (سنحصل عليه لاحقاً من Stripe)
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $event = null;

        // 3. التحقق من أن الطلب قادم فعلاً من Stripe وليس من هكر
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // بيانات غير صالحة
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // توقيع غير صالح (محاولة اختراق)
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // 4. إذا كان الحدث هو "تم الدفع بنجاح"
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            // نجلب رقم الطلب الذي أرسلناه سابقاً في client_reference_id
            $orderId = $session->client_reference_id;

            // نبحث عن الطلب في قاعدة البيانات ونحدث حالته
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid', // تم الدفع
                    'status' => 'processing'    // قيد التجهيز
                ]);

                \Log::info("Order #{$order->id} has been paid successfully via Stripe.");
            }
        }

        // يجب أن نرد على Stripe برمز 200 لكي يعرف أننا استلمنا الرسالة
        return response()->json(['status' => 'success'], 200);
    }
}
