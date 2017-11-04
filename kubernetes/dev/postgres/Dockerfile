FROM postgres:9.5

RUN echo "sed -i 's/max_connections = 100/max_connections = 300/g' /var/lib/postgresql/data/postgresql.conf" \
     > /docker-entrypoint-initdb.d/max_con.sh
