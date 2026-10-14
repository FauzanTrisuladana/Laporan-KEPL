# run
{
  podman run -d --name kepl-larevel \
    --network shared-net \
    -p 8007:80 \
    --env-file .env \
    -e DB_HOST=mysql-db \
    kepl-be

  podman ps -s
} 2>&1 | tee Tugas4/run.txt
