<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10B981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 60px; text-align: center; margin: 20px 0; }
        .order-details { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Delivered! ✅</h1>
        </div>
        <div class="content">
            <div class="success-icon">🎉</div>
            
            <p>Hi {{ $customer->name }},</p>
            
            <p>Your order from <strong>{{ $vendor->business_name }}</strong> has been successfully delivered!</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Vendor Order Number:</strong> {{ $vendorOrder->vendor_order_number }}</p>
                <p><strong>Delivered Date:</strong> {{ $vendorOrder->delivered_at->format('F d, Y') }}</p>
                <p><strong>Order Total:</strong> ${{ number_format($vendorOrder->subtotal, 2) }}</p>
            </div>
            
            <p>We hope you enjoy your purchase! If you have any issues with your order, please don't hesitate to contact us.</p>
            
            <p style="margin-top: 30px;">Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
