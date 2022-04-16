module.exports = {
    plugins: ["@typescript-eslint", "vue"],
    ignorePatterns: ["**/vendor/**/*.js", "ziggy.js"],
    extends: [
        // add more generic rulesets here, such as:
        "eslint:recommended",
        "plugin:@typescript-eslint/recommended",
        "plugin:vue/base",
        "plugin:vue/vue3-essential",
        "plugin:vue/vue3-recommended",
        // "prettier",
    ],
    parser: "@typescript-eslint/parser",
    env: {
        browser: true,
        node: true,
        es6: true,
        "vue/setup-compiler-macros": true,
    },
};
