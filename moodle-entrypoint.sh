#!/usr/bin/env bash
set -e

CONFIG_FILE=/var/www/html/config.php

# If config.php exists, fix dbtype and wwwroot for reverse proxy/HTTPS
if [ -f "$CONFIG_FILE" ]; then
  # Ensure dbtype is mariadb instead of mysql/mysqli
  sed -i "s/'dbtype'[[:space:]]*=>[[:space:]]*'mysql'/'dbtype'    => 'mariadb'/" "$CONFIG_FILE" || true
  sed -i "s/'dbtype'[[:space:]]*=>[[:space:]]*'mysqli'/'dbtype'    => 'mariadb'/" "$CONFIG_FILE" || true

  # Determine target URL for wwwroot
  TARGET_URL=""
  if [ -n "$COOLIFY_URL" ]; then
    TARGET_URL="$COOLIFY_URL"
  elif [ -n "$MOODLE_WWWROOT" ]; then
    TARGET_URL="$MOODLE_WWWROOT"
  fi

  if [ -n "$TARGET_URL" ]; then
    # Remove trailing slash for consistency
    TARGET_URL="${TARGET_URL%/}"
    # Replace existing wwwroot line
    sed -i "s#\\$CFG->wwwroot[[:space:]]*=[[:space:]]*'.*';#\\$CFG->wwwroot   = '${TARGET_URL}';#" "$CONFIG_FILE" || true
  fi

  # Ensure reverse proxy flags
  if ! grep -q "\\$CFG->reverseproxy" "$CONFIG_FILE"; then
    printf "\n\\$CFG->reverseproxy = true;\n" >> "$CONFIG_FILE"
  fi
  if ! grep -q "\\$CFG->sslproxy" "$CONFIG_FILE"; then
    printf "\n\\$CFG->sslproxy = true;\n" >> "$CONFIG_FILE"
  fi
fi

# Run the main container command (Apache)
exec "$@"
