# Deploying to Laravel Forge

This guide covers how to deploy the **Web Uptime Monitor** application to Laravel Forge.

## Prerequisites

- A [Laravel Forge](https://forge.laravel.com) account.
- A provisioned server on Forge (Ubuntu, Nginx, PHP 8.2+, MySQL, Redis).
- Connection to the Git repository.

## 1. Create Site

1.  Navigate to your server in Forge.
2.  **New Site**:
    *   **Root Domain**: e.g., `monitor.yourdomain.com`
    *   **Project Type**: `Laravel`
    *   **PHP Version**: `PHP 8.2` or higher (Application requires `^8.2`)
3.  **Install Repository**:
    *   Provider: GitLab (or your provider)
    *   Repository: `r.arijit23/web-uptime-monitor`
    *   Branch: `main`
    *   Click **Install Repository**.

## 2. Environment Configuration (.env)

Forge creates a default `.env` file. You need to update it to match the application requirements.

1.  Go to the **Environment** tab.
2.  Update/Verify the following variables:

```ini
APP_NAME="Web Uptime Monitor"
APP_ENV=production
APP_KEY=base64:... (Generated automatically)
APP_DEBUG=false
APP_URL=https://monitor.yourdomain.com

# Database (Forge auto-fills these)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=...

# Queue & Cache (Use Redis)
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Redis (Forge auto-fills these)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=...
REDIS_PORT=6379

# Mail (Mailgun recommended for production)
MAIL_MAILER=mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@example.com
MAILGUN_DOMAIN=...
MAILGUN_SECRET=...
```

3.  Click **Save**.

## 3. Update Deployment Script

We need to install PHP dependencies, migrate the database, and build the frontend assets.

1.  Go to the **App** (or Deployments) tab.
2.  Edit the **Deployment Script**:

```bash
cd /home/forge/monitor.yourdomain.com
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

if [ -f artisan ]; then
    $FORGE_PHP artisan migrate --force
fi

# NPM Config & Build
npm ci
npm run build

# Cache Config (Optional but recommended)
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
```

3.  Click **Update Deployment Script**.

## 4. Queue Worker Configuration

The application uses background jobs (e.g., `CheckWebsiteStatus`). You must run a queue worker.

1.  Go to the **Queue** tab on the **Server** dashboard (left sidebar).
2.  **New Worker**:
    *   **Connection**: `redis`
    *   **Queue**: `default`
    *   **PHP Version**: `PHP 8.2`
    *   **Daemon**: `Checked`
3.  Click **Start Worker**.

## 5. Task Scheduler

The application uses the scheduler for periodic checks (e.g., `schedule:work` locally equivalent).

1.  Go to the **Scheduler** tab on the **Server** dashboard (left sidebar).
2.  **New Job**:
    *   **Command**: `php artisan schedule:run`
    *   **User**: `forge`
    *   **Frequency**: `Every Minute`
3.  Click **Schedule Job**.

## 6. Verification

-   **Deploy**: Click **Deploy Now** to build the application.
-   **Check Logs**: If issues arise, check `storage/logs/laravel.log`.
-   **Test**: Add a website to monitor and ensure the status updates after a few minutes (verifies Queue and Scheduler).
