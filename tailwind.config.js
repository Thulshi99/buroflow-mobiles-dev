const colors = require('tailwindcss/colors');
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    safelist: [
        'bg-sky-500/10',
        {
            pattern: /text-(emerald|sky|blue)-(600|700|800|900)/,

        }
    ],
    theme: {
        extend: {
            colors: {
                danger: colors.red,
                primary: colors.indigo,
                success: colors.green,
                warning: colors.yellow,
            },
            fontFamily: {
                'serif': ['"Proxima Nova"', ...defaultTheme.fontFamily.serif],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
