# 1. criar pasta e iniciar git
mkdir show-do-milhao && cd show-do-milhao
git init

# 2. criar projeto Laravel (ajuste a versão se quiser outra)
composer create-project --prefer-dist laravel/laravel .

# 3. criar .env (copiar .env.example) e gerar app key
cp .env.example .env
php artisan key:generate

# 4. instalar dependências node + vite + react + ts
npm init -y
npm install --save-dev vite @vitejs/plugin-react typescript ts-node
npm install react react-dom axios

# 5. instalar ferramentas de frontend / lint
npm install --save-dev eslint eslint-plugin-react @typescript-eslint/parser @typescript-eslint/eslint-plugin prettier eslint-config-prettier eslint-plugin-prettier

# 6. php tools: phpcs + fixer (dev)
composer require --dev squizlabs/php_codesniffer friendsofphp/php-cs-fixer

# 7. scaffolds iniciais no Laravel (folders + git)
php artisan storage:link
mkdir -p app/Domain app/UseCase app/Infra app/Services
mkdir -p resources/js/components resources/js/pages resources/ts
git add .
git commit -m "chore: bootstrap laravel + vite + react + ts (fase 1)"
