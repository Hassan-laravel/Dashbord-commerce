<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

  // Receiving the request when creating the class
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

public function build()
{

    App::setLocale($this->order->locale);

    return $this->locale($this->order->locale)
                ->subject(__('emails.order_confirmation', ['number' => $this->order->number]))
                ->view('emails.orders.placed');
}
}
