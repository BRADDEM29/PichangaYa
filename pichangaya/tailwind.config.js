module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/View/ComponentViews.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};