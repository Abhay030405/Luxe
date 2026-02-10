import { motion } from "framer-motion";
import { Truck, ShieldCheck, RotateCcw, Headphones } from "lucide-react";

const features = [
  {
    icon: Truck,
    title: "Free Delivery",
    description: "Free shipping on all orders over $50. Fast and reliable worldwide delivery.",
  },
  {
    icon: ShieldCheck,
    title: "Secure Payments",
    description: "Your payment information is processed securely with 256-bit encryption.",
  },
  {
    icon: RotateCcw,
    title: "Easy Returns",
    description: "30-day hassle-free return policy. No questions asked.",
  },
  {
    icon: Headphones,
    title: "24/7 Support",
    description: "Round-the-clock customer support to help you with any questions.",
  },
];

export const WhyChooseUs = () => {
  return (
    <section className="section-padding">
      <div className="container-custom">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="mb-16 text-center"
        >
          <h2 className="mb-4 text-3xl font-bold sm:text-4xl">Why Choose Us</h2>
          <p className="mx-auto max-w-2xl text-muted-foreground">
            We're committed to providing the best shopping experience with premium products
            and exceptional service.
          </p>
        </motion.div>

        {/* Features Grid */}
        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {features.map((feature, index) => (
            <motion.div
              key={feature.title}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className="group text-center"
            >
              {/* Icon */}
              <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-accent/10 transition-all group-hover:bg-accent group-hover:shadow-glow">
                <feature.icon className="h-7 w-7 text-accent transition-colors group-hover:text-accent-foreground" />
              </div>

              {/* Title */}
              <h3 className="mb-3 text-lg font-semibold">{feature.title}</h3>

              {/* Description */}
              <p className="text-sm text-muted-foreground">{feature.description}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};
