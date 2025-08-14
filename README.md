run application migration
php artisan migrate --path=database/migrations/landlord --database=landlord  

refresh application migration
php artisan migrate:refresh --path=database/migrations/landlord --database=landlord

onboard a new vendor
php artisan tenant:onboard Tenant1

run migration for all vendor
php artisan tenant:migrate

run migration for one vender
php artisan tenant:migrate 1

