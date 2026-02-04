# Web Uptime Monitor

A Laravel + Vue.js application to monitor website uptime and send email alerts.

## Requirements
- Docker (Laravel Sail)

## Setup

1.  Start Docker containers:
    ```bash
    ./vendor/bin/sail up -d
    ```

2.  Install dependencies (if not already):
    ```bash
    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install
    ```

3.  Setup Database:
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```
    *This will populate the database with test clients and websites.*

4.  Build Frontend:
    ```bash
    ./vendor/bin/sail npm run dev
    ```

5.  Run Queue Worker (Essential for monitoring):
    ```bash
    ./vendor/bin/sail artisan queue:work
    ```

6.  Access the application at [http://localhost](http://localhost).

## Features

- **Monitoring**: Checks websites every 15 minutes (via Scheduler).
- **Manual Trigger**: Run `./vendor/bin/sail artisan monitor:websites`.
- **Alerts**: Sends an email when a website is down.
- **Frontend**: View clients and websites, with a confirmation dialog before visiting.
