

# Multi-Tenant Laravel API (DB-per-Vendor)
This project is a Laravel API designed to support a multi-tenant SaaS application using a database-per-tenant isolation strategy. This approach ensures the highest level of data separation, providing strong security and simplifying compliance for each vendor (tenant).

### 💡 Project Goals & Design Choices
The primary goal of this project is to create a secure, scalable, and maintainable backend that can serve multiple independent tenants.


Strong Data Isolation: We chose the database-per-tenant model to prevent data leakage between vendors. This is the most robust form of isolation, as it physically separates each tenant's data into its own database.


Dynamic Database Switching: The backend uses middleware to dynamically switch the database connection based on the incoming request. This ensures that every request, after authentication, operates within the correct tenant's database context.


Centralized Authentication: Authentication is handled by a central user system (using Laravel Sanctum) that maps a user to a specific vendor. This simplifies login and token management.


Automated Tenant Management: We’ve designed a system with custom Artisan commands to automate the onboarding and migration process for new and existing tenants, a critical feature for a managed SaaS offering.

### ⚖️ Trade-offs
This design, while highly secure, comes with some trade-offs:

- Increased Infrastructure Cost: Managing a dedicated database for each tenant is more expensive than using a shared database with a single schema.

- Operational Overhead: While automated, managing a large number of databases (e.g., for migrations, backups, and restores) is more complex than managing a single database.

- No Cross-Tenant Queries: Performing aggregate queries or analytics across all tenants is not possible directly from this API. It requires a separate data warehouse or a dedicated aggregation service.


# 🚀 Getting Started
## Build application locally

### Start by cloning the repository 

``` git clone https://github.com/billiegate/multi-tenancy-laravel.git``` 
``` cd multi-tenancy-laravel``` 
``` cp .env.example .env``` 
``` touch ./database/database.sqlite``` 

### Install dependencies
composer install
RUN npm install
RUN npm run build

### set up project
php artisan key:generate


### run application migration
php artisan migrate --path=database/migrations/landlord --database=landlord  

### refresh application migration if you need to
php artisan migrate:refresh --path=database/migrations/landlord --database=landlord


## build and run yourself
docker build -t multitenant .
docker run -it -p 8000:8000 multitenant

## run already built version
docker run -it -p 8000:8000 toppy44/multitenant

## Commands
### onboard a new vendor
php artisan tenant:onboard Tenant1

### run migration for all vendor
php artisan tenant:migrate

### run migration for one vender
php artisan tenant:migrate 1

## Api Documentation
https://documenter.getpostman.com/view/5296421/2sB3BGHpZm

## Application URL
https://multitenant-latest.onrender.com