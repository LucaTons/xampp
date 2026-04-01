#!/usr/bin/env bash

# elimina le immagini superflue e recupera spazio
docker system prune -a -f

# qui dovrebbe comparire che il file tc.log è corrotto
# docker-compose logs db | grep tc.log

# ferma i container
docker-compose down

# elimina il file tc.log
docker run --rm -v $(pwd)/mariadb_data:/data alpine rm /data/tc.log

# riavvia i container
./setup-mariadb.sh
