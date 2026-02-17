<?php

declare(strict_types=1);

namespace App\Mail;

use App\Modules\Order\Models\VendorOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorOrderShippedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public VendorOrder $vendorOrder
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order from '.$this->vendorOrder->vendor->business_name.' Has Shipped',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-orders.shipped',
            with: [
                'vendorOrder' => $this->vendorOrder,
                'vendor' => $this->vendorOrder->vendor,
                'customer' => $this->vendorOrder->order->customer,
                'trackingNumber' => $this->vendorOrder->tracking_number,
                'shippingCarrier' => $this->vendorOrder->shipping_carrier,
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
