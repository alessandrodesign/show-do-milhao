import type {Config} from "tailwindcss";

export default {
    darkMode: "class",
    content: ["./resources/**/*.{js,ts,jsx,tsx,vue,blade.php,html}"],
    prefix: "",
    theme: {
        container: {
            center: true,
            padding: "2rem",
            screens: {
                "2xl": "1400px",
            },
        },
        extend: {
            colors: {
                border: "hsl(var(--border))",
                input: "hsl(var(--input))",
                ring: "hsl(var(--ring))",
                background: "hsl(var(--background))",
                foreground: "hsl(var(--foreground))",
                primary: {
                    DEFAULT: "hsl(var(--primary))",
                    foreground: "hsl(var(--primary-foreground))",
                },
                secondary: {
                    DEFAULT: "hsl(var(--secondary))",
                    foreground: "hsl(var(--secondary-foreground))",
                },
                destructive: {
                    DEFAULT: "hsl(var(--destructive))",
                    foreground: "hsl(var(--destructive-foreground))",
                },
                success: {
                    DEFAULT: "hsl(var(--success))",
                    foreground: "hsl(var(--success-foreground))",
                },
                muted: {
                    DEFAULT: "hsl(var(--muted))",
                    foreground: "hsl(var(--muted-foreground))",
                },
                accent: {
                    DEFAULT: "hsl(var(--accent))",
                    foreground: "hsl(var(--accent-foreground))",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover))",
                    foreground: "hsl(var(--popover-foreground))",
                },
                card: {
                    DEFAULT: "hsl(var(--card))",
                    foreground: "hsl(var(--card-foreground))",
                },
                gold: {
                    DEFAULT: "hsl(var(--gold))",
                    light: "hsl(var(--gold-light))",
                    dark: "hsl(var(--gold-dark))",
                },
                blue: {
                    deep: "hsl(var(--blue-deep))",
                    light: "hsl(var(--blue-light))",
                },
            },
            backgroundImage: {
                'gradient-gold': 'var(--gradient-gold)',
                'gradient-stage': 'var(--gradient-stage)',
                'gradient-prize': 'var(--gradient-prize)',
            },
            boxShadow: {
                'gold': 'var(--shadow-gold)',
                'glow': 'var(--shadow-glow)',
            },
            borderRadius: {
                lg: "var(--radius)",
                md: "calc(var(--radius) - 2px)",
                sm: "calc(var(--radius) - 4px)",
            },
            keyframes: {
                "accordion-down": {
                    from: {height: "0"},
                    to: {height: "var(--radix-accordion-content-height)"},
                },
                "accordion-up": {
                    from: {height: "var(--radix-accordion-content-height)"},
                    to: {height: "0"},
                },
                "pulse-gold": {
                    "0%, 100%": {opacity: "1", transform: "scale(1)"},
                    "50%": {opacity: "0.8", transform: "scale(1.05)"},
                },
                "shine": {
                    "0%": {backgroundPosition: "-200% center"},
                    "100%": {backgroundPosition: "200% center"},
                },
                "float": {
                    "0%, 100%": {transform: "translateY(0)"},
                    "50%": {transform: "translateY(-10px)"},
                },
                "glow": {
                    "0%, 100%": {opacity: "1"},
                    "50%": {opacity: "0.6"},
                },
            },
            animation: {
                "accordion-down": "accordion-down 0.2s ease-out",
                "accordion-up": "accordion-up 0.2s ease-out",
                "pulse-gold": "pulse-gold 2s ease-in-out infinite",
                "shine": "shine 3s linear infinite",
                "float": "float 3s ease-in-out infinite",
                "glow": "glow 2s ease-in-out infinite",
            },
        },
    },
    plugins: [require("tailwindcss-animate")],
} satisfies Config;
