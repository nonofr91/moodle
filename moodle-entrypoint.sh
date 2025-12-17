#!/usr/bin/env bash
set -e

CONFIG_FILE=/var/www/html/config.php
PERSISTED_CONFIG=/var/www/moodledata/config.php

# Restore config.php from persistent storage if missing in the code directory.
if [ ! -f "$CONFIG_FILE" ] && [ -f "$PERSISTED_CONFIG" ]; then
  cp "$PERSISTED_CONFIG" "$CONFIG_FILE"
  chown www-data:www-data "$CONFIG_FILE" || true
fi

# If config.php exists, fix dbtype and wwwroot/HTTPS, then persist it.
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

  # Ensure sslproxy flag (reverseproxy left to manual config)
  if ! grep -q "\\$CFG->sslproxy" "$CONFIG_FILE"; then
    printf "\n\\$CFG->sslproxy = true;\n" >> "$CONFIG_FILE"
  fi

  # Persist config.php so it survives redeploys.
  cp "$CONFIG_FILE" "$PERSISTED_CONFIG" || true
  chown www-data:www-data "$PERSISTED_CONFIG" || true
fi

# Run the main container command (Apache)
exec "$@"
