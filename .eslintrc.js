module.exports = {
  env: {
    browser: true,
    es2021: true,
    "vue/setup-compiler-macros": true,
  },
  extends: [
    "plugin:vue/vue3-recommended",
    "plugin:tailwindcss/recommended"
  ],
  parserOptions: {
    ecmaVersion: "latest",
    parser: "@typescript-eslint/parser",
    sourceType: "module",
  },
  plugins: ["@typescript-eslint", "tailwindcss"],
  "ignorePatterns": ["nova-components/**/*"],
};
