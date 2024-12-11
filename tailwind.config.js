/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'selector',
    content: {
        relative: true,
        files: [
            "./resources/**/*.{php,html,js}",
            "./src/**/*.{php,html}",
        ],
    },
    theme: {
        extend: {
            gridAutoRows: {
                'dashboard': '150px',
            },
            animation: {
                'spin-fast': 'spin 0.5s linear infinite',
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

