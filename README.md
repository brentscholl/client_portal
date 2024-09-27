# Getting started
- First run migrations: run `php artisan migrate`
- Run `php artisan storage:link`
- You can create the first admin user by running `php artisan stealth:create-admin` or seed the database
  - Admin Username: `admin@stealthmedia.com`
  - Admin Password: `St34lth!`

# Database
- Migrate database: run `php artisan migrate`
- Reset Database: run `php artisan migrate:reset`
- Migrate database with seeded data: run `php artisan migrate --seed` you can adjust which seeders run at `../database/seeders/DatabaseSeeder.php`

# Testing
- Testing database config is found in `config\databese.php -> mysql_testing`
- Testing settings can be found in `phpunit.xml`
- Testing .env file is `.env.testing`
- Migrate testing database `php artisan migrate --env=testing`
- To run all test run `php artisan test`
- To run specific tests run `php artisan test --filter ExampleTest`

# Algolia Search
- Import Data to Algolia: run `php artisan scout:import`
- Flush Data in Algolia: run `php artisan scout:flush`
- Generate Index for Optimization: run `php artisan scout:optimize` then run `php artisan scout:sync`
- Zero Downtime Reimport: run `php artisan scout:reimport`
