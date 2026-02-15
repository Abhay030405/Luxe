# Payment Module Documentation

## Overview
The Payment Module handles payment processing, payment gateway integration, transaction management, and payment verification for the e-commerce platform. It provides a unified interface for multiple payment methods including Cash on Delivery (COD), Credit/Debit Cards, UPI, Net Banking, and digital wallets.

## Module Status
⚠️ **Current Status: Placeholder / In Development**

The Payment module structure exists but is not yet fully implemented. This documentation outlines the intended functionality and serves as a specification for future development.

## Purpose
- Process online and offline payments
- Integrate with payment gateways (Razorpay, Stripe, PayPal, etc.)
- Handle payment verification and confirmation
- Manage payment status workflow
- Record payment transactions
- Process refunds and partial refunds
- Handle payment failures and retries
- Support multiple payment methods
- Generate payment receipts and invoices

## Module Structure

```
Payment/
├── Controllers/
│   └── PaymentController.php           # Payment processing (currently empty)
├── DTOs/
│   ├── PaymentDTO.php                  # Payment data transfer object
│   └── TransactionDTO.php              # Transaction details DTO
├── Events/
│   ├── PaymentFailed.php               # Triggered on payment failure
│   ├── PaymentInitiated.php            # Triggered when payment starts
│   ├── PaymentSuccessful.php           # Triggered on successful payment
│   └── RefundProcessed.php             # Triggered when refund completed
├── Listeners/
│   ├── UpdateOrderPaymentStatus.php    # Updates order payment_status
│   └── SendPaymentReceipt.php          # Emails payment receipt
├── Models/
│   └── Payment.php                     # Payment eloquent model
├── Policies/
│   └── PaymentPolicy.php               # Authorization for payment operations
├── Repositories/
│   ├── Contracts/
│   │   └── PaymentRepositoryInterface.php
│   └── PaymentRepository.php           # Payment data access layer
├── Requests/
│   ├── InitiatePaymentRequest.php      # Validation for starting payment
│   └── VerifyPaymentRequest.php        # Validation for payment verification
├── Services/
│   ├── Gateways/
│   │   ├── PaymentGatewayInterface.php # Gateway contract
│   │   ├── RazorpayGateway.php         # Razorpay integration
│   │   ├── StripeGateway.php           # Stripe integration
│   │   └── PayPalGateway.php           # PayPal integration
│   ├── PaymentService.php              # Payment business logic
│   └── RefundService.php               # Refund processing
└── Routes.php                          # Payment routes (currently empty)
```

## Planned Routes

All payment routes require authentication (`auth` middleware).

### Payment Processing Routes

- **POST** `/payment/initiate` → `payment.initiate` - Start payment process
- **POST** `/payment/verify` → `payment.verify` - Verify payment after gateway callback
- **GET** `/payment/success/{orderId}` → `payment.success` - Payment success page
- **GET** `/payment/failure/{orderId}` → `payment.failure` - Payment failure page
- **POST** `/payment/retry/{orderId}` → `payment.retry` - Retry failed payment

### Payment Gateway Callbacks

- **POST** `/payment/webhook/razorpay` → `payment.webhook.razorpay` - Razorpay webhook
- **POST** `/payment/webhook/stripe` → `payment.webhook.stripe` - Stripe webhook
- **POST** `/payment/webhook/paypal` → `payment.webhook.paypal` - PayPal webhook

### Payment Management Routes (for users)

- **GET** `/payments` → `payments.index` - View payment history
- **GET** `/payments/{id}` → `payments.show` - View payment details
- **GET** `/payments/{id}/receipt` → `payments.receipt` - Download payment receipt
- **POST** `/payments/{id}/refund-request` → `payments.refund.request` - Request refund

### Admin Payment Routes

- **GET** `/admin/payments` → `admin.payments.index` - All payments list
- **POST** `/admin/payments/{id}/refund` → `admin.payments.refund` - Process refund
- **GET** `/admin/payments/reports` → `admin.payments.reports` - Payment analytics

## Planned Database Tables

### Primary Table: payments

**Migration:** `create_payments_table.php` (to be created)

**Columns:**
- `id` (bigint, primary key) - Payment identifier
- `order_id` (bigint, foreign key) - Related order (references orders.id)
- `user_id` (bigint, foreign key) - Customer (references users.id)
- `payment_method` (enum) - Payment method: `cod`, `credit_card`, `debit_card`, `upi`, `net_banking`, `wallet`
- `gateway` (string, nullable) - Payment gateway: `razorpay`, `stripe`, `paypal`, `none` (for COD)
- `transaction_id` (string, unique, nullable) - Gateway transaction ID
- `payment_id` (string, unique, nullable) - Gateway payment ID
- `order_id_gateway` (string, nullable) - Gateway order reference
- `amount` (decimal 10,2) - Payment amount
- `currency` (string, default: 'INR') - Currency code
- `status` (enum) - Payment status: `pending`, `initiated`, `processing`, `success`, `failed`, `refunded`, `partially_refunded`
- `gateway_response` (json, nullable) - Raw gateway response
- `failure_reason` (text, nullable) - Reason for failure
- `paid_at` (timestamp, nullable) - When payment completed
- `refunded_at` (timestamp, nullable) - When refund processed
- `refund_amount` (decimal 10,2, nullable) - Amount refunded
- `refund_reason` (text, nullable) - Reason for refund
- `created_at` (timestamp) - Payment initiated
- `updated_at` (timestamp) - Last update

**Indexes:**
- Unique index on `transaction_id`
- Index on `order_id` for order lookups
- Index on `user_id` for user payment history
- Index on `status` for filtering
- Index on `created_at` for sorting

**Constraints:**
- Foreign key to orders table
- Foreign key to users table
- Amount must be positive

### Related Tables:
- **orders** - Payment belongs to order
- **users** - Payment made by user
- **refunds** - Separate refund tracking table (optional)

## Features & Functionality

### 1. Initiate Payment

**Route:** `POST /payment/initiate`

**Validation Rules:**
```php
'order_id' => 'required|exists:orders,id',
'payment_method' => 'required|in:cod,credit_card,debit_card,upi,net_banking,wallet',
'gateway' => 'required_unless:payment_method,cod|in:razorpay,stripe,paypal'
```

**Process:**
1. Validate order belongs to user
2. Verify order is in pending payment status
3. Calculate payment amount from order.total
4. **For COD:**
   - Mark payment as success immediately
   - No gateway integration needed
5. **For Online Payments:**
   - Create payment record with status `initiated`
   - Call payment gateway API to create order
   - Get payment URL/token from gateway
   - Fire `PaymentInitiated` event
6. Return payment URL or redirect to gateway

**Response:**
```json
{
    "success": true,
    "payment_id": "pay_abc123",
    "order_id": "order_xyz789",
    "amount": 4230.00,
    "currency": "INR",
    "payment_url": "https://razorpay.com/checkout/...",
    "callback_url": "https://yoursite.com/payment/verify"
}
```

### 2. Verify Payment

**Route:** `POST /payment/verify`

**Process:**
1. Receive callback from payment gateway
2. Extract transaction details (payment_id, signature, status)
3. **Verify Payment Signature:**
   ```php
   $expectedSignature = hash_hmac('sha256', 
       $orderId . '|' . $paymentId, 
       $gatewaySecret
   );
   
   if ($expectedSignature !== $receivedSignature) {
       throw new PaymentVerificationException();
   }
   ```
4. **Query Gateway API** for payment status (double verification)
5. Update payment record:
   - Set status to `success` or `failed`
   - Store transaction_id
   - Store gateway_response (JSON)
   - Set paid_at timestamp
6. **Update Order:**
   - Set order.payment_status to `paid`
   - Set order.status to `processing`
7. Fire `PaymentSuccessful` or `PaymentFailed` event
8. Redirect to success/failure page

**Security Measures:**
- Signature verification
- Double verification with gateway API
- IP whitelist for webhooks
- HTTPS required
- Idempotency (prevent duplicate processing)

### 3. Payment Success

**Route:** `GET /payment/success/{orderId}`

**Output:**
```
✓ Payment Successful!

Payment Details
---------------
Order Number: ORD-20260210-001
Amount Paid: ₹4,230.00
Payment Method: UPI
Transaction ID: txn_abc123xyz
Date: February 10, 2026, 10:30 AM

Your order is confirmed and will be processed shortly.

[View Order Details]  [Download Receipt]
```

### 4. Payment Failure

**Route:** `GET /payment/failure/{orderId}`

**Output:**
```
✗ Payment Failed

Payment Details
---------------
Order Number: ORD-20260210-001
Amount: ₹4,230.00
Reason: Payment declined by bank

Please try again with a different payment method.

[Retry Payment]  [Change Payment Method]  [Contact Support]
```

### 5. Payment History

**Route:** `GET /payments`

**Output:**
- Table of all user payments
- Columns: Date, Order Number, Amount, Method, Status, Receipt
- Filter by status
- Search by transaction ID
- Pagination

### 6. Payment Receipt

**Route:** `GET /payments/{id}/receipt`

**Output:**
- PDF receipt with:
  - Transaction details
  - Order information
  - Amount breakdown
  - Payment method
  - Timestamp
  - Company information

### 7. Process Refund

**Route:** `POST /admin/payments/{id}/refund` (Admin only)

**Validation Rules:**
```php
'refund_amount' => 'required|numeric|min:1|max:' . $payment->amount,
'refund_reason' => 'required|string|max:500',
'refund_type' => 'required|in:full,partial'
```

**Process:**
1. Verify payment is refundable (status = success)
2. Check refund_amount <= payment.amount
3. **Call Gateway Refund API:**
   ```php
   $gatewayResponse = $gateway->refund([
       'payment_id' => $payment->payment_id,
       'amount' => $refundAmount,
   ]);
   ```
4. Update payment record:
   - status = `refunded` or `partially_refunded`
   - refund_amount = amount
   - refund_reason = reason
   - refunded_at = now()
5. Update order.payment_status to `refunded`
6. Fire `RefundProcessed` event
7. Send refund confirmation email

**Response:**
- Success: "Refund of ₹X processed successfully. Amount will be credited in 5-7 business days."
- Error: "Refund failed. Gateway error: [message]"

## Payment Methods Supported

### 1. Cash on Delivery (COD)
- **Status:** Immediately successful
- **Gateway:** None
- **Process:** Mark as paid on delivery
- **Verification:** Manual by delivery agent

### 2. Credit/Debit Cards
- **Gateway:** Razorpay, Stripe
- **Cards:** Visa, Mastercard, Amex, RuPay
- **Security:** 3D Secure, CVV required
- **Process:** Gateway-hosted checkout

### 3. UPI (Unified Payments Interface)
- **Gateway:** Razorpay
- **Apps:** Google Pay, PhonePe, Paytm, etc.
- **Process:** Scan QR or enter UPI ID
- **Verification:** Instant

### 4. Net Banking
- **Gateway:** Razorpay, PayPal
- **Banks:** All major Indian banks
- **Process:** Redirect to bank portal
- **Verification:** After bank confirmation

### 5. Digital Wallets
- **Wallets:** Paytm, PhonePe, Amazon Pay
- **Gateway:** Razorpay
- **Process:** Wallet selection → authentication
- **Verification:** Instant

## Payment Gateway Integration

### Razorpay Integration

**Configuration:**
```php
// config/services.php
'razorpay' => [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
],
```

**Create Order:**
```php
use Razorpay\Api\Api;

$api = new Api($key, $secret);

$order = $api->order->create([
    'amount' => $amount * 100, // Amount in paise
    'currency' => 'INR',
    'receipt' => $orderNumber,
    'payment_capture' => 1, // Auto capture
]);
```

**Verify Payment:**
```php
$attributes = [
    'razorpay_order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_signature' => $signature,
];

try {
    $api->utility->verifyPaymentSignature($attributes);
    // Payment verified
} catch (SignatureVerificationError $e) {
    // Verification failed
}
```

### Stripe Integration

**Configuration:**
```php
// config/services.php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],
```

**Create Payment Intent:**
```php
use Stripe\StripeClient;

$stripe = new StripeClient($secret);

$paymentIntent = $stripe->paymentIntents->create([
    'amount' => $amount * 100, // Amount in cents
    'currency' => 'inr',
    'metadata' => ['order_id' => $orderId],
]);
```

## How to Use

### For End Users

#### Making a Payment

1. **Complete Checkout:**
   ```
   Cart → Checkout → Select Payment Method
   ```

2. **Choose Payment Method:**
   - Cash on Delivery (no online payment)
   - Credit/Debit Card
   - UPI
   - Net Banking
   - Wallet

3. **Complete Payment:**
   - Redirected to payment gateway
   - Enter payment details
   - Complete authentication (OTP, 3D Secure)
   - Wait for confirmation

4. **Payment Confirmation:**
   - Redirected to success page
   - Receive confirmation email
   - View order status updated

#### Viewing Payment History

1. **Access Payments:**
   ```
   My Account → Payments
   Or visit: /payments
   ```

2. **View Details:**
   - Click on any payment
   - See transaction details
   - Download receipt

### For Developers

#### Processing a Payment

```php
use App\Modules\Payment\Services\PaymentService;

$paymentService = app(PaymentService::class);

// Initiate payment
$payment = $paymentService->initiatePayment([
    'order_id' => $orderId,
    'payment_method' => 'upi',
    'gateway' => 'razorpay',
]);

// Get payment URL
$paymentUrl = $payment->payment_url;

// Redirect user to gateway
return redirect($paymentUrl);
```

#### Verifying a Payment

```php
// In payment callback
$paymentService->verifyPayment([
    'payment_id' => $request->payment_id,
    'order_id' => $request->order_id,
    'signature' => $request->signature,
]);
```

#### Processing a Refund

```php
use App\Modules\Payment\Services\RefundService;

$refundService = app(RefundService::class);

$refund = $refundService->processRefund([
    'payment_id' => $paymentId,
    'amount' => $refundAmount,
    'reason' => 'Customer request',
]);
```

#### Using PaymentDTO

```php
$paymentDTO = $paymentService->getPayment($paymentId);

echo $paymentDTO->transactionId;
echo $paymentDTO->amount;
echo $paymentDTO->status;
echo $paymentDTO->paymentMethod;
echo $paymentDTO->paidAt;
```

## Events & Listeners

### PaymentInitiated Event
**Triggered:** When payment process starts

**Data:**
- payment (Payment model)
- order (Order model)

### PaymentSuccessful Event
**Triggered:** When payment completes successfully

**Data:**
- payment (Payment model)
- order (Order model)
- transactionId

**Listeners:**
- `UpdateOrderPaymentStatus` - Sets order.payment_status = 'paid'
- `SendPaymentReceipt` - Emails receipt to customer
- Update inventory
- Trigger order processing

### PaymentFailed Event
**Triggered:** When payment fails

**Data:**
- payment (Payment model)
- order (Order model)
- failureReason

**Listeners:**
- Send failure notification email
- Log failure for analysis
- Offer retry option

### RefundProcessed Event
**Triggered:** When refund completed

**Data:**
- payment (Payment model)
- refundAmount
- refundReason

**Listeners:**
- Update order status
- Send refund confirmation email
- Restore inventory (if applicable)

## Security & Best Practices

### Payment Security
- Never store full card details (PCI-DSS compliance)
- Use gateway-hosted checkout pages
- Implement signature verification
- Use HTTPS for all payment pages
- Validate all gateway callbacks server-side

### Transaction Integrity
- Use database transactions
- Implement idempotency keys
- Double-verify payment status with gateway
- Log all payment attempts
- Monitor for duplicate transactions

### Data Protection
- Encrypt sensitive data
- Mask card numbers (show last 4 digits only)
- Secure webhook endpoints
- IP whitelist for gateway callbacks
- Rate limiting on payment endpoints

## Testing

### Test Mode
All gateways provide test mode with test credentials.

**Razorpay Test Cards:**
```
Success: 4111 1111 1111 1111
CVV: Any 3 digits
Expiry: Any future date
```

**Stripe Test Cards:**
```
Success: 4242 4242 4242 4242
Decline: 4000 0000 0000 0002
```

### Feature Tests
```bash
# Run payment tests
php artisan test tests/Feature/Payment
```

**Test Coverage:**
- Initiate payment
- Verify successful payment
- Handle failed payment
- Process refund
- Webhook handling
- Payment authorization

## Integration with Other Modules

### Dependencies:
- **Order Module** - Payment for orders
- **User Module** - User making payment

### Used By:
- **Checkout Process** - Payment step
- **Admin Module** - Payment management
- **Order Module** - Updates order status

## Common Issues & Solutions

### Issue: "Payment successful but order not updated"
**Cause:** Webhook not received or callback failed
**Solution:** Implement fallback verification, check webhook URL

### Issue: "Duplicate payment attempt"
**Cause:** User clicked multiple times
**Solution:** Implement idempotency keys, disable button after click

### Issue: "Refund failed"
**Cause:** Gateway API error or insufficient balance
**Solution:** Retry refund, manual processing if needed

### Issue: "Payment verification failed"
**Cause:** Signature mismatch or network timeout
**Solution:** Re-verify with gateway API, log for investigation

## Configuration

### Environment Variables

```env
# Razorpay
RAZORPAY_KEY=rzp_test_xxxxx
RAZORPAY_SECRET=xxxxx
RAZORPAY_WEBHOOK_SECRET=xxxxx

# Stripe
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx

# PayPal
PAYPAL_CLIENT_ID=xxxxx
PAYPAL_SECRET=xxxxx
PAYPAL_MODE=sandbox # or 'live'

# Payment Settings
PAYMENT_CURRENCY=INR
PAYMENT_TIMEOUT=300 # seconds
REFUND_ENABLED=true
COD_ENABLED=true
COD_MAX_AMOUNT=50000
```

## Implementation Checklist

### Phase 1: Basic Setup
- [ ] Create payments table migration
- [ ] Create Payment model
- [ ] Implement PaymentController
- [ ] Define payment routes
- [ ] Create payment views

### Phase 2: Gateway Integration
- [ ] Install Razorpay PHP SDK
- [ ] Implement RazorpayGateway service
- [ ] Setup webhook endpoint
- [ ] Test payment flow end-to-end
- [ ] Implement payment verification

### Phase 3: Features
- [ ] Implement COD support
- [ ] Add retry payment functionality
- [ ] Create payment history page
- [ ] Generate payment receipts (PDF)
- [ ] Add refund functionality

### Phase 4: Testing & Security
- [ ] Write feature tests
- [ ] Security audit
- [ ] Load testing
- [ ] PCI-DSS compliance review
- [ ] Penetration testing

### Phase 5: Production
- [ ] Setup production gateway credentials
- [ ] Configure webhooks
- [ ] Enable monitoring
- [ ] Deploy to production
- [ ] Monitor payment metrics

## Future Enhancements
- International payment methods
- Cryptocurrency payments
- EMI/installment options
- Subscription payments
- Split payments (multiple beneficiaries)
- Currency conversion
- Dynamic routing (multiple gateways)
- Smart retry logic
- Payment fraud detection
- Payment analytics dashboard
- Automated reconciliation
- Payment link generation
- QR code payments

---

**Module Status:** 🚧 **In Development**  
**Module Version:** 0.1 (Placeholder)  
**Last Updated:** February 2026  
**Maintained By:** Development Team

**Note:** This documentation serves as a specification for implementing the Payment module. The actual implementation should follow these guidelines while adapting to specific business requirements and compliance needs.
