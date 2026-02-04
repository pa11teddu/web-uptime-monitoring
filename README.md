# Website Status Monitoring System

A full-stack web application built with Laravel and Vue.js for tracking website availability and sending automated email notifications.

## System Requirements
- Docker Desktop with Laravel Sail support

## Installation & Configuration

1.  Launch Docker environment:
    ```bash
    ./vendor/bin/sail up -d
    ```

2.  Install project dependencies:
    ```bash
    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install
    ```

3.  Initialize database schema and seed data:
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```
    *This command creates the necessary tables and populates them with sample client and website data.*

4.  Compile frontend assets:
    ```bash
    ./vendor/bin/sail npm run dev
    ```

5.  Start background job processor (Required for status checks):
    ```bash
    ./vendor/bin/sail artisan queue:work
    ```

6.  Open your browser and navigate to [http://localhost](http://localhost).

## Core Functionality

- **Automated Status Checks**: Performs website availability verification every 15 minutes using Laravel's task scheduler.
- **Manual Execution**: Trigger status checks immediately with `./vendor/bin/sail artisan monitor:websites`.
- **Email Notifications**: Automatically sends alert emails to registered clients when their monitored sites become unavailable.
- **User Interface**: Interactive dashboard displaying client information and monitored sites, featuring a confirmation prompt before external site navigation.
