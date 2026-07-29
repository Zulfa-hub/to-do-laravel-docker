/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                serif: ['"Playfair Display"', 'serif'],
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            },
            colors: {
                astra: {
                    50: '#faf7ff',
                    100: '#f3ecff',
                    200: '#e5d6ff',
                    300: '#d1b3ff',
                    400: '#b585ff',
                    500: '#9b5cf6',
                    600: '#8138e8',
                    700: '#6c28c9',
                    800: '#5a22a3',
                    900: '#4a1d84',
                },
                blush: {
                    100: '#ffe9ec',
                    200: '#ffd3d9',
                    300: '#ffb3bd',
                    400: '#ff8fa0',
                },
            },
            backgroundImage: {
                'astra-gradient': 'linear-gradient(135deg, #f3ecff 0%, #ffe9ec 50%, #ffe7d6 100%)',
                'astra-button': 'linear-gradient(135deg, #9b5cf6 0%, #ff8fa0 100%)',
            },
            boxShadow: {
                soft: '0 10px 40px -12px rgba(129, 56, 232, 0.25)',
            },
        },
    },
    plugins: [],
};
