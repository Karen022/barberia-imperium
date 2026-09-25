/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
          darkGray: "#1f1f1f",
          midGray: "#2d2d2d",
          gold: '#d4af37',
          dark: '#0d0d0d',
          dark2: '#1a1a1a',
          dark3: '#2a2a2a',
      }
    },
  },
  plugins: [],
}

