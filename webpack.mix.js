const mix = require('laravel-mix');

require('laravel-mix-eslint');

mix.ts('resources/js/app.ts', 'public/js')
  .vue({ version: 3 })
  .eslint({
    fix:        true,
    extensions: ['ts', 'js', 'vue'],
  })
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ])
  .sourceMaps()
  .extract(['vue'])
  .version();
