# ATS Boost

Adapt your CV to match any job posting in seconds.

## Tech Stack

[![Laravel](https://img.shields.io/badge/Laravel-10-F05340?logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql)](https://www.mysql.com)
[![Docker](https://img.shields.io/badge/Docker-24-2496ED?logo=docker)](https://www.docker.com)

## Features

- Instant CV tailoring to specific job descriptions.
- AI‑powered keyword extraction and matching.
- Live preview of the adapted resume.
- Multiple export formats (PDF, DOCX, HTML).
- Subscription management with Mercado Pago integration.
- Secure authentication powered by Laravel Fortify.

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/brunorossani/ats-boost.git
   cd ats-boost
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Install PHP dependencies**
   ```bash
   composer install
   ```

4. **Install front‑end dependencies**
   ```bash
   npm install
   npm run build
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seed data**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   The app will be available at `http://localhost:8000`.

## Contributing

Contributions are welcome! Please read the [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

This project is licensed under the MIT License.
