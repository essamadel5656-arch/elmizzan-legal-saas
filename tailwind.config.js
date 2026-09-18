/** @type {import('tailwindcss').Config} */
export default {
  // Uses your exact data-theme attribute instead of the default 'class' based dark mode
  darkMode: ['class', '[data-theme="dark"]'],
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
    './app/Livewire/**/*.php', // If using Livewire v3
    './app/Http/Livewire/**/*.php', // If using Livewire v2
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
