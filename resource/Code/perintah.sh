# pembuatan laravel project
composer create-project laravel/laravel backend

# buat project vue
pnpm create vue@latest

# compose up
podman compose up -d 2>&1 | tee documentation/document/compose-up.txt

# check running containers
podman ps 2>&1 | tee documentation/document/podman-ps.txt

# migrate and seed database
podman exec -it uts-kepl-backend php artisan migrate --seed 2>&1 | tee documentation/document/migrate.txt

# check container logs
podman logs uts-kepl-backend 2>&1 | tee documentation/document/container-logs.txt
