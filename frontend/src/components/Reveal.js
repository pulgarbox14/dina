import { motion } from "framer-motion";

const ease = [0.16, 1, 0.3, 1];

export const Reveal = ({ children, delay = 0, y = 32, className = "", ...rest }) => (
  <motion.div
    className={className}
    initial={{ opacity: 0, y }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true, margin: "-80px" }}
    transition={{ duration: 0.9, ease, delay }}
    {...rest}
  >
    {children}
  </motion.div>
);

export const LineReveal = ({ lines, className = "", delay = 0, stagger = 0.12 }) => (
  <span className={className}>
    {lines.map((line, i) => (
      <span key={i} className="block overflow-hidden pb-[0.08em]">
        <motion.span
          className="block"
          initial={{ y: "110%" }}
          animate={{ y: 0 }}
          transition={{ duration: 1.1, ease, delay: delay + i * stagger }}
        >
          {line}
        </motion.span>
      </span>
    ))}
  </span>
);
