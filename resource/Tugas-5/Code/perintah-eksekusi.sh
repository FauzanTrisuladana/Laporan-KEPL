# 1. Image sebelum
podman images 2>&1 | tee Tugas5/images-sebelum.txt

# 2. Build Multi-stage
podman build -t kepl-be:multistage . 2>&1 | tee Tugas5/build-multistage.txt

# 3. Image sesudah (pembuktian efisiensi ukuran)
podman images 2>&1 | tee Tugas5/images-sesudah.txt

# 4. Menjalankan Container
{
  podman run -d --name kepl-laravel-multistage \
    --network shared-net \
    -p 8007:80 \
    --env-file .env \
    -e DB_HOST=mysql-db \
    kepl-be:multistage
} 2>&1 | tee Tugas5/run.txt

# 5. Pembuktian Non-Root User (whoami)
podman exec kepl-laravel-multistage whoami 2>&1 | tee Tugas5/whoami.txt

# 6. Pembuktian Healthcheck (status healthy)
podman ps 2>&1 | tee Tugas5/healthcheck.txt