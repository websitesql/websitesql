/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'selector',
    content: {
        relative: true,
        files: [
            "./resources/views/**/*.{php,html}",
        ],
    },
    theme: {
        extend: {
            gridAutoRows: {
                'dashboard': '150px',
            },
            'border-right': {
                'active-module': '8px solid #60a5fa',
            }
        },
        fontFamily: {
            'baloo': ['Baloo2'],
        }
    },
    plugins: [],
}

