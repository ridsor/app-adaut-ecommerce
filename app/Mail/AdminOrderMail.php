<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected $order_id
    ) {
        $this->order = Order::with([
            'order_items',
            'order_items.product',
            'transaction',
            'shipping',
            'transaction.payment',
            'user' => fn($query) => $query->withTrashed()->select(['name', 'id'])
        ])
            ->withSum([
                'order_items as total_price' => function ($query) {
                    $query->select(DB::raw('SUM(order_items.quantity * products.price)'))
                        ->join('products', 'order_items.product_id', '=', 'products.id');
                }
            ], '')->where('id', $order_id)->firstOrFail();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pesanan " . $this->order->transaction->invoice . " dari " . $this->order->user->name,
            metadata: [
                'order_id' => $this->order->id,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.order',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
