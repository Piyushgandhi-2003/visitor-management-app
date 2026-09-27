# Sycatel Visitor Management System

Containerized visitor management app for security personnel to log visitor entries.
Built with PHP (php-fpm) + Nginx + MySQL, orchestrated via Docker Compose.

## Architecture

```
Browser → Nginx (port 8080) → PHP-FPM (app container) → MySQL (with named volume)
```

- **nginx** — reverse proxy, serves static assets, forwards `.php` requests to `app` over FastCGI
- **app** — PHP 8.2-FPM, connects to MySQL via PDO using env vars (no hardcoded credentials)
- **mysql** — MySQL 8.0, schema auto-created from `mysql/init.sql`, data persisted in a named volume

## Features

- Session-based login for security personnel (no more open access)
- Dashboard with live stats (total visitors, today's count, on-site count) and a Chart.js breakdown by visit reason
- Add visitor (with server-side validation + CSRF protection)
- View all visitors — search by name/mobile, pagination, edit, delete
- Check-in **and** check-out timestamps
- Filter visitors by reason
- Dark/light mode toggle (persisted in browser)
- Responsive layout for tablets/phones

## Setup

1. Copy the environment template and set your own passwords:
   ```bash
   cp .env.example .env
   nano .env
   ```
2. Build and start the stack:
   ```bash
   docker compose build
   docker compose up -d
   ```
3. Visit `http://<your-ec2-public-ip>:8080` (or `http://localhost:8080` locally).
4. Log in with the seeded default account:
   - **Username:** `admin`
   - **Password:** `admin123`

   ⚠️ Change this immediately in production — there's no in-app "change password" screen yet,
   so generate a new hash with `password_hash('yourpassword', PASSWORD_DEFAULT)` in a quick PHP
   snippet and update the `admins` table directly.

## Notes / known simplifications

- Delete/checkout actions are plain GET links for simplicity — fine for a demo/portfolio project,
  but in a real production app these should be POST requests with CSRF tokens too.
- No password-reset flow yet — a good next feature to add.
- This is the **Docker Compose** phase of the project. Kubernetes and AWS deployment variants
  are separate projects/phases.

## Stopping / resetting

```bash
docker compose down          # stop containers, keep data
docker compose down -v       # stop containers AND wipe the database volume
```
