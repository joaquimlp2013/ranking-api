#!/bin/bash
# Caminho absoluto do diretório do script
DIR="$(cd "$(dirname "$0")" && pwd)"

# Carrega variáveis do .env
set -a
[ -f "$DIR/../.env" ] && . "$DIR/../.env"
set +a

CREATE_BASE="$DIR/../app/Database/Migrations/create_base.sql"
CREATE_TABLE="$DIR/../app/Database/Migrations/create_tables.sql"

if [ -z "$DB_PASSWORD" ]; then
    mysql -u"$DB_USERNAME" -h"$DB_HOST" -P"$DB_PORT" < "$CREATE_BASE"
    mysql -u"$DB_USERNAME" -h"$DB_HOST" -P"$DB_PORT" "$DB_DATABASE" < "$CREATE_TABLE"
else
    mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -h"$DB_HOST" -P"$DB_PORT" < "$CREATE_BASE"
    mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -h"$DB_HOST" -P"$DB_PORT" "$DB_DATABASE" < "$CREATE_TABLE"
fi

php "$DIR/run_seeders.php"
