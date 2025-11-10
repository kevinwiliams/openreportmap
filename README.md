# OpenReportMap

A lightweight, mobile-first web app that lets residents drop a pin, pick a report type (Outage, Blockage, Damage, Relief), set severity, add text + photos and immediately see every one else’s reports update on a live map.

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.3)
- **Database:** MySQL 8
- **Frontend:** React 18, Vite, Material-Web, Leaflet 1.9
- **Deployment:** Docker

## Getting Started

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/open-report-map.git
   cd open-report-map
   ```

2. **Set up the environment:**
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials.

3. **Install dependencies and run the application:**
   ```bash
   docker-compose up -d --build
   ```

4. **Run database migrations and seeders:**
   ```bash
   docker-compose exec api php artisan migrate --seed
   ```

5. **Access the application:**
   - **Frontend:** [http://localhost:5173](http://localhost:5173)
   - **API:** [http://localhost:4000/api](http://localhost:4000/api)
