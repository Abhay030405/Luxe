import { useParams, Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Package, Truck, CheckCircle2, Clock, MapPin, CreditCard, ArrowLeft } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { Separator } from "@/components/ui/separator";

const orderData = {
  id: "LX-2024-7823",
  date: "Jan 15, 2025",
  status: "Shipped",
  address: "123 Main Street, New York, NY 10001",
  payment: "Visa ending in 4242",
  items: [
    { name: "Premium Wireless Headphones", qty: 1, price: 299.99, image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80" },
    { name: "Designer Leather Bag", qty: 1, price: 189.99, image: "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=200&q=80" },
  ],
  subtotal: 489.98,
  shipping: 0,
  tax: 39.20,
  total: 529.18,
  timeline: [
    { label: "Order Placed", date: "Jan 15, 2025", done: true },
    { label: "Processing", date: "Jan 15, 2025", done: true },
    { label: "Shipped", date: "Jan 17, 2025", done: true },
    { label: "Delivered", date: "Expected Jan 22", done: false },
  ],
};

const OrderDetail = () => {
  const { id } = useParams();

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="pt-20">
        <section className="border-b border-border bg-secondary/30 py-12">
          <div className="container-custom">
            <Link to="/orders" className="mb-4 inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground">
              <ArrowLeft className="h-4 w-4" /> Back to Orders
            </Link>
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <h1 className="mb-1 text-4xl font-bold">Order {id || orderData.id}</h1>
              <p className="text-muted-foreground">Placed on {orderData.date}</p>
            </motion.div>
          </div>
        </section>

        <div className="container-custom py-12">
          <div className="grid gap-8 lg:grid-cols-3">
            {/* Main */}
            <div className="space-y-8 lg:col-span-2">
              {/* Timeline */}
              <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="rounded-2xl border border-border bg-card p-6">
                <h2 className="mb-6 text-lg font-bold">Order Status</h2>
                <div className="relative">
                  <div className="absolute left-[15px] top-0 h-full w-0.5 bg-border" />
                  <div className="space-y-6">
                    {orderData.timeline.map((step, i) => (
                      <div key={i} className="relative flex items-start gap-4 pl-10">
                        <div
                          className={`absolute left-0 flex h-8 w-8 items-center justify-center rounded-full ${
                            step.done ? "bg-accent text-accent-foreground" : "bg-muted text-muted-foreground"
                          }`}
                        >
                          {step.done ? <CheckCircle2 className="h-4 w-4" /> : <Clock className="h-4 w-4" />}
                        </div>
                        <div>
                          <p className={`font-semibold ${!step.done && "text-muted-foreground"}`}>{step.label}</p>
                          <p className="text-sm text-muted-foreground">{step.date}</p>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </motion.div>

              {/* Items */}
              <div className="rounded-2xl border border-border bg-card p-6">
                <h2 className="mb-4 text-lg font-bold">Items</h2>
                <div className="space-y-4">
                  {orderData.items.map((item, i) => (
                    <div key={i} className="flex items-center gap-4">
                      <div className="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-secondary">
                        <img src={item.image} alt={item.name} className="h-full w-full object-cover" />
                      </div>
                      <div className="flex-1">
                        <p className="font-semibold">{item.name}</p>
                        <p className="text-sm text-muted-foreground">Qty: {item.qty}</p>
                      </div>
                      <p className="font-semibold">${item.price.toFixed(2)}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Sidebar */}
            <div className="space-y-6">
              <div className="rounded-2xl border border-border bg-card p-6">
                <h2 className="mb-4 text-lg font-bold">Summary</h2>
                <div className="space-y-3 text-sm">
                  <div className="flex justify-between"><span className="text-muted-foreground">Subtotal</span><span>${orderData.subtotal.toFixed(2)}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Shipping</span><span>{orderData.shipping === 0 ? <span className="text-accent">Free</span> : `$${orderData.shipping.toFixed(2)}`}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Tax</span><span>${orderData.tax.toFixed(2)}</span></div>
                  <Separator />
                  <div className="flex justify-between text-lg font-bold"><span>Total</span><span>${orderData.total.toFixed(2)}</span></div>
                </div>
              </div>

              <div className="rounded-2xl border border-border bg-card p-6">
                <h2 className="mb-4 text-lg font-bold">Details</h2>
                <div className="space-y-4 text-sm">
                  <div className="flex items-start gap-3">
                    <MapPin className="mt-0.5 h-4 w-4 text-muted-foreground" />
                    <div>
                      <p className="font-medium">Shipping Address</p>
                      <p className="text-muted-foreground">{orderData.address}</p>
                    </div>
                  </div>
                  <div className="flex items-start gap-3">
                    <CreditCard className="mt-0.5 h-4 w-4 text-muted-foreground" />
                    <div>
                      <p className="font-medium">Payment</p>
                      <p className="text-muted-foreground">{orderData.payment}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default OrderDetail;
