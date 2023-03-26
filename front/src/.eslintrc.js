module.exports = {
    env: {
        node: true,
    },
    extends: ['eslint:recommended', 'plugin:vue/vue3-recommended', 'prettier', 'eslint-import-resolver-typescript'],
    rules: {
        "import/no-unresolved": "error"
        // override/add rules settings here, such as:
        // 'vue/no-unused-vars': 'error'
    },
    "settings": {
        "import/parsers": {
          "@typescript-eslint/parser": [".ts", ".tsx"]
        },
        "import/resolver": {
          "typescript": {
            "alwaysTryTypes": true, // always try to resolve types under `<root>@types` directory even it doesn't contain any source code, like `@types/unist`
          }
        }
    }
};
