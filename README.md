# Moodle Docker Deployment (Local + Coolify)

## Overview

This repository provides a Docker-based deployment of **Moodle 4.0.x** using:

- A custom `Dockerfile` that builds Moodle from the official Git repository
- A `docker-compose.yml` stack with:
  - **MariaDB 10.11** as the database
  - A custom **PHP 8.1 + Apache** container running Moodle
- Configuration tailored for both **local development** and **Coolify** deployment

The goal is to keep local and production configurations as close as possible, while allowing Coolify to manage HTTPS and reverse proxying.

---

## Components & Versions

- **Moodle**: Branch `MOODLE_404_STABLE` (cloned from https://github.com/moodle/moodle)
- **Web server**: `php:8.1-apache`
- **Database**: `mariadb:10.11` (LTS)
- **Orchestrator**: Docker Compose v3.9

---

## Architecture

Docker Compose defines two services:

- `db`
  - Image: `mariadb:10.11`
  - Stores data in named volume `db_data`
  - Environment variables configure root password, database, user and password

- `moodle`
  - Built from the local `Dockerfile`
  - Uses build arg `MOODLE_BRANCH` (default `MOODLE_404_STABLE`)
  - Connects to `db` on the internal Docker network
  - Stores Moodle data in named volume `moodledata`
  - Listens on port **80** inside the container (no host port mapping in production)

### Volumes

- `db_data` → `/var/lib/mysql` (MariaDB data)
- `moodledata` → `/var/www/moodledata` (Moodle file storage)

---

## Dockerfile Summary

The `Dockerfile`:

1. Uses `php:8.1-apache` as base image
2. Installs required system packages and PHP extensions for Moodle, including:
   - `intl`, `gd`, `exif`, `zip`, `mysqli`, `soap`, `xml`, `mbstring`, `curl`, `opcache`
3. Configures PHP settings via `moodle.ini` (e.g. `max_input_vars`, opcache settings)
4. Clones Moodle from Git using the `MOODLE_BRANCH` build arg
5. Creates `/var/www/moodledata` and sets permissions for `www-data`
6. Copies and uses `moodle-entrypoint.sh` as the container entrypoint before Apache

---

## EntryPoint Script: `moodle-entrypoint.sh`

On container start, if `/var/www/html/config.php` exists, the script will:

1. Ensure the database type is set to `mariadb`:
   - Replaces `$CFG->dbtype` values `mysql` / `mysqli` with `mariadb`.
2. Adjust `$CFG->wwwroot` for HTTPS / Coolify:
   - If `COOLIFY_URL` is set, it is used as the canonical URL
   - Else, if `MOODLE_WWWROOT` is set, that is used
   - Trailing slash is removed
3. Ensure `$CFG->sslproxy = true;` is present

The script **does not** change `$CFG->reverseproxy` so that this can be controlled manually in `config.php` if needed.

Finally, it executes the main Apache command:

```bash
exec "${@}"
```

---

## Environment Variables

### Database (service `db`)

These variables are defined in `docker-compose.yml` with local defaults:

- `MYSQL_ROOT_PASSWORD` (default: `rootpassword`)
- `MYSQL_DATABASE` (default: `moodle`)
- `MYSQL_USER` (default: `moodle`)
- `MYSQL_PASSWORD` (default: `moodlepass`)

In production (Coolify), you should override them via the application \"Environment Variables\" UI.

### Moodle (service `moodle`)

- `MOODLE_DB_HOST` (default: `db`)
- `MOODLE_DB_NAME` (default: `moodle`)
- `MOODLE_DB_USER` (default: `moodle`)
- `MOODLE_DB_PASSWORD` (defaults to `${MYSQL_PASSWORD}`)
- `MOODLE_DB_TYPE` (default: `mariadb`)

Optional:

- `MOODLE_WWWROOT` — If set, used as `$CFG->wwwroot` when `COOLIFY_URL` is not available

Coolify also injects:

- `COOLIFY_URL` — Public URL of the application, used by `moodle-entrypoint.sh` to set `$CFG->wwwroot`

---

## Running Locally

### Prerequisites

- Docker Engine
- Docker Compose (v2 recommended)

### Steps

1. Clone this repository:

```bash
git clone https://github.com/nonofr91/moodle.git
cd moodle
```

2. (Optional) override env vars in a `.env` file in the project root, e.g.:

```env
MYSQL_ROOT_PASSWORD=changeme-root
MYSQL_DATABASE=moodle
MYSQL_USER=moodle
MYSQL_PASSWORD=changeme-db
```

3. Start the stack:

```bash
docker compose up -d --build
```

4. Access Moodle in your browser:

- URL: `http://localhost:8080`

5. Follow the web installer. `config.php` will be generated in `/var/www/html/config.php` inside the `moodle` container.

---

## Deploying on Coolify

This project is designed to work well with **Coolify v4** using the **Docker Compose build pack**.

### 1. Create the Application

1. In Coolify, click **Create New Resource → Applications → Public Repository**.
2. Repository URL: `https://github.com/nonofr91/moodle.git`
3. Branch: `main`
4. Base Directory: `/` (empty if at repo root)
5. Build Pack: **Docker Compose**
6. Docker Compose Location: `/docker-compose.yml`

### 2. Configure Environment Variables

In the application **Environment Variables** section, set at least:

- `MYSQL_ROOT_PASSWORD=...`
- `MYSQL_DATABASE=moodle`
- `MYSQL_USER=moodle`
- `MYSQL_PASSWORD=...`

You may also override the `MOODLE_DB_*` variables if desired.

### 3. Ports & Domain

- Do **not** map host ports in `docker-compose.yml` for production (no `ports:` block on `moodle`).
- In the Coolify app configuration:
  - Set **Ports Exposes** to `80` (internal Apache port in the container).
  - Configure your domain, e.g. `https://moodle.example.com` (without `:80`).

Coolify will route HTTPS traffic to the `moodle` container on port 80.

### 4. First Deployment & Installation

1. Deploy the application from Coolify.
2. When both services `db` and `moodle` are **Running**, open your domain:
   - `https://moodle.example.com`
3. Complete the Moodle web installer (database details must match the values configured via env vars).
4. After installation, `config.php` will exist. On subsequent container starts, `moodle-entrypoint.sh` will:
   - Ensure `$CFG->dbtype = 'mariadb'`
   - Set `$CFG->wwwroot` to `COOLIFY_URL` (your HTTPS domain)
   - Ensure `$CFG->sslproxy = true;`

If needed, you can manually adjust `config.php` inside the `moodle` container and then run:

```bash
php admin/cli/purge_caches.php
```

to clear caches.

---

## Notes & Troubleshooting

- **No CSS / broken layout after install**
  - Typically caused by HTTP URLs on an HTTPS site (mixed content).
  - Ensure `$CFG->wwwroot` uses `https://` and that `$CFG->sslproxy = true;` is set.
  - Clear caches: `php admin/cli/purge_caches.php` inside the container.

- **Database connection issues in Coolify**
  - Make sure both `db` and `moodle` services are part of the same Docker Compose app.
  - `MOODLE_DB_HOST` must be `db` (service name) when using the internal database service.

- **Port conflicts on 8080**
  - Avoid using `ports: "8080:80"` in production. Let Coolify handle ports via the proxy and the **Ports Exposes** setting.

---

## License

This repository packages Moodle, which is GPL licensed. See https://moodle.org/ and the Moodle source for full license details.
