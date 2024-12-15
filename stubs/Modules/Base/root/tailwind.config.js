import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography';

export default {
    content: [
        'app/**/*.php',
        'resources/views/**/*.blade.php',
        './resources/**/*.jsx',
        './resources/**/*.js',
    ],

    theme: {
        extend: {},
    },

    variants: {
        extend: {},
    },

    plugins: [
        forms,
        typography
    ],
};
