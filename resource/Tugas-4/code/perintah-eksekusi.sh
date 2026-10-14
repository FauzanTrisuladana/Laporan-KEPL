# Build 1: 
{ time podman build --no-cache -t kepl-be . ; } 2>&1 | tee Tugas4/build-1.txt

# Build 2: 
{ time podman build -t kepl-be . ; } 2>&1 | tee Tugas4/build-2.txt

# Build 3 
{ time podman build -t kepl-be . ; } 2>&1 | tee Tugas4/build-3.txt

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
