import { useState } from "react";
import { motion } from "framer-motion";
import { Link, useLocation } from "react-router-dom";
import {
  LayoutDashboard, Package, ShoppingCart, Users, TrendingUp,
  DollarSign, Eye, ArrowUpRight, ArrowDownRight,
} from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

const stats = [
  { label: "Revenue", value: "$24,530", change: "+12.5%", up: true, icon: DollarSign },
  { label: "Orders", value: "356", change: "+8.2%", up: true, icon: ShoppingCart },
  { label: "Customers", value: "1,245", change: "+3.1%", up: true, icon: Users },
  { label: "Page Views", value: "18.2K", change: "-2.4%", up: false, icon: Eye },
];

const recentOrders = [
  { id: "LX-7823", customer: "Sarah M.", total: "$299.99", status: "Delivered", date: "Today" },
  { id: "LX-7822", customer: "James K.", total: "$189.99", status: "Shipped", date: "Today" },
  { id: "LX-7821", customer: "Emily R.", total: "$459.99", status: "Processing", date: "Yesterday" },
  { id: "LX-7820", customer: "Mike T.", total: "$79.99", status: "Processing", date: "Yesterday" },
  { id: "LX-7819", customer: "Anna L.", total: "$149.99", status: "Delivered", date: "2 days ago" },
];

const statusColor: Record<string, string> = {
  Delivered: "bg-green-500/10 text-green-600 dark:text-green-400",
  Shipped: "bg-blue-500/10 text-blue-600 dark:text-blue-400",
  Processing: "bg-yellow-500/10 text-yellow-600 dark:text-yellow-400",
};

const sidebarLinks = [
  { label: "Dashboard", path: "/admin", icon: LayoutDashboard },
  { label: "Products", path: "/admin/products", icon: Package },
  { label: "Orders", path: "/admin/orders", icon: ShoppingCart },
  { label: "Customers", path: "/admin/users", icon: Users },
];

export const AdminSidebar = () => {
  const location = useLocation();
  return (
    <aside className="hidden w-64 shrink-0 border-r border-border bg-card p-6 lg:block">
      <h2 className="mb-6 text-lg font-bold">Admin Panel</h2>
      <nav className="space-y-1">
        {sidebarLinks.map((link) => (
          <Link
            key={link.path}
            to={link.path}
            className={`flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-colors ${
              location.pathname === link.path
                ? "bg-accent text-accent-foreground"
                : "text-muted-foreground hover:bg-muted hover:text-foreground"
            }`}
          >
            <link.icon className="h-5 w-5" />
            {link.label}
          </Link>
        ))}
      </nav>
    </aside>
  );
};

const Dashboard = () => {
  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <div className="flex pt-20">
        <AdminSidebar />
        <main className="flex-1 p-8">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="mb-2 text-3xl font-bold">Dashboard</h1>
            <p className="mb-8 text-muted-foreground">Overview of your store performance.</p>
          </motion.div>

          {/* Stats Grid */}
          <div className="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {stats.map((stat, i) => (
              <motion.div key={stat.label} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.1 }}>
                <Card>
                  <CardHeader className="flex flex-row items-center justify-between pb-2">
                    <CardTitle className="text-sm font-medium text-muted-foreground">{stat.label}</CardTitle>
                    <stat.icon className="h-4 w-4 text-muted-foreground" />
                  </CardHeader>
                  <CardContent>
                    <p className="text-2xl font-bold">{stat.value}</p>
                    <div className={`mt-1 flex items-center gap-1 text-xs ${stat.up ? "text-green-600" : "text-red-500"}`}>
                      {stat.up ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />}
                      {stat.change} from last month
                    </div>
                  </CardContent>
                </Card>
              </motion.div>
            ))}
          </div>

          {/* Recent Orders */}
          <Card>
            <CardHeader className="flex flex-row items-center justify-between">
              <CardTitle>Recent Orders</CardTitle>
              <Link to="/admin/orders" className="text-sm text-accent hover:underline">View All</Link>
            </CardHeader>
            <CardContent>
              <div className="overflow-x-auto">
                <table className="w-full text-sm">
                  <thead>
                    <tr className="border-b border-border text-left text-muted-foreground">
                      <th className="pb-3 font-medium">Order</th>
                      <th className="pb-3 font-medium">Customer</th>
                      <th className="pb-3 font-medium">Total</th>
                      <th className="pb-3 font-medium">Status</th>
                      <th className="pb-3 font-medium">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {recentOrders.map((order) => (
                      <tr key={order.id} className="border-b border-border last:border-0">
                        <td className="py-3 font-medium">{order.id}</td>
                        <td className="py-3">{order.customer}</td>
                        <td className="py-3">{order.total}</td>
                        <td className="py-3">
                          <span className={`rounded-full px-3 py-1 text-xs font-semibold ${statusColor[order.status]}`}>
                            {order.status}
                          </span>
                        </td>
                        <td className="py-3 text-muted-foreground">{order.date}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </CardContent>
          </Card>
        </main>
      </div>
    </div>
  );
};

export default Dashboard;
