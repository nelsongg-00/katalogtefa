import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Seluruh teks situs memakai Open Sauce Sans (Fontsource CDN, OFL-1.1).
                // Pengecualian: judul hero di home.blade.php tetap Open Sauce One.
                sans: ['"Open Sauce Sans"', ...defaultTheme.fontFamily.sans],
                // Design tokens from docs/design/homepage.html
                body: ['"Open Sauce Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Open Sauce Sans"', 'Impact', 'Arial Narrow', 'sans-serif'],
            },
            colors: {
                ink: {
                    DEFAULT: '#111111',
                    muted: '#444444',
                },
                surface: '#fafafa',
                line: '#c9c9c9',
                brand: {
                    DEFAULT: '#0a4aa6',
                    dark: '#00357f',
                    blue: '#1414c8',
                    navy: '#0b1a6b',
                    yellow: '#f2b630',
                    orange: '#f26a1b',
                    green: '#1fb15a',
                    purple: '#8a4de0',
                },
                card: {
                    blue: '#5db8f5',
                    orange: '#ff7a45',
                    red: '#f84545',
                },
            },
            borderRadius: {
                chip: '8px',
                card: '20px',
                pill: '999px',
            },
            boxShadow: {
                header: '0 2px 8px rgba(0, 0, 0, 0.08)',
                arrow: '0 2px 6px rgba(0, 0, 0, 0.15)',
            },
            maxWidth: {
                shell: '1120px',
            },
        },
    },

    plugins: [forms],
};
