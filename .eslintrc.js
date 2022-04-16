module.exports = {
    plugins: ["@typescript-eslint", "vue", "prettier"],
    ignorePatterns: [
        "**/vendor/**/*.js",
        "ziggy.js",
        "resources/js/ziggy.generated.js",
    ],
    extends: [
        // add more generic rulesets here, such as:
        "eslint:recommended",
        "plugin:@typescript-eslint/recommended",
        "plugin:vue/base",
        "plugin:vue/vue3-essential",
        "plugin:vue/vue3-recommended",
        "plugin:prettier/recommended",
    ],
    parser: "vue-eslint-parser",
    parserOptions: {
        parser: "@typescript-eslint/parser",
        sourceType: "module",
    },
    env: {
        browser: true,
        node: true,
        es6: true,
        "vue/setup-compiler-macros": true,
    },
};
