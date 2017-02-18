FROM mysql:5.6

ARG MYSQL_ROOT_PASSWORD
COPY db.sql /docker-entrypoint-initdb.d/db.sql
RUN rm /docker-entrypoint-initdb.d/db.sql
