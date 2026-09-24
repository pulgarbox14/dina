/** @type {import('tailwindcss').Config} */
module.exports = {
    // `overline` est une classe Tailwind : sans ce blocage, la classe maison `eyebrow` tracerait une ligne au-dessus du texte.
    blocklist: ["overline"],
    content: ["./app/views/**/*.php", "./app/helpers.php", "./public/assets/js/app.js"],
    theme: {
        extend: {},
    },
    plugins: [],
};
