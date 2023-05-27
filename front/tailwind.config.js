module.exports = {
  mode: 'jit',
  purge: [],
  darkMode: false, // or 'media' or 'class'

  variants: {
    extend: {},
  },
  plugins: [],
  content: ["./src/**/*.{html,js}"],
  theme: {
    extend: {
      backgroundImage: {
        'login_background': "url('./src/assets/johann-walter-bantz-Clv9DfJLwac-unsplash.jpg')",
      }
    },
  },
}
