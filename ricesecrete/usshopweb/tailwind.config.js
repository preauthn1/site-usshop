/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        "page-bg-color": "#fff",
        "base-text-color": "#222",
        "base-text-color-gray": "#767676",
        "base-selector-color": "#f6f6f6",
        "detail-title":"#666",
        "borderColor":"#e5e5e5",
      },
      boxShadow: {
        'hotel-shadow': '0 .4rem .8rem 0 rgba(15, 41, 77, .16)',
      },
    },
  },
  plugins: [],
}

