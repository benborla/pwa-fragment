module.exports = {
    parser: "vue-eslint-parser",
    parserOptions: {
        parser: "@typescript-eslint/parser",
        vueFeatures: {
            interpolationAsNonHTML: true,
        },
        ecmaVersion: 2020,
    },
    extends: [
        "plugin:@typescript-eslint/recommended",
        "plugin:vue/vue3-essential",
        "eslint:recommended",
        "@vue/typescript",
    ],
    plugins: ["vue", "@typescript-eslint"],
    rules: {
        strict: 0,
        "class-methods-use-this": ["off"],
        "import/prefer-default-export": 0,
        indent: ["error", 2],
        "vue/html-indent": ["error", 2],
    },
    env: {
        browser: true,
        jest: true,
        node: true
    },
};
