# Nutrition Tracking OCR

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-12.0-red)
![Livewire](https://img.shields.io/badge/Livewire-3.x-pink)
![License](https://img.shields.io/badge/License-MIT-green)

## Description

Nutrition Tracking OCR is a web application designed to automate and monitor daily dietary intake. It utilizes Vision-Language Models (VLM) via the Gemini API to perform Optical Character Recognition (OCR) directly on food packaging labels. This eliminates manual data entry, allowing users to accurately track macronutrients, micronutrients, Basal Metabolic Rate (BMR), and Total Daily Energy Expenditure (TDEE) in alignment with BPOM standards.

## Features

* **Nutritional OCR Scanning:** Extracts nutritional values directly from food packaging images using the Gemini API.
* **Comprehensive Health Tracking:** Calculates and logs BMR, TDEE, macros, and micros for each user.
* **Data Visualization:** Renders daily intake metrics and historical nutritional data using Chart.js.
* **TKPI Database Integration:** Allows users to log meals manually using the standardized Indonesian Food Composition Data (TKPI).
* **Administrative Dashboard:** Powered by Filament for full CRUD management of system data, users, and food entries.
* **Authentication:** Secure login, registration, and session management built natively with Laravel Starter Kit.

## Screenshots

[Insert Screenshot of OCR Scanning Interface Here]

[Insert Screenshot of Nutrition Charts/Dashboard Here]

[Insert Screenshot of Filament Admin Panel Here]

## Prerequisites

This project uses Laravel Sail for local development. You must have the following installed:

* [Docker Desktop](https://www.docker.com/products/docker-desktop/)
* [Git](https://git-scm.com/)
* A valid [Google Gemini API Key](https://aistudio.google.com/app/apikey)

## Installation

Follow these steps to deploy the application locally using Docker.

1. **Clone the repository:**

```bash
git clone [https://github.com/qodlizaka/nutrition-tracking-ocr.git](https://github.com/qodlizaka/nutrition-tracking-ocr.git)
cd nutrition-tracking-ocr
```

2. **Configure environment variables:**

```bash
cp .env.example .env
```

Open the `.env` file and append your Gemini API key:

```env
GEMINI_API_KEY=your_actual_api_key_here
```

Don't forget to set up admin email & password:

```sh
ADMIN_EMAIL=prefered@email.com
ADMIN_PASSWORD=admin_password
```

3. **Install Composer dependencies:**

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

4. **Initialize Docker containers:**

```bash
./vendor/bin/sail up -d
```

5. **Generate the application key and execute migrations:**

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

6. **Install NPM packages and compile frontend assets:**

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## Usage

* **Main Application:** Access the user dashboard at `http://localhost`.
* **Admin Panel:** Access the Filament administrative interface at `http://localhost/admin`.
* **Shutdown:** Stop the Docker containers by running `./vendor/bin/sail down`.

## Contributing

Pull requests are welcome. For major changes, open an issue first to discuss the proposed modifications.

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
