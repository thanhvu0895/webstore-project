#!/bin/bash

# Wait for MySQL to start
while ! mysqladmin ping -hmysql_db --silent; do
    sleep 1
done

# Import the SQL file
mysql -hmysql_db -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < /docker-entrypoint-initdb.d/db_webstore.sql
