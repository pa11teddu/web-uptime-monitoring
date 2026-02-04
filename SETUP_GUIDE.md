# Setup Guide (Fresh Machine)

This guide assumes you have a completely fresh machine with no PHP, Node.js, or Composer installed. We will use **Docker** to handle all dependencies.

## Prerequisites

1.  **Install Docker Desktop**:
    - Download and install Docker Desktop for your OS (Mac/Windows/Linux).
    - Ensure Docker is running.

## Installation Steps

### 1. Install PHP Dependencies (via Docker)
Since you don't have PHP/Composer installed locally, we use a temporary Docker container to install the project dependencies.

Run this command in your terminal at the project root:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

*This command may take a few minutes as it downloads the necessary images and dependencies.*

### 2. Configure Environment
Create the environment file from the example:

```bash
cp .env.example .env
```

### 3. Start the Application (Sail)
Now that dependencies are installed (including Laravel Sail), you can start the application containers.

```bash
./vendor/bin/sail up -d
```

*Note: The first start will take some time to build the Docker images.*

### 4. Application Setup
Run the following commands using Sail to generate keys and setup the database:

```bash
# Generate Application Key
./vendor/bin/sail artisan key:generate

# Run Database Migrations and Seed Data
./vendor/bin/sail artisan migrate --seed
```

### 5. Install Frontend Dependencies
Install and build the frontend assets (Node/Vue):

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### 6. Start Queue Worker
The application requires a queue worker for monitoring websites:

```bash
./vendor/bin/sail artisan queue:work
```

## Accessing the App

- **Web Interface**: [http://localhost](http://localhost)
- **Mailpit (Emails)**: [http://localhost:8025](http://localhost:8025) (Assuming default port)

## Troubleshooting

- **Alias**: To make commands shorter, you can configure a shell alias:
    ```bash
    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
    ```
    Then you can run `sail up`, `sail artisan ...` instead of `./vendor/bin/sail ...`.
