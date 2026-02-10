import { useState } from "react";
import { useParams, Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Star, ShoppingCart, Heart, Truck, Shield, RotateCcw, Minus, Plus, ChevronRight } from "lucide-react";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { useCart } from "@/context/CartContext";
import { products } from "@/data/products";
import { ProductCard } from "@/components/products/ProductCard";

const mockReviews = [
  { id: "1", user: "Sarah M.", rating: 5, date: "2 weeks ago", comment: "Absolutely love this product! The quality exceeds expectations." },
  { id: "2", user: "James K.", rating: 4, date: "1 month ago", comment: "Great value for money. Shipping was fast and packaging was excellent." },
  { id: "3", user: "Emily R.", rating: 5, date: "2 months ago", comment: "Beautiful design and perfect functionality. Highly recommended!" },
];

const ProductDetail = () => {
  const { id } = useParams();
  const { addToCart } = useCart();
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const [isLiked, setIsLiked] = useState(false);

  const product = products.find((p) => p.id === id);

  if (!product) {
    return (
      <div className="min-h-screen bg-background">
        <Navbar />
        <main className="flex min-h-[60vh] items-center justify-center pt-20">
          <div className="text-center">
            <h1 className="mb-4 text-2xl font-bold">Product not found</h1>
            <Link to="/products">
              <Button className="bg-accent text-accent-foreground hover:bg-accent/90">
                Back to Shop
              </Button>
            </Link>
          </div>
        </main>
        <Footer />
      </div>
    );
  }

  // Simulate multiple images with the same image
  const images = [product.image, product.image, product.image, product.image];
  const relatedProducts = products.filter((p) => p.category === product.category && p.id !== product.id).slice(0, 4);

  const handleAddToCart = () => {
    for (let i = 0; i < quantity; i++) {
      addToCart(product);
    }
  };

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="pt-20">
        {/* Breadcrumb */}
        <div className="border-b border-border bg-secondary/30 py-4">
          <div className="container-custom flex items-center gap-2 text-sm text-muted-foreground">
            <Link to="/" className="hover:text-foreground">Home</Link>
            <ChevronRight className="h-4 w-4" />
            <Link to="/products" className="hover:text-foreground">Shop</Link>
            <ChevronRight className="h-4 w-4" />
            <span className="text-foreground">{product.name}</span>
          </div>
        </div>

        <div className="container-custom py-12">
          <div className="grid gap-12 lg:grid-cols-2">
            {/* Image Gallery */}
            <motion.div initial={{ opacity: 0, x: -20 }} animate={{ opacity: 1, x: 0 }}>
              <div className="mb-4 aspect-square overflow-hidden rounded-2xl bg-secondary">
                <img
                  src={images[selectedImage]}
                  alt={product.name}
                  className="h-full w-full object-cover"
                />
              </div>
              <div className="grid grid-cols-4 gap-3">
                {images.map((img, i) => (
                  <button
                    key={i}
                    onClick={() => setSelectedImage(i)}
                    className={`aspect-square overflow-hidden rounded-xl border-2 transition-all ${
                      selectedImage === i ? "border-accent" : "border-transparent opacity-60 hover:opacity-100"
                    }`}
                  >
                    <img src={img} alt="" className="h-full w-full object-cover" />
                  </button>
                ))}
              </div>
            </motion.div>

            {/* Product Info */}
            <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} className="space-y-6">
              {product.badge && (
                <span className="inline-block rounded-full bg-accent/10 px-4 py-1 text-sm font-semibold text-accent">
                  {product.badge}
                </span>
              )}

              <div>
                <p className="mb-1 text-sm font-medium uppercase tracking-wider text-muted-foreground">{product.category}</p>
                <h1 className="text-3xl font-bold lg:text-4xl">{product.name}</h1>
              </div>

              {/* Rating */}
              <div className="flex items-center gap-3">
                <div className="flex items-center gap-1">
                  {Array.from({ length: 5 }).map((_, i) => (
                    <Star
                      key={i}
                      className={`h-5 w-5 ${
                        i < Math.floor(product.rating) ? "fill-accent text-accent" : "text-muted-foreground/30"
                      }`}
                    />
                  ))}
                </div>
                <span className="font-medium">{product.rating}</span>
                <span className="text-muted-foreground">({product.reviews} reviews)</span>
              </div>

              {/* Price */}
              <div className="flex items-center gap-3">
                <span className="text-3xl font-bold">${product.price.toFixed(2)}</span>
                {product.originalPrice && (
                  <>
                    <span className="text-xl text-muted-foreground line-through">${product.originalPrice.toFixed(2)}</span>
                    <span className="rounded-lg bg-accent/10 px-3 py-1 text-sm font-bold text-accent">
                      {Math.round(((product.originalPrice - product.price) / product.originalPrice) * 100)}% OFF
                    </span>
                  </>
                )}
              </div>

              <Separator />

              <p className="leading-relaxed text-muted-foreground">
                Experience premium quality with the {product.name}. Crafted with attention to detail, this product delivers exceptional performance and style for everyday use.
              </p>

              {/* Quantity & Add to Cart */}
              <div className="flex flex-wrap items-center gap-4">
                <div className="flex items-center gap-3 rounded-xl border border-border p-1">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="flex h-10 w-10 items-center justify-center rounded-lg transition-colors hover:bg-muted"
                  >
                    <Minus className="h-4 w-4" />
                  </button>
                  <span className="w-8 text-center font-semibold">{quantity}</span>
                  <button
                    onClick={() => setQuantity(quantity + 1)}
                    className="flex h-10 w-10 items-center justify-center rounded-lg transition-colors hover:bg-muted"
                  >
                    <Plus className="h-4 w-4" />
                  </button>
                </div>

                <Button
                  onClick={handleAddToCart}
                  className="flex-1 gap-2 bg-accent py-6 text-accent-foreground hover:bg-accent/90"
                  size="lg"
                >
                  <ShoppingCart className="h-5 w-5" />
                  Add to Cart
                </Button>

                <Button
                  variant="outline"
                  size="icon"
                  className="h-12 w-12 rounded-xl"
                  onClick={() => setIsLiked(!isLiked)}
                >
                  <Heart className={`h-5 w-5 ${isLiked ? "fill-accent text-accent" : ""}`} />
                </Button>
              </div>

              {/* Trust badges */}
              <div className="grid grid-cols-3 gap-4 rounded-2xl border border-border p-4">
                <div className="flex flex-col items-center gap-2 text-center">
                  <Truck className="h-5 w-5 text-accent" />
                  <span className="text-xs text-muted-foreground">Free Shipping</span>
                </div>
                <div className="flex flex-col items-center gap-2 text-center">
                  <Shield className="h-5 w-5 text-accent" />
                  <span className="text-xs text-muted-foreground">2 Year Warranty</span>
                </div>
                <div className="flex flex-col items-center gap-2 text-center">
                  <RotateCcw className="h-5 w-5 text-accent" />
                  <span className="text-xs text-muted-foreground">30-Day Returns</span>
                </div>
              </div>
            </motion.div>
          </div>

          {/* Reviews & Description Tabs */}
          <div className="mt-16">
            <Tabs defaultValue="description">
              <TabsList>
                <TabsTrigger value="description">Description</TabsTrigger>
                <TabsTrigger value="reviews">Reviews ({product.reviews})</TabsTrigger>
              </TabsList>
              <TabsContent value="description" className="mt-6 max-w-3xl leading-relaxed text-muted-foreground">
                <p className="mb-4">
                  The {product.name} represents the pinnacle of modern design and engineering. Every detail has been meticulously crafted to provide an unparalleled user experience.
                </p>
                <ul className="list-inside list-disc space-y-2">
                  <li>Premium materials for lasting durability</li>
                  <li>Ergonomic design for maximum comfort</li>
                  <li>Advanced technology for superior performance</li>
                  <li>Eco-friendly packaging and sustainable manufacturing</li>
                </ul>
              </TabsContent>
              <TabsContent value="reviews" className="mt-6 space-y-6">
                {mockReviews.map((review) => (
                  <div key={review.id} className="rounded-2xl border border-border bg-card p-6">
                    <div className="mb-3 flex items-center justify-between">
                      <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-accent/10 text-sm font-bold text-accent">
                          {review.user.charAt(0)}
                        </div>
                        <div>
                          <p className="font-semibold">{review.user}</p>
                          <p className="text-xs text-muted-foreground">{review.date}</p>
                        </div>
                      </div>
                      <div className="flex gap-1">
                        {Array.from({ length: 5 }).map((_, i) => (
                          <Star key={i} className={`h-4 w-4 ${i < review.rating ? "fill-accent text-accent" : "text-muted-foreground/30"}`} />
                        ))}
                      </div>
                    </div>
                    <p className="text-muted-foreground">{review.comment}</p>
                  </div>
                ))}
              </TabsContent>
            </Tabs>
          </div>

          {/* Related Products */}
          {relatedProducts.length > 0 && (
            <div className="mt-20">
              <h2 className="mb-8 text-2xl font-bold">You May Also Like</h2>
              <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {relatedProducts.map((p, i) => (
                  <Link key={p.id} to={`/product/${p.id}`}>
                    <ProductCard product={p} index={i} />
                  </Link>
                ))}
              </div>
            </div>
          )}
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default ProductDetail;
