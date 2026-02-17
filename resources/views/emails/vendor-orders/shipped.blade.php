<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6366F1; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .tracking-box { background: white; padding: 25px; border-radius: 6px; margin: 20px 0; text-align: center; border: 2px solid #6366F1; }
        .tracking-number { font-size: 24px; font-weight: bold; color: #6366F1; margin: 10px 0; letter-spacing: 1px; }
        .order-details { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Order Has Shipped! 📦</h1>
        </div>
        <div class="content">
            <p>Hi {{ $customer->name }},</p>
            
            <p>Exciting news! Your order from <strong>{{ $vendor->business_name }}</strong> has been shipped and is on its way to you.</p>
            
            <div class="tracking-box">
                <p style="margin: 0; color: #6b7280;">Tracking Number</p>
                <div class="tracking-number">{{ $trackingNumber }}</div>
                @if($shippingCarrier)
                <p style="margin: 10px 0 0 0; color: #6b7280;">Carrier: <strong>{{ $shippingCarrier }}</strong></p>
                @endif
            </div>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Vendor Order Number:</strong> {{ $vendorOrder->vendor_order_number }}</p>
                <p><strong>Shipped Date:</strong> {{ $vendorOrder->shipped_at->format('F d, Y') }}</p>
            </div>
            
            <p>You can use the tracking number above to monitor your shipment's progress.</p>
            
            <p style="margin-top: 30px;">Thank you for your purchase!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
