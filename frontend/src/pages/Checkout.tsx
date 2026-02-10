import { useState } from "react";
import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import { MapPin, CreditCard, ChevronRight, Check, Lock } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { useCart } from "@/context/CartContext";
import { toast } from "sonner";

const savedAddresses = [
  { id: "1", label: "Home", address: "123 Main Street, New York, NY 10001" },
  { id: "2", label: "Office", address: "456 Business Ave, Suite 200, San Francisco, CA 94102" },
];

const Checkout = () => {
  const { items, subtotal, clearCart } = useCart();
  const [selectedAddress, setSelectedAddress] = useState("1");
  const [step, setStep] = useState<"address" | "payment" | "review">("address");
  const [orderPlaced, setOrderPlaced] = useState(false);

  const shipping = subtotal > 50 ? 0 : 9.99;
  const tax = subtotal * 0.08;
  const total = subtotal + shipping + tax;

  const steps = [
    { key: "address", label: "Address", icon: MapPin },
    { key: "payment", label: "Payment", icon: CreditCard },
    { key: "review", label: "Review", icon: Check },
  ] as const;

  const handlePlaceOrder = () => {
    setOrderPlaced(true);
    clearCart();
    toast.success("Order placed — UI only.");
  };

  if (orderPlaced) {
    return (
      <div className="min-h-screen bg-background">
        <Navbar />
        <main className="flex min-h-[70vh] items-center justify-center pt-20">
          <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} className="text-center">
            <div className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-accent/10">
              <Check className="h-10 w-10 text-accent" />
            </div>
            <h1 className="mb-3 text-3xl font-bold">Order Confirmed!</h1>
            <p className="mb-2 text-muted-foreground">Your order #LX-2024-7823 has been placed successfully.</p>
            <p className="mb-8 text-sm text-muted-foreground">We'll send you a confirmation email shortly.</p>
            <div className="flex justify-center gap-4">
              <Link to="/orders">
                <Button className="bg-accent text-accent-foreground hover:bg-accent/90">View Orders</Button>
              </Link>
              <Link to="/products">
                <Button variant="outline">Continue Shopping</Button>
              </Link>
            </div>
          </motion.div>
        </main>
        <Footer />
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="min-h-screen bg-background">
        <Navbar />
        <main className="flex min-h-[60vh] items-center justify-center pt-20 text-center">
          <div>
            <h1 className="mb-4 text-2xl font-bold">Your cart is empty</h1>
            <Link to="/products">
              <Button className="bg-accent text-accent-foreground hover:bg-accent/90">Start Shopping</Button>
            </Link>
          </div>
        </main>
        <Footer />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="pt-20">
        <section className="border-b border-border bg-secondary/30 py-12">
          <div className="container-custom">
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <h1 className="mb-6 text-4xl font-bold">Checkout</h1>
              {/* Stepper */}
              <div className="flex items-center gap-4">
                {steps.map((s, i) => (
                  <div key={s.key} className="flex items-center gap-4">
                    <button
                      onClick={() => setStep(s.key)}
                      className={`flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all ${
                        step === s.key
                          ? "bg-accent text-accent-foreground"
                          : "text-muted-foreground hover:text-foreground"
                      }`}
                    >
                      <s.icon className="h-4 w-4" />
                      {s.label}
                    </button>
                    {i < steps.length - 1 && <ChevronRight className="h-4 w-4 text-muted-foreground" />}
                  </div>
                ))}
              </div>
            </motion.div>
          </div>
        </section>

        <div className="container-custom py-12">
          <div className="flex flex-col gap-12 lg:flex-row">
            {/* Main Content */}
            <div className="flex-1">
              {step === "address" && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="space-y-6">
                  <h2 className="text-xl font-bold">Shipping Address</h2>
                  {savedAddresses.map((addr) => (
                    <label
                      key={addr.id}
                      className={`flex cursor-pointer items-start gap-4 rounded-2xl border p-6 transition-all ${
                        selectedAddress === addr.id ? "border-accent bg-accent/5" : "border-border"
                      }`}
                    >
                      <input
                        type="radio"
                        name="address"
                        checked={selectedAddress === addr.id}
                        onChange={() => setSelectedAddress(addr.id)}
                        className="mt-1 accent-[hsl(var(--accent))]"
                      />
                      <div>
                        <p className="font-semibold">{addr.label}</p>
                        <p className="text-sm text-muted-foreground">{addr.address}</p>
                      </div>
                    </label>
                  ))}
                  <Button variant="outline" className="gap-2">
                    <MapPin className="h-4 w-4" /> Add New Address
                  </Button>
                  <div className="pt-4">
                    <Button onClick={() => setStep("payment")} className="bg-accent text-accent-foreground hover:bg-accent/90">
                      Continue to Payment
                    </Button>
                  </div>
                </motion.div>
              )}

              {step === "payment" && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="max-w-lg space-y-6">
                  <h2 className="text-xl font-bold">Payment Method</h2>
                  <div className="rounded-2xl border border-border p-6">
                    <div className="mb-4 flex items-center gap-2 text-sm text-muted-foreground">
                      <Lock className="h-4 w-4" /> Your payment info is encrypted
                    </div>
                    <div className="space-y-4">
                      <div className="space-y-2">
                        <Label>Card Number</Label>
                        <Input placeholder="1234 5678 9012 3456" />
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label>Expiry Date</Label>
                          <Input placeholder="MM/YY" />
                        </div>
                        <div className="space-y-2">
                          <Label>CVV</Label>
                          <Input placeholder="123" />
                        </div>
                      </div>
                      <div className="space-y-2">
                        <Label>Cardholder Name</Label>
                        <Input placeholder="John Doe" />
                      </div>
                    </div>
                  </div>
                  <div className="flex gap-3">
                    <Button variant="outline" onClick={() => setStep("address")}>Back</Button>
                    <Button onClick={() => setStep("review")} className="bg-accent text-accent-foreground hover:bg-accent/90">
                      Review Order
                    </Button>
                  </div>
                </motion.div>
              )}

              {step === "review" && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="space-y-6">
                  <h2 className="text-xl font-bold">Review Order</h2>
                  <div className="space-y-4">
                    {items.map((item) => (
                      <div key={item.id} className="flex items-center gap-4 rounded-xl border border-border p-4">
                        <div className="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-secondary">
                          <img src={item.image} alt={item.name} className="h-full w-full object-cover" />
                        </div>
                        <div className="flex-1">
                          <p className="font-semibold">{item.name}</p>
                          <p className="text-sm text-muted-foreground">Qty: {item.quantity}</p>
                        </div>
                        <p className="font-semibold">${(item.price * item.quantity).toFixed(2)}</p>
                      </div>
                    ))}
                  </div>
                  <div className="flex gap-3">
                    <Button variant="outline" onClick={() => setStep("payment")}>Back</Button>
                    <Button onClick={handlePlaceOrder} className="gap-2 bg-accent text-accent-foreground hover:bg-accent/90" size="lg">
                      Place Order — ${total.toFixed(2)}
                    </Button>
                  </div>
                </motion.div>
              )}
            </div>

            {/* Order Summary Sidebar */}
            <div className="h-fit w-full rounded-2xl border border-border bg-card p-6 lg:w-96">
              <h2 className="mb-6 text-xl font-bold">Order Summary</h2>
              <div className="space-y-3 text-sm">
                {items.map((item) => (
                  <div key={item.id} className="flex justify-between">
                    <span className="text-muted-foreground">{item.name} × {item.quantity}</span>
                    <span>${(item.price * item.quantity).toFixed(2)}</span>
                  </div>
                ))}
              </div>
              <Separator className="my-4" />
              <div className="space-y-3 text-sm">
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Subtotal</span>
                  <span>${subtotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Shipping</span>
                  <span>{shipping === 0 ? <span className="text-accent">Free</span> : `$${shipping.toFixed(2)}`}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Tax</span>
                  <span>${tax.toFixed(2)}</span>
                </div>
              </div>
              <Separator className="my-4" />
              <div className="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>${total.toFixed(2)}</span>
              </div>
            </div>
          </div>
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Checkout;
