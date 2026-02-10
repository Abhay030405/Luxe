import { useState } from "react";
import { motion } from "framer-motion";
import { Search, MoreHorizontal, Mail, Ban, ShieldCheck } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { AdminSidebar } from "@/pages/admin/Dashboard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { toast } from "sonner";

const mockUsers = [
  { id: "1", name: "Sarah Mitchell", email: "sarah@mail.com", role: "Customer", orders: 12, spent: 2340.50, joined: "Oct 2024", status: "Active" },
  { id: "2", name: "James Kim", email: "james@mail.com", role: "Customer", orders: 5, spent: 890.00, joined: "Nov 2024", status: "Active" },
  { id: "3", name: "Emily Ross", email: "emily@mail.com", role: "Admin", orders: 0, spent: 0, joined: "Sep 2024", status: "Active" },
  { id: "4", name: "Mike Torres", email: "mike@mail.com", role: "Customer", orders: 3, spent: 450.99, joined: "Dec 2024", status: "Suspended" },
  { id: "5", name: "Anna Lee", email: "anna@mail.com", role: "Customer", orders: 8, spent: 1560.00, joined: "Aug 2024", status: "Active" },
];

const UserManagement = () => {
  const [search, setSearch] = useState("");
  const [users] = useState(mockUsers);

  const filtered = users.filter((u) =>
    u.name.toLowerCase().includes(search.toLowerCase()) || u.email.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <div className="flex pt-20">
        <AdminSidebar />
        <main className="flex-1 p-8">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="mb-2 text-3xl font-bold">Customers</h1>
            <p className="mb-8 text-muted-foreground">{users.length} registered users</p>
          </motion.div>

          <div className="relative mb-6 max-w-sm">
            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
            <Input placeholder="Search customers..." value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
          </div>

          <div className="overflow-x-auto rounded-2xl border border-border bg-card">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border text-left">
                  <th className="p-4 font-medium text-muted-foreground">Customer</th>
                  <th className="p-4 font-medium text-muted-foreground">Role</th>
                  <th className="p-4 font-medium text-muted-foreground">Orders</th>
                  <th className="p-4 font-medium text-muted-foreground">Total Spent</th>
                  <th className="p-4 font-medium text-muted-foreground">Joined</th>
                  <th className="p-4 font-medium text-muted-foreground">Status</th>
                  <th className="p-4 font-medium text-muted-foreground">Actions</th>
                </tr>
              </thead>
              <tbody>
                {filtered.map((user) => (
                  <tr key={user.id} className="border-b border-border last:border-0 transition-colors hover:bg-muted/50">
                    <td className="p-4">
                      <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-accent/10 text-sm font-bold text-accent">
                          {user.name.charAt(0)}
                        </div>
                        <div>
                          <p className="font-medium">{user.name}</p>
                          <p className="text-xs text-muted-foreground">{user.email}</p>
                        </div>
                      </div>
                    </td>
                    <td className="p-4">
                      <span className={`rounded-full px-3 py-1 text-xs font-semibold ${
                        user.role === "Admin" ? "bg-accent/10 text-accent" : "bg-muted text-muted-foreground"
                      }`}>
                        {user.role}
                      </span>
                    </td>
                    <td className="p-4">{user.orders}</td>
                    <td className="p-4 font-medium">${user.spent.toFixed(2)}</td>
                    <td className="p-4 text-muted-foreground">{user.joined}</td>
                    <td className="p-4">
                      <span className={`rounded-full px-3 py-1 text-xs font-semibold ${
                        user.status === "Active"
                          ? "bg-green-500/10 text-green-600 dark:text-green-400"
                          : "bg-destructive/10 text-destructive"
                      }`}>
                        {user.status}
                      </span>
                    </td>
                    <td className="p-4">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreHorizontal className="h-4 w-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem onClick={() => toast.success("Email sent — UI only.")}>
                            <Mail className="mr-2 h-4 w-4" /> Send Email
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => toast.success("Role updated — UI only.")}>
                            <ShieldCheck className="mr-2 h-4 w-4" /> Make Admin
                          </DropdownMenuItem>
                          <DropdownMenuItem className="text-destructive" onClick={() => toast.success("User suspended — UI only.")}>
                            <Ban className="mr-2 h-4 w-4" /> Suspend
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
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

export default UserManagement;
