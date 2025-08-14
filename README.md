

# Multi-Tenant Laravel API (DB-per-Vendor)
This project is a Laravel API designed to support a multi-tenant SaaS application using a database-per-tenant isolation strategy. This approach ensures the highest level of data separation, providing strong security and simplifying compliance for each vendor (tenant).

### 💡 Project Goals & Design Choices
The primary goal of this project is to create a secure, scalable, and maintainable backend that can serve multiple independent tenants.


Strong Data Isolation: We chose the database-per-tenant model to prevent data leakage between vendors. This is the most robust form of isolation, as it physically separates each tenant's data into its own database.


Dynamic Database Switching: The backend uses middleware to dynamically switch the database connection based on the incoming request. This ensures that every request, after authentication, operates within the correct tenant's database context.


Automated Tenant Management: We’ve designed a system with custom Artisan commands to automate the onboarding and migration process for new and existing tenants, a critical feature for a managed SaaS offering.

### ⚖️ Trade-offs
This design, while highly secure, comes with some trade-offs:

- Increased Infrastructure Cost: Managing a dedicated database for each tenant is more expensive than using a shared database with a single schema.

- Operational Overhead: While automated, managing a large number of databases (e.g., for migrations, backups, and restores) is more complex than managing a single database.

- No Cross-Tenant Queries: Performing aggregate queries or analytics across all tenants is not possible directly from this API. It requires a separate data warehouse or a dedicated aggregation service.


# 🚀 Getting Started
## Build application locally

### Start by cloning the repository 

```bash
git clone https://github.com/billiegate/multi-tenancy-laravel.git
```

```bash
cd multi-tenancy-laravel
```

```bash
cp .env.example .env
```

```bash
touch ./database/database.sqlite
```

### Install dependencies
```bash
composer install
```

```bash
RUN npm install
```

```bash
RUN npm run build
```

### Set up project
```bash
php artisan key:generate
```

### Run application migration
```bash
php artisan migrate --path=database/migrations/landlord --database=landlord
```

### Refresh application migration (if you need to)
```bash
php artisan migrate:refresh --path=database/migrations/landlord --database=landlord
```

## Build and run yourself
```bash
docker build -t multitenant .
```
```bash
docker run -it -p 8000:8000 multitenant
```

## Run already built version
```bash
docker run -it -p 8000:8000 toppy44/multitenant
```
## Commands
### Onboard a new vendor
```bash
php artisan tenant:onboard Tenant1
```
### Run migration for all vendor
```bash
php artisan tenant:migrate
```
### Run migration for one vender
```bash
php artisan tenant:migrate 1
```
## Api Documentation
https://documenter.getpostman.com/view/5296421/2sB3BGHpZm

## Application URL
https://multitenant-latest.onrender.com

---

## 🧑‍💻 Author
Afolabi Tope

