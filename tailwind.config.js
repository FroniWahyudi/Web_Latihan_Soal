/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php', // Memindai semua file Blade
    './resources/js/**/*.js',          // Memindai file JavaScript (opsional)
  ],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter', 'sans-serif'], // Tambahkan font Inter
      },
    },
  },
  plugins: [],
}