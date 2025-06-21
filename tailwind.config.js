/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/views/auth/login.blade.php",
        "./resources/**/*.js",
        "./vendor/filament/**/*.blade.php",
    ],
    safelist: [
        "bg-gradient-to-r",
        "from-yellow-400",
        "to-yellow-600",
        "from-gray-400",
        "to-gray-600",
        "from-orange-400",
        "to-orange-600",
        "w-full",
        "table-auto",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Instrument Sans",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
        },
    },
    plugins: [],
};
