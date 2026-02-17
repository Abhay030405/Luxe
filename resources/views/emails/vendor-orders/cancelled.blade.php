<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #EF4444; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .alert-box { background: #FEE2E2; border-left: 4px solid #EF4444; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .order-details { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Cancelled</h1>
        </div>
        <div class="content">
            <p>Hi {{ $customer->name }},</p>
            
            <p>We're writing to inform you that your order from <strong>{{ $vendor->business_name }}</strong> has been cancelled.</p>
            
            <div class="alert-box">
                <strong>Cancellation Reason:</strong><br>
                {{ $reason ?? 'No reason provided' }}
            </div>
            
            <div class="order-details">
                <h3>Cancelled Order Details</h3>
                <p><strong>Vendor Order Number:</strong> {{ $vendorOrder->vendor_order_number }}</p>
                <p><strong>Order Total:</strong> ${{ number_format($vendorOrder->subtotal, 2) }}</p>
                <p><strong>Cancelled Date:</strong> {{ $vendorOrder->cancelled_at->format('F d, Y') }}</p>
            </div>
            
            <p>If you were charged for this order, a refund will be processed to your original payment method within 5-7 business days.</p>
            
            <p>If you have any questions about this cancellation, please contact our support team.</p>
            
            <p style="margin-top: 30px;">We apologize for any inconvenience.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
