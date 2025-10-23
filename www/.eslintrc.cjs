module.exports = {
    parser: '@typescript-eslint/parser',
    plugins: ['@typescript-eslint', 'react', 'prettier'],
    extends: ['eslint:recommended', 'plugin:react/recommended', 'plugin:@typescript-eslint/recommended', 'prettier'],
    settings: {react: {version: 'detect'}},
    env: {browser: true, es2021: true, node: true},
    rules: {'prettier/prettier': 'error'}
};
