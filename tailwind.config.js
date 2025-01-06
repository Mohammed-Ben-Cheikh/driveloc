/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./*.{php,html,js}","./public/**/*.{html,js,php}","./Dashboard/page/**/*.{html,js,php}","./authentification/**/*.{html,js,php}","./app/**/*.{html,js,php}"],
    theme: {
        extend: {
            colors: {
                dark: '#0f172a',
                'dark-light': '#1e293b'
            }
        },
    },
    plugins: [],
}
