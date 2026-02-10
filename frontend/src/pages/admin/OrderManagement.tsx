import { useState } from "react";
import { motion } from "framer-motion";
import { Search, Eye } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { AdminSidebar } from "@/pages/admin/Dashboard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { toast } from "sonner";

const mockOrders = [
  { id: "LX-7823", customer: "Sarah Mitchell", email: "sarah@mail.com", total: 489.98, status: "Delivered", date: "Jan 15, 2025", items: 2 },
  { id: "LX-7822", customer: "James Kim", email: "james@mail.com", total: 149.99, status: "Shipped", date: "Jan 14, 2025", items: 1 },
  { id: "LX-7821", customer: "Emily Ross", email: "emily@mail.com", total: 459.99, status: "Processing", date: "Jan 13, 2025", items: 1 },
  { id: "LX-7820", customer: "Mike Torres", email: "mike@mail.com", total: 79.99, status: "Processing", date: "Jan 12, 2025", items: 1 },
  { id: "LX-7819", customer: "Anna Lee", email: "anna@mail.com", total: 349.98, status: "Delivered", date: "Jan 11, 2025", items: 3 },
  { id: "LX-7818", customer: "David Chen", email: "david@mail.com", total: 199.99, status: "Cancelled", date: "Jan 10, 2025", items: 1 },
];

const statusColor: Record<string, string> = {
  Delivered: "bg-green-500/10 text-green-600 dark:text-green-400",
  Shipped: "bg-blue-500/10 text-blue-600 dark:text-blue-400",
  Processing: "bg-yellow-500/10 text-yellow-600 dark:text-yellow-400",
  Cancelled: "bg-destructive/10 text-destructive",
};

const OrderManagement = () => {
  const [search, setSearch] = useState("");
  const [orders, setOrders] = useState(mockOrders);
  const [filterStatus, setFilterStatus] = useState("all");

  const filtered = orders.filter((o) => {
    const matchesSearch = o.customer.toLowerCase().includes(search.toLowerCase()) || o.id.toLowerCase().includes(search.toLowerCase());
    const matchesStatus = filterStatus === "all" || o.status === filterStatus;
    return matchesSearch && matchesStatus;
  });

  const updateStatus = (id: string, newStatus: string) => {
    setOrders((prev) => prev.map((o) => (o.id === id ? { ...o, status: newStatus } : o)));
    toast.success(`Order ${id} marked as ${newStatus} — UI only.`);
  };

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <div className="flex pt-20">
        <AdminSidebar />
        <main className="flex-1 p-8">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="mb-2 text-3xl font-bold">Orders</h1>
            <p className="mb-8 text-muted-foreground">{orders.length} orders total</p>
          </motion.div>

          {/* Filters */}
          <div className="mb-6 flex flex-wrap gap-4">
            <div className="relative max-w-sm flex-1">
              <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
              <Input placeholder="Search orders..." value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
            </div>
            <Select value={filterStatus} onValueChange={setFilterStatus}>
              <SelectTrigger className="w-[160px]">
                <SelectValue placeholder="All Status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Status</SelectItem>
                <SelectItem value="Processing">Processing</SelectItem>
                <SelectItem value="Shipped">Shipped</SelectItem>
                <SelectItem value="Delivered">Delivered</SelectItem>
                <SelectItem value="Cancelled">Cancelled</SelectItem>
              </SelectContent>
            </Select>
          </div>

          {/* Table */}
          <div className="overflow-x-auto rounded-2xl border border-border bg-card">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border text-left">
                  <th className="p-4 font-medium text-muted-foreground">Order</th>
                  <th className="p-4 font-medium text-muted-foreground">Customer</th>
                  <th className="p-4 font-medium text-muted-foreground">Items</th>
                  <th className="p-4 font-medium text-muted-foreground">Total</th>
                  <th className="p-4 font-medium text-muted-foreground">Status</th>
                  <th className="p-4 font-medium text-muted-foreground">Date</th>
                  <th className="p-4 font-medium text-muted-foreground">Actions</th>
                </tr>
              </thead>
              <tbody>
                {filtered.map((order) => (
                  <tr key={order.id} className="border-b border-border last:border-0 transition-colors hover:bg-muted/50">
                    <td className="p-4 font-medium">{order.id}</td>
                    <td className="p-4">
                      <p>{order.customer}</p>
                      <p className="text-xs text-muted-foreground">{order.email}</p>
                    </td>
                    <td className="p-4">{order.items}</td>
                    <td className="p-4 font-medium">${order.total.toFixed(2)}</td>
                    <td className="p-4">
                      <Select
                        value={order.status}
                        onValueChange={(val) => updateStatus(order.id, val)}
                      >
                        <SelectTrigger className="h-8 w-[130px]">
                          <span className={`rounded-full px-2 py-0.5 text-xs font-semibold ${statusColor[order.status]}`}>
                            {order.status}
                          </span>
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="Processing">Processing</SelectItem>
                          <SelectItem value="Shipped">Shipped</SelectItem>
                          <SelectItem value="Delivered">Delivered</SelectItem>
                          <SelectItem value="Cancelled">Cancelled</SelectItem>
                        </SelectContent>
                      </Select>
                    </td>
                    <td className="p-4 text-muted-foreground">{order.date}</td>
                    <td className="p-4">
                      <Button variant="ghost" size="icon"><Eye className="h-4 w-4" /></Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  );
};

export default OrderManagement;
