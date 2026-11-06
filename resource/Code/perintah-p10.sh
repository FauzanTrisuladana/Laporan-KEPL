# 1. Pembangunan ulang dan pengaktifan service dengan konfigurasi logging stdout & healthcheck
podman compose up -d --build 2>&1 | tee documentation/document/compose-up.txt

# 2. Pemeriksaan status seluruh container (kolom STATUS berstatus healthy)
podman ps 2>&1 | tee documentation/document/podman-ps.txt

# 3. Pengujian endpoint /health saat seluruh service (db & cache) normal (HTTP 200 OK)
curl -i http://localhost:8007/health 2>&1 | tee documentation/document/health-200-healthy.txt

# 4. Pengambilan structured logs dari stdout container backend
podman compose logs backend 2>&1 | tee documentation/document/structured-logs-stdout.txt

# 5. Simulasi kegagalan database: mematikan container db secara sengaja
podman compose stop db 2>&1 | tee documentation/document/db-stop.txt

# 6. Pengujian endpoint /health saat database mati (HTTP 503 Service Unavailable)
curl -i http://localhost:8007/health 2>&1 | tee documentation/document/health-503-unhealthy.txt

# 7. Pembuktian status container backend berubah menjadi unhealthy pada podman ps
podman ps 2>&1 | tee documentation/document/db-stopped-unhealthy-ps.txt

# 8. Pembuktian log container backend mencatat penyebab kegagalan koneksi database
podman compose logs backend 2>&1 | tail -n 35 | tee documentation/document/db-stopped-unhealthy-logs.txt

# 9. Pemulihan service database: menyalakan kembali container db
podman compose start db 2>&1 | tee documentation/document/db-start.txt

# 10. Verifikasi endpoint /health kembali berstatus 200 OK setelah db pulih
curl -i http://localhost:8007/health 2>&1 | tee documentation/document/health-recovered-200.txt

# 11. Pembuktian status container backend kembali menjadi healthy pada podman ps
podman ps 2>&1 | tee documentation/document/db-restarted-healthy-ps.txt

# 12. Pemeriksaan pembatasan ukuran log (max-size 10MB dan max-file) pada konfigurasi container
podman inspect uts-kepl-backend --format '{{json .HostConfig.LogConfig}}' 2>&1 | tee documentation/document/logging-config.txt
