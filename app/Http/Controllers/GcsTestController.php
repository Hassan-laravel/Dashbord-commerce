<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Http\JsonResponse;

class GcsTestController extends Controller
{
    /**
     * تجربة رفع ملف إلى Google Cloud Storage
     */
public function uploadTest(Request $request): JsonResponse
{
    try {
        // 1. التحقق من وجود الملف وصحته
        if (!$request->hasFile('image')) {
            return response()->json(['status' => 'error', 'message' => 'لم يتم إرسال أي صورة'], 400);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120'
        ]);

        // 2. سحب الإعدادات من الـ Config (نفس الطريقة التي نجحت في الدالة الأولى)
        $gcsConfig = config('filesystems.disks.gcs');

        // التحقق من اكتمال الإعدادات
        if (empty($gcsConfig['project_id']) || empty($gcsConfig['bucket'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'إعدادات Google Cloud Storage غير مكتملة في ملف config/filesystems.php',
                'debug_config' => [
                    'project_id' => isset($gcsConfig['project_id']) ? 'OK' : 'Missing',
                    'bucket' => isset($gcsConfig['bucket']) ? 'OK' : 'Missing',
                ]
            ], 500);
        }

        // 3. تنفيذ عملية الرفع باستخدام القرص المعرف
        $file = $request->file('image');

        // الرفع لمجلد اسمه 'uploads' داخل الـ Bucket
        $path = Storage::disk('gcs')->putFile('uploads', $file);

        if (!$path) {
            return response()->json(['status' => 'error', 'message' => 'فشل الرفع لسبب غير معروف في GCS'], 500);
        }

        // 4. توليد الرابط العام
        $url = Storage::disk('gcs')->url($path);

        return response()->json([
            'status' => 'success',
            'message' => 'تم رفع الصورة بنجاح إلى Google Cloud',
            'data' => [
                'path' => $path,
                'url' => $url,
                'filename' => $file->getClientOriginalName()
            ]
        ], 201);

} catch (\Exception $e) {
    // هذا السطر سيكشف لك إذا كانت المشكلة (Quota، Permissions، أو Bucket Name)
    return response()->json([
        'status' => 'error',
        'message' => 'GCS Error Details: ' . $e->getMessage()
    ], 500);
}
}

    /**
     * اختبار الاتصال بـ Google Cloud Storage
     */
    public function testConnection(): JsonResponse
    {
        try {
        // بدلاً من فحص env مباشرة، نسحب الإعدادات التي جهزناها في config
        $gcsConfig = config('filesystems.disks.gcs');

        $projectId = $gcsConfig['project_id'] ?? null;
        $bucket = $gcsConfig['bucket'] ?? null;
        $keyFile = $gcsConfig['key_file'] ?? null;

        // التحقق من أن الإعدادات وصلت للارافل بنجاح
        if (!$keyFile || !$projectId || !$bucket) {
            return response()->json([
                'status' => 'error',
                'message' => 'المتغيرات البيئية غير مكتملة في ملف config',
                'config_preview' => [
                    'key_file' => $keyFile ? 'موجود (Array/Path)' : 'غير موجود',
                    'project_id' => $projectId ? 'موجود' : 'غير موجود',
                    'bucket' => $bucket ? 'موجود' : 'غير موجود'
                ]
            ], 400);
        }

        // محاولة الاتصال باستخدام Disk المعرف في لارافل
        $disk = Storage::disk('gcs');
        $files = $disk->listContents('/');

        return response()->json([
            'status' => 'success',
            'message' => 'تم الاتصال بـ Google Cloud Storage بنجاح',
            'details' => [
                'project_id' => $projectId,
                'bucket' => $bucket,
                'files_found' => is_array($files) ? count($files) : 'N/A'
            ]
        ]);
            $keyFile = env('GCS_KEY_FILE');
            $projectId = env('GCS_PROJECT_ID');
            $bucket = env('GCS_BUCKET');

            // التحقق من المتغيرات البيئية
            if (!$keyFile || !$projectId || !$bucket) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'المتغيرات البيئية غير مكتملة',
                    'config' => [
                        'key_file' => $keyFile ? 'موجود' : 'غير موجود',
                        'project_id' => $projectId ? 'موجود' : 'غير موجود',
                        'bucket' => $bucket ? 'موجود' : 'غير موجود'
                    ]
                ], 400);
            }

            // دعم ثلاث طرق لتمرير بيانات الاعتماد في GCS_KEY_FILE:
            // 1) مسار داخل المشروع: storage/app/google-auth.json
            // 2) JSON خام ملصوق مباشرة
            // 3) Base64-encoded JSON
            $decoded = null;
            $keySource = null; // file | env_raw | env_base64
            $fullPath = null;

            // Trim whitespace
            $keyFile = trim($keyFile);

            $possiblePath = base_path($keyFile);
            if (file_exists($possiblePath)) {
                $fullPath = $possiblePath;
                $content = file_get_contents($fullPath);
                $decoded = json_decode($content, true);
                $keySource = 'file';
            } else {
                // محاولة JSON خام
                $decoded = json_decode($keyFile, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $keySource = 'env_raw';
                } else {
                    // محاولة Base64
                    // Remove whitespace from base64 string
                    $cleanBase64 = str_replace(["\n", "\r", " ", "\t"], '', $keyFile);
                    $base = base64_decode($cleanBase64, true);
                    if ($base !== false) {
                        $decodedCandidate = json_decode($base, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedCandidate)) {
                            $decoded = $decodedCandidate;
                            $keySource = 'env_base64';
                        }
                    }
                }
            }

            if (!$decoded) {
                // Debug info for base64 decoding failures
                $debugInfo = [];
                if (env('APP_DEBUG')) {
                    $cleanBase64 = str_replace(["\n", "\r", " ", "\t"], '', $keyFile);
                    $base64Decoded = base64_decode($cleanBase64, true);
                    $debugInfo = [
                        'key_file_length' => strlen($keyFile),
                        'clean_base64_length' => strlen($cleanBase64),
                        'base64_decode_result' => $base64Decoded !== false ? 'decoded_successfully' : 'base64_decode_failed',
                        'json_decode_error' => json_last_error_msg(),
                        'attempted_path_exists' => file_exists(base_path($keyFile)),
                        'base_path' => base_path(),
                        'storage_contents' => glob(storage_path('app/*'))
                    ];
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'ملف المفاتيح غير موجود أو غير صالح. تأكد من قيمة GCS_KEY_FILE (path, raw JSON أو base64).',
                    'key_file_value_preview' => substr($keyFile, 0, 200),
                    'key_file_source_attempted' => $keySource,
                    'full_path' => $fullPath,
                    'debug' => $debugInfo
                ], 400);
            }

            // محاولة الاتصال
            $disk = Storage::disk('gcs');
            $files = $disk->listContents('/');

            return response()->json([
                'status' => 'success',
                'message' => 'تم الاتصال بـ Google Cloud Storage بنجاح',
                'config' => [
                    'project_id' => $projectId,
                    'bucket' => $bucket,
                    'key_file_source' => $keySource,
                    'key_file_path' => $fullPath,
                    'files_count' => iterator_count($files),
                    'service_account' => $decoded['client_email'] ?? 'Unknown'
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('GCS Connection Test Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'فشل الاتصال بـ Google Cloud Storage',
                'error' => $e->getMessage(),
                'debug' => env('APP_DEBUG') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }
}
