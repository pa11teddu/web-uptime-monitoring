# Initial Setup Instructions (New Installation)

This documentation is designed for systems without pre-installed PHP, Node.js, or Composer. All dependencies will be managed through **Docker containers**.

## Required Software

1.  **Docker Desktop Installation**:
    - Obtain and install Docker Desktop compatible with your operating system (macOS/Windows/Linux).
    - Verify Docker is active and running before proceeding.

## Step-by-Step Installation

### Step 1: Install Backend Dependencies (Using Docker)
If PHP and Composer are not available on your local machine, utilize a temporary Docker container to fetch project dependencies.

Execute this command from the project's root directory:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

*Please allow several minutes for this process as it downloads Docker images and required packages.*

### Step 2: Environment File Configuration
Generate the environment configuration file from the template:

```bash
cp .env.example .env
```

### Step 3: Launch Application Containers (Sail)
With all dependencies installed (including Laravel Sail), initialize the application's Docker containers.

```bash
./vendor/bin/sail up -d
```

*Initial container build may require additional time to complete.*

### Step 4: Application Initialization
Execute these Sail commands to create the application encryption key and prepare the database:

```bash
# Create Application Encryption Key
./vendor/bin/sail artisan key:generate

# Execute Database Migrations and Populate Seed Data
./vendor/bin/sail artisan migrate --seed
```

### Step 5: Frontend Package Installation
Download and compile frontend dependencies (Node.js/Vue.js):

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### Step 6: Initialize Background Job Processor
The system needs a queue worker running to process website status monitoring tasks:

```bash
./vendor/bin/sail artisan queue:work
```

## Application Access Points

- **Main Dashboard**: [http://localhost](http://localhost)
- **Email Testing Interface**: [http://localhost:8025](http://localhost:8025) (Default Mailpit port)

## Common Issues & Solutions

- **Command Shortcuts**: Create a shell alias to simplify Sail commands:
    ```bash
    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
    ```
    After setting this alias, you can use `sail up` and `sail artisan ...` instead of the full path.
