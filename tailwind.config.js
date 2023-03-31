/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" // set up the path to the flowbite package

  ],
  theme: {
    extend: {
      fontFamily: {
        climateCrisis: ["Climate Crisis", 'cursive'],
        LibreBaskerville: ["Libre Baskerville","serif"],
        RobotoTo: ['Roboto', "sans-serif"]
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('flowbite/plugin') // add the flowbite plugin

  ],
}