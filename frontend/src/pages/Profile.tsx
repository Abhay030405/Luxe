import { useState } from "react";
import { motion } from "framer-motion";
import { User, MapPin, Lock, Plus, Pencil, Trash2, Check, Camera } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { toast } from "sonner";

const mockAddresses = [
  {
    id: "1",
    label: "Home",
    name: "John Doe",
    street: "123 Main Street",
    city: "New York",
    state: "NY",
    zip: "10001",
    country: "United States",
    phone: "+1 (555) 123-4567",
    isDefault: true,
  },
  {
    id: "2",
    label: "Office",
    name: "John Doe",
    street: "456 Business Ave, Suite 200",
    city: "San Francisco",
    state: "CA",
    zip: "94102",
    country: "United States",
    phone: "+1 (555) 987-6543",
    isDefault: false,
  },
];

const Profile = () => {
  const [addresses, setAddresses] = useState(mockAddresses);
  const [showAddForm, setShowAddForm] = useState(false);

  const handleProfileSave = (e: React.FormEvent) => {
    e.preventDefault();
    toast.success("Profile updated — UI only.");
  };

  const handlePasswordChange = (e: React.FormEvent) => {
    e.preventDefault();
    toast.success("Password changed — UI only.");
  };

  const setDefault = (id: string) => {
    setAddresses((prev) =>
      prev.map((a) => ({ ...a, isDefault: a.id === id }))
    );
    toast.success("Default address updated.");
  };

  const removeAddress = (id: string) => {
    setAddresses((prev) => prev.filter((a) => a.id !== id));
    toast.success("Address removed.");
  };

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="pt-20">
        <section className="border-b border-border bg-secondary/30 py-12">
          <div className="container-custom">
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <h1 className="mb-2 text-4xl font-bold">My Account</h1>
              <p className="text-muted-foreground">Manage your profile, addresses, and security.</p>
            </motion.div>
          </div>
        </section>

        <div className="container-custom py-12">
          <Tabs defaultValue="profile" className="space-y-8">
            <TabsList className="grid w-full max-w-md grid-cols-3">
              <TabsTrigger value="profile" className="gap-2">
                <User className="h-4 w-4" /> Profile
              </TabsTrigger>
              <TabsTrigger value="addresses" className="gap-2">
                <MapPin className="h-4 w-4" /> Addresses
              </TabsTrigger>
              <TabsTrigger value="security" className="gap-2">
                <Lock className="h-4 w-4" /> Security
              </TabsTrigger>
            </TabsList>

            {/* Profile Tab */}
            <TabsContent value="profile">
              <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="max-w-2xl">
                <div className="mb-8 flex items-center gap-6">
                  <div className="relative">
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-accent/10 text-accent">
                      <User className="h-10 w-10" />
                    </div>
                    <button className="absolute -bottom-1 -right-1 flex h-8 w-8 items-center justify-center rounded-full bg-accent text-accent-foreground shadow-lg">
                      <Camera className="h-4 w-4" />
                    </button>
                  </div>
                  <div>
                    <h2 className="text-xl font-bold">John Doe</h2>
                    <p className="text-sm text-muted-foreground">john.doe@example.com</p>
                  </div>
                </div>

                <form onSubmit={handleProfileSave} className="space-y-6">
                  <div className="grid gap-6 sm:grid-cols-2">
                    <div className="space-y-2">
                      <Label>First Name</Label>
                      <Input defaultValue="John" />
                    </div>
                    <div className="space-y-2">
                      <Label>Last Name</Label>
                      <Input defaultValue="Doe" />
                    </div>
                  </div>
                  <div className="space-y-2">
                    <Label>Email</Label>
                    <Input type="email" defaultValue="john.doe@example.com" />
                  </div>
                  <div className="space-y-2">
                    <Label>Phone</Label>
                    <Input type="tel" defaultValue="+1 (555) 123-4567" />
                  </div>
                  <div className="space-y-2">
                    <Label>Date of Birth</Label>
                    <Input type="date" defaultValue="1990-01-15" />
                  </div>
                  <Button type="submit" className="bg-accent text-accent-foreground hover:bg-accent/90">
                    Save Changes
                  </Button>
                </form>
              </motion.div>
            </TabsContent>

            {/* Addresses Tab */}
            <TabsContent value="addresses">
              <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
                <div className="mb-6 flex items-center justify-between">
                  <h2 className="text-xl font-bold">Saved Addresses</h2>
                  <Button
                    onClick={() => setShowAddForm(!showAddForm)}
                    className="gap-2 bg-accent text-accent-foreground hover:bg-accent/90"
                  >
                    <Plus className="h-4 w-4" /> Add Address
                  </Button>
                </div>

                {showAddForm && (
                  <motion.div
                    initial={{ opacity: 0, height: 0 }}
                    animate={{ opacity: 1, height: "auto" }}
                    className="mb-8 rounded-2xl border border-border bg-card p-6"
                  >
                    <h3 className="mb-4 font-semibold">New Address</h3>
                    <form
                      onSubmit={(e) => {
                        e.preventDefault();
                        setShowAddForm(false);
                        toast.success("Address added — UI only.");
                      }}
                      className="space-y-4"
                    >
                      <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                          <Label>Label (e.g. Home, Office)</Label>
                          <Input placeholder="Home" />
                        </div>
                        <div className="space-y-2">
                          <Label>Full Name</Label>
                          <Input placeholder="John Doe" />
                        </div>
                      </div>
                      <div className="space-y-2">
                        <Label>Street Address</Label>
                        <Input placeholder="123 Main Street" />
                      </div>
                      <div className="grid gap-4 sm:grid-cols-3">
                        <div className="space-y-2">
                          <Label>City</Label>
                          <Input placeholder="New York" />
                        </div>
                        <div className="space-y-2">
                          <Label>State</Label>
                          <Input placeholder="NY" />
                        </div>
                        <div className="space-y-2">
                          <Label>ZIP Code</Label>
                          <Input placeholder="10001" />
                        </div>
                      </div>
                      <div className="space-y-2">
                        <Label>Phone</Label>
                        <Input type="tel" placeholder="+1 (555) 000-0000" />
                      </div>
                      <div className="flex gap-3">
                        <Button type="submit" className="bg-accent text-accent-foreground hover:bg-accent/90">
                          Save Address
                        </Button>
                        <Button type="button" variant="outline" onClick={() => setShowAddForm(false)}>
                          Cancel
                        </Button>
                      </div>
                    </form>
                  </motion.div>
                )}

                <div className="grid gap-4 sm:grid-cols-2">
                  {addresses.map((address) => (
                    <div
                      key={address.id}
                      className={`relative rounded-2xl border p-6 transition-all ${
                        address.isDefault ? "border-accent bg-accent/5" : "border-border bg-card"
                      }`}
                    >
                      {address.isDefault && (
                        <span className="absolute right-4 top-4 flex items-center gap-1 rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                          <Check className="h-3 w-3" /> Default
                        </span>
                      )}
                      <h3 className="mb-1 font-semibold">{address.label}</h3>
                      <p className="text-sm text-muted-foreground">{address.name}</p>
                      <p className="text-sm text-muted-foreground">{address.street}</p>
                      <p className="text-sm text-muted-foreground">
                        {address.city}, {address.state} {address.zip}
                      </p>
                      <p className="text-sm text-muted-foreground">{address.phone}</p>
                      <Separator className="my-4" />
                      <div className="flex gap-2">
                        {!address.isDefault && (
                          <Button variant="outline" size="sm" onClick={() => setDefault(address.id)}>
                            Set Default
                          </Button>
                        )}
                        <Button variant="ghost" size="sm">
                          <Pencil className="h-4 w-4" />
                        </Button>
                        <Button
                          variant="ghost"
                          size="sm"
                          className="text-destructive hover:text-destructive"
                          onClick={() => removeAddress(address.id)}
                        >
                          <Trash2 className="h-4 w-4" />
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
              </motion.div>
            </TabsContent>

            {/* Security Tab */}
            <TabsContent value="security">
              <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="max-w-2xl">
                <h2 className="mb-6 text-xl font-bold">Change Password</h2>
                <form onSubmit={handlePasswordChange} className="space-y-6">
                  <div className="space-y-2">
                    <Label>Current Password</Label>
                    <Input type="password" placeholder="••••••••" />
                  </div>
                  <div className="space-y-2">
                    <Label>New Password</Label>
                    <Input type="password" placeholder="••••••••" />
                  </div>
                  <div className="space-y-2">
                    <Label>Confirm New Password</Label>
                    <Input type="password" placeholder="••••••••" />
                  </div>
                  <Button type="submit" className="bg-accent text-accent-foreground hover:bg-accent/90">
                    Update Password
                  </Button>
                </form>

                <Separator className="my-10" />

                <h2 className="mb-4 text-xl font-bold">Two-Factor Authentication</h2>
                <p className="mb-4 text-sm text-muted-foreground">
                  Add an extra layer of security to your account.
                </p>
                <Button variant="outline">Enable 2FA</Button>
              </motion.div>
            </TabsContent>
          </Tabs>
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Profile;
