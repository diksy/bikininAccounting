# Bikinin Accounting

A modular monolith PHP application built for modern accounting workflows. It uses SQLite during development and MariaDB in production, while the UI is built with semantic HTML and Tailwind CSS without requiring Node.js.

## Stack

- PHP 8.5 (stable/current runtime)
- Composer autoloading
- SQLite for local development
- MariaDB for production
- Tailwind CSS via CDN for templates
- Modular monolith structure

## Local setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

3. Start the app:
   ```bash
   php -S localhost:8000 -t public
   ```

4. Open the app in your browser:
   ```text
   http://localhost:8000
   ```

## Production settings

Set the following in `.env`:

```env
DB_CONNECTION=mariadb
DB_HOST=your-db-host
DB_PORT=3306
DB_NAME=bikinin_accounting
DB_USERNAME=your-user
DB_PASSWORD=your-password
```

## Tailwind usage

The app uses the Tailwind CDN script in the layout instead of a Node build pipeline:

```html
<script src="https://cdn.tailwindcss.com"></script>
```

This keeps the template stack semantic and simple while avoiding JavaScript tooling.

## Project structure

```text
app/
  Modules/
  Support/
  Http/
public/
  index.php
resources/
  views/
  layouts/
```
