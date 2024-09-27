# Client Portal - Marketing Agency Management

## Overview
The **Client Portal** is a Laravel-based application that allows marketing agencies to efficiently manage their clients and employees. This platform enables agencies to track projects, manage project phases, and assign tasks to employees. Clients can view the progress of their projects, receive updates, and provide resources as needed.

The app is designed to streamline communication and task management between the agency, its employees, and the clients.

## Features

- **Employee Management**: Agencies can add and manage their employees.
- **Client Management**: Agencies can onboard and manage clients.
- **Project and Task Management**:
  - Create and manage projects.
  - Define phases for each project.
  - Create tasks under each phase and assign them to employees.
  - Employees can track the tasks assigned to them and provide updates.
  - Questionaire can be created to gather details about the client.
- **Client Dashboard**:
  - Clients have their own dashboard to track project progress and receive updates.
  - Clients can provide resources or complete tasks as requested by the agency.
- **Agency-Client Communication**:
  - Agencies can update clients with project progress.
  - Clients can upload resources and communicate directly with the agency through the portal.

## Requirements

- PHP 8.0+
- Laravel 9.x
- MySQL or another supported database
- Composer
- Node.js & NPM

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/brentscholl/client-portal.git
   cd client-portal
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up the environment file:
   ```bash
   cp .env.example .env
   ```
   Update your `.env` file with the necessary database and app configurations.

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Seed the database (optional):
   ```bash
   php artisan db:seed
   ```

7. Link storage:
   ```bash
   php artisan storage:link
   ```

8. Compile frontend assets:
   ```bash
   npm run dev
   ```

## Usage

- **Admin Panel**: The marketing agency can log in to manage employees, clients, projects, and tasks.
- **Client Portal**: Clients can log in to view the progress of their projects, upload resources, and communicate with the agency.

### Command-Line Tools

- **Create Admin User**:
   ```bash
   php artisan stealth:create-admin
   ```

- **Reset Database**:
   ```bash
   php artisan migrate:reset
   ```

- **Migrate and Seed Database**:
   ```bash
   php artisan migrate --seed
   ```

## Testing

- **Testing Setup**: Testing configurations are available in `phpunit.xml` and `.env.testing`.
- **Migrate Testing Database**:
   ```bash
   php artisan migrate --env=testing
   ```
- **Run All Tests**:
   ```bash
   php artisan test
   ```
- **Run Specific Test**:
   ```bash
   php artisan test --filter ExampleTest
   ```

## Algolia Search (Optional)

- **Import Data to Algolia**:
   ```bash
   php artisan scout:import
   ```

- **Flush Data in Algolia**:
   ```bash
   php artisan scout:flush
   ```

- **Zero Downtime Reimport**:
   ```bash
   php artisan scout:reimport
   ```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
