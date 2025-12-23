#!/usr/bin/env bash
set -e

CONFIG_FILE=/var/www/html/config.php
PERSISTED_CONFIG=/var/www/moodledata/config.php

# Restore config.php from persistent storage if missing in the code directory.
if [ ! -f "$CONFIG_FILE" ] && [ -f "$PERSISTED_CONFIG" ]; then
  cp "$PERSISTED_CONFIG" "$CONFIG_FILE"
  chown www-data:www-data "$CONFIG_FILE" || true
fi

# If config.php exists, fix DB params and wwwroot/HTTPS, then persist it.
if [ -f "$CONFIG_FILE" ]; then
  DB_HOST="${MOODLE_DB_HOST:-db}"
  DB_NAME="${MOODLE_DB_NAME:-moodle}"
  DB_USER="${MOODLE_DB_USER:-moodle}"
  DB_PASS="${MOODLE_DB_PASSWORD:-${MYSQL_PASSWORD:-}}"
  DB_TYPE="${MOODLE_DB_TYPE:-mariadb}"

  # Ensure dbtype matches the configured DB (default: mariadb).
  sed -i "s#\$CFG->dbtype[[:space:]]*=[[:space:]]*'.*';#\$CFG->dbtype    = '${DB_TYPE}';#" "$CONFIG_FILE" || true

  # Enforce DB connection settings from env vars (prevents localhost socket issues).
  sed -i "s#\$CFG->dbhost[[:space:]]*=[[:space:]]*'.*';#\$CFG->dbhost    = '${DB_HOST}';#" "$CONFIG_FILE" || true
  sed -i "s#\$CFG->dbname[[:space:]]*=[[:space:]]*'.*';#\$CFG->dbname    = '${DB_NAME}';#" "$CONFIG_FILE" || true
  sed -i "s#\$CFG->dbuser[[:space:]]*=[[:space:]]*'.*';#\$CFG->dbuser    = '${DB_USER}';#" "$CONFIG_FILE" || true
  if [ -n "$DB_PASS" ]; then
    sed -i "s#\$CFG->dbpass[[:space:]]*=[[:space:]]*'.*';#\$CFG->dbpass    = '${DB_PASS}';#" "$CONFIG_FILE" || true
  fi

  # Determine target URL for wwwroot
  TARGET_URL=""
  if [ -n "$SERVICE_URL_MOODLE" ]; then
    TARGET_URL="$SERVICE_URL_MOODLE"
  elif [ -n "$COOLIFY_URL" ]; then
    TARGET_URL="$COOLIFY_URL"
  elif [ -n "$MOODLE_WWWROOT" ]; then
    TARGET_URL="$MOODLE_WWWROOT"
  elif [ -n "$SERVICE_FQDN_MOODLE" ]; then
    TARGET_URL="https://${SERVICE_FQDN_MOODLE}"
  fi

  if [ -n "$TARGET_URL" ]; then
    # Remove trailing slash for consistency
    TARGET_URL="${TARGET_URL%/}"
    # Replace existing wwwroot line
    sed -i "s#\$CFG->wwwroot[[:space:]]*=[[:space:]]*'.*';#\$CFG->wwwroot   = '${TARGET_URL}';#" "$CONFIG_FILE" || true
  fi

  # Ensure sslproxy flag (reverseproxy left to manual config)
  if ! grep -q '\$CFG->sslproxy' "$CONFIG_FILE"; then
    printf '\n$CFG->sslproxy = true;\n' >> "$CONFIG_FILE"
  fi

  REVERSEPROXY="${MOODLE_REVERSEPROXY:-false}"
  if [ "$REVERSEPROXY" = "1" ]; then
    REVERSEPROXY="true"
  fi
  if [ "$REVERSEPROXY" != "true" ]; then
    REVERSEPROXY="false"
  fi

  if grep -q '\$CFG->reverseproxy' "$CONFIG_FILE"; then
    sed -i "s#\$CFG->reverseproxy[[:space:]]*=[[:space:]]*.*;#\$CFG->reverseproxy = ${REVERSEPROXY};#" "$CONFIG_FILE" || true
  else
    printf "\n\$CFG->reverseproxy = ${REVERSEPROXY};\n" >> "$CONFIG_FILE"
  fi

  # Persist config.php so it survives redeploys.
  cp "$CONFIG_FILE" "$PERSISTED_CONFIG" || true
  chown www-data:www-data "$PERSISTED_CONFIG" || true
fi

# Run the main container command (Apache)
exec "$@"
