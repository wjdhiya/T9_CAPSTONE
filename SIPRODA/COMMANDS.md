# 🚀 COMMAND REFERENCE - siprodo
## Daftar Command Penting untuk Development & Deployment

---

## 📦 INSTALLATION COMMANDS

### Initial Setup
```bash
# Clone repository
git clone <repository-url>
cd siprodo

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database (SQLite)
touch database/database.sqlite

# Or create MySQL database
mysql -u root -p
CREATE DATABASE siprodo;
exit;
```

---

## 🗄️ DATABASE COMMANDS

### Migrations
```bash
# Run all migrations
php artisan migrate

# Run migrations with seeding
php artisan migrate --seed

# Fresh migration (drop all tables and re-run)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Check migration status
php artisan migrate:status
```

### Seeders
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PenelitianSeeder
```

### Database Management
```bash
# Show database info
php artisan db:show

# Show table info
php artisan db:table users

# Wipe database (SQLite)
php artisan db:wipe
```

---

## 🏗️ ARTISAN COMMANDS

### Make Commands (Generate Files)
```bash
# Make controller
php artisan make:controller PenelitianController
php artisan make:controller PenelitianController --resource

# Make model
php artisan make:model Penelitian
php artisan make:model Penelitian -m  # with migration
php artisan make:model Penelitian -mfs  # with migration, factory, seeder

# Make migration
php artisan make:migration create_penelitian_table
php artisan make:migration add_role_to_users_table --table=users

# Make seeder
php artisan make:seeder UserSeeder

# Make middleware
php artisan make:middleware CheckRole

# Make request
php artisan make:request StorePenelitianRequest

# Make policy
php artisan make:policy PenelitianPolicy

# Make factory
php artisan make:factory PenelitianFactory
```

### Cache Commands
```bash
# Clear all caches
php artisan optimize:clear

# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### Storage Commands
```bash
# Create symbolic link for storage
php artisan storage:link

# Clear storage logs
rm -rf storage/logs/*.log
```

---

## 🎨 FRONTEND COMMANDS

### NPM Commands
```bash
# Install dependencies
npm install

# Development mode (with hot reload)
npm run dev

# Build for production
npm run build

# Watch for changes
npm run watch
```

### Tailwind Commands
```bash
# Build Tailwind CSS
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css

# Watch mode
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch

# Minified build
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify
```

---

## 🧪 TESTING COMMANDS

### PHPUnit
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/PenelitianTest.php

# Run specific test method
php artisan test --filter test_dosen_can_create_penelitian

# Run with coverage
php artisan test --coverage

# Run with coverage HTML report
php artisan test --coverage-html coverage

# Parallel testing
php artisan test --parallel
```

### Laravel Pint (Code Formatting)
```bash
# Format all files
./vendor/bin/pint

# Format specific directory
./vendor/bin/pint app/Models

# Dry run (preview changes)
./vendor/bin/pint --test

# Verbose output
./vendor/bin/pint -v
```

---

## 🚀 SERVER COMMANDS

### Development Server
```bash
# Start development server
php artisan serve

# Start on specific port
php artisan serve --port=8080

# Start on specific host
php artisan serve --host=0.0.0.0 --port=8000
```

### Queue Workers
```bash
# Start queue worker
php artisan queue:work

# Start queue worker with specific connection
php artisan queue:work redis

# Process only one job
php artisan queue:work --once

# Restart queue workers
php artisan queue:restart
```

### Scheduler
```bash
# Run scheduled tasks
php artisan schedule:run

# List scheduled tasks
php artisan schedule:list

# Test scheduled task
php artisan schedule:test
```

---

## 🔍 DEBUGGING COMMANDS

### Tinker (REPL)
```bash
# Start tinker
php artisan tinker

# Example commands in tinker:
>>> User::count()
>>> User::where('role', 'dosen')->get()
>>> Penelitian::with('user')->first()
>>> DB::table('users')->get()
```

### Route Commands
```bash
# List all routes
php artisan route:list

# List routes with specific name
php artisan route:list --name=penelitian

# List routes with specific method
php artisan route:list --method=GET

# List routes in JSON format
php artisan route:list --json
```

### Model Commands
```bash
# Show model info
php artisan model:show User
php artisan model:show Penelitian
```

---

## 📊 MAINTENANCE COMMANDS

### Application
```bash
# Put application in maintenance mode
php artisan down

# Put application in maintenance mode with message
php artisan down --message="Maintenance in progress"

# Bring application back online
php artisan up

# Check application environment
php artisan env

# Show Laravel version
php artisan --version
```

### Logs
```bash
# View logs in real-time
tail -f storage/logs/laravel.log

# Clear logs
> storage/logs/laravel.log

# Or delete log files
rm storage/logs/*.log
```

---

## 🔐 SECURITY COMMANDS

### Generate Keys
```bash
# Generate application key
php artisan key:generate

# Generate JWT secret (if using JWT)
php artisan jwt:secret
```

### Permissions
```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache

# Fix storage permissions (Windows - run as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

---

## 📦 COMPOSER COMMANDS

### Package Management
```bash
# Install packages
composer install

# Update packages
composer update

# Update specific package
composer update laravel/framework

# Require new package
composer require maatwebsite/excel

# Require dev package
composer require --dev phpunit/phpunit

# Remove package
composer remove package-name

# Dump autoload
composer dump-autoload
```

---

## 🐳 DOCKER COMMANDS (Optional)

### Laravel Sail
```bash
# Start containers
./vendor/bin/sail up

# Start in background
./vendor/bin/sail up -d

# Stop containers
./vendor/bin/sail down

# Run artisan commands
./vendor/bin/sail artisan migrate

# Run composer commands
./vendor/bin/sail composer install

# Run npm commands
./vendor/bin/sail npm install

# Access MySQL
./vendor/bin/sail mysql

# Access shell
./vendor/bin/sail shell
```

---

## 🔄 GIT COMMANDS

### Basic Git Workflow
```bash
# Check status
git status

# Add files
git add .
git add app/Models/Penelitian.php

# Commit
git commit -m "feat: add penelitian CRUD"

# Push
git push origin main

# Pull
git pull origin main

# Create branch
git checkout -b feature/penelitian

# Switch branch
git checkout main

# Merge branch
git merge feature/penelitian

# View log
git log --oneline

# View diff
git diff
```

---

## 📝 USEFUL ALIASES

Add to your `.bashrc` or `.zshrc`:

```bash
# Laravel aliases
alias pa="php artisan"
alias pas="php artisan serve"
alias pam="php artisan migrate"
alias pamf="php artisan migrate:fresh --seed"
alias pat="php artisan test"
alias pao="php artisan optimize:clear"

# Composer aliases
alias ci="composer install"
alias cu="composer update"
alias cda="composer dump-autoload"

# NPM aliases
alias ni="npm install"
alias nd="npm run dev"
alias nb="npm run build"

# Git aliases
alias gs="git status"
alias ga="git add ."
alias gc="git commit -m"
alias gp="git push"
alias gl="git pull"
```

---

## 🎯 COMMON WORKFLOWS

### Fresh Start
```bash
# Complete fresh start
php artisan migrate:fresh --seed
php artisan optimize:clear
npm run build
php artisan serve
```

### After Pulling Changes
```bash
# Update dependencies and rebuild
composer install
npm install
php artisan migrate
php artisan optimize:clear
npm run build
```

### Before Committing
```bash
# Format code and run tests
./vendor/bin/pint
php artisan test
git add .
git commit -m "your message"
git push
```

### Deployment
```bash
# Production deployment
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

---

## 🆘 TROUBLESHOOTING COMMANDS

### Common Issues

```bash
# "Class not found" error
composer dump-autoload

# "Permission denied" error
chmod -R 775 storage bootstrap/cache

# "Vite manifest not found" error
npm run build

# "SQLSTATE connection refused" error
# Check .env database configuration
php artisan config:clear

# Clear everything
php artisan optimize:clear
composer dump-autoload
npm run build
```

---

## 📚 DOCUMENTATION COMMANDS

### Generate Documentation
```bash
# Generate API documentation (if using Laravel API Documentation Generator)
php artisan l5-swagger:generate

# Generate IDE helper files
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

---

**Quick Reference Card - Keep this handy during development! 🚀**

