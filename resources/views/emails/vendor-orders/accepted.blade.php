<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .order-details { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Accepted!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $customer->name }},</p>
            
            <p>Great news! Your order from <strong>{{ $vendor->business_name }}</strong> has been accepted and is being prepared for shipment.</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Vendor Order Number:</strong> {{ $vendorOrder->vendor_order_number }}</p>
                <p><strong>Order Date:</strong> {{ $vendorOrder->created_at->format('F d, Y') }}</p>
                
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                
                <h4>Items:</h4>
                @foreach($vendorOrder->items as $item)
                <div class="item">
                    <div>
                        <strong>{{ $item->product->name }}</strong><br>
                        <span style="color: #6b7280;">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</span>
                    </div>
                    <div style="font-weight: bold;">${{ number_format($item->total, 2) }}</div>
                </div>
                @endforeach
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #3B82F6;">
                    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                        <span>Total:</span>
                        <span>${{ number_format($vendorOrder->subtotal, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <p>The vendor will ship your order soon. You'll receive another email with tracking information once it's shipped.</p>
            
            <p style="margin-top: 30px;">If you have any questions, please contact us.</p>
            
            <p>Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
