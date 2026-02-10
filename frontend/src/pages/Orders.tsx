import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Package, ChevronRight, Eye } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";

const mockOrders = [
  {
    id: "LX-2024-7823",
    date: "Jan 15, 2025",
    status: "Delivered",
    total: 489.98,
    items: [
      { name: "Premium Wireless Headphones", qty: 1, price: 299.99, image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&q=80" },
      { name: "Designer Leather Bag", qty: 1, price: 189.99, image: "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=100&q=80" },
    ],
  },
  {
    id: "LX-2024-6591",
    date: "Dec 28, 2024",
    status: "Shipped",
    total: 149.99,
    items: [
      { name: "Smart Fitness Tracker", qty: 1, price: 149.99, image: "https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=100&q=80" },
    ],
  },
  {
    id: "LX-2024-5120",
    date: "Nov 10, 2024",
    status: "Processing",
    total: 259.98,
    items: [
      { name: "Minimalist Sunglasses", qty: 2, price: 129.99, image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=100&q=80" },
    ],
  },
];

const statusColor: Record<string, string> = {
  Delivered: "bg-green-500/10 text-green-600 dark:text-green-400",
  Shipped: "bg-blue-500/10 text-blue-600 dark:text-blue-400",
  Processing: "bg-yellow-500/10 text-yellow-600 dark:text-yellow-400",
  Cancelled: "bg-destructive/10 text-destructive",
};

const Orders = () => {
  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="pt-20">
        <section className="border-b border-border bg-secondary/30 py-12">
          <div className="container-custom">
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <h1 className="mb-2 text-4xl font-bold">My Orders</h1>
              <p className="text-muted-foreground">Track and manage your orders.</p>
            </motion.div>
          </div>
        </section>

        <div className="container-custom py-12">
          <div className="space-y-6">
            {mockOrders.map((order, index) => (
              <motion.div
                key={order.id}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: index * 0.1 }}
                className="rounded-2xl border border-border bg-card p-6"
              >
                <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                  <div className="flex items-center gap-4">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-accent/10">
                      <Package className="h-5 w-5 text-accent" />
                    </div>
                    <div>
                      <p className="font-semibold">{order.id}</p>
                      <p className="text-sm text-muted-foreground">{order.date}</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className={`rounded-full px-3 py-1 text-xs font-semibold ${statusColor[order.status]}`}>
                      {order.status}
                    </span>
                    <Link to={`/orders/${order.id}`}>
                      <Button variant="outline" size="sm" className="gap-2">
                        <Eye className="h-4 w-4" /> Details
                      </Button>
                    </Link>
                  </div>
                </div>

                <div className="space-y-3">
                  {order.items.map((item, i) => (
                    <div key={i} className="flex items-center gap-4">
                      <div className="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-secondary">
                        <img src={item.image} alt={item.name} className="h-full w-full object-cover" />
                      </div>
                      <div className="flex-1">
                        <p className="text-sm font-medium">{item.name}</p>
                        <p className="text-xs text-muted-foreground">Qty: {item.qty}</p>
                      </div>
                      <p className="text-sm font-semibold">${item.price.toFixed(2)}</p>
                    </div>
                  ))}
                </div>

                <div className="mt-4 flex justify-end border-t border-border pt-4">
                  <p className="text-lg font-bold">Total: ${order.total.toFixed(2)}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Orders;
