import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import { products } from "@/data/products";
import { ProductCard } from "@/components/products/ProductCard";

export const FeaturedProducts = () => {
  const featuredProducts = products.slice(0, 8);

  return (
    <section className="section-padding bg-secondary/50">
      <div className="container-custom">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="mb-12 flex flex-col items-center justify-between gap-4 sm:flex-row"
        >
          <div>
            <h2 className="text-3xl font-bold sm:text-4xl">Featured Products</h2>
            <p className="mt-2 text-muted-foreground">
              Handpicked favorites just for you
            </p>
          </div>
          <Link
            to="/products"
            className="group flex items-center gap-2 text-sm font-semibold text-accent transition-colors hover:text-accent/80"
          >
            View All Products
            <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
          </Link>
        </motion.div>

        {/* Product Grid */}
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {featuredProducts.map((product, index) => (
            <ProductCard key={product.id} product={product} index={index} />
          ))}
        </div>
      </div>
    </section>
  );
};
