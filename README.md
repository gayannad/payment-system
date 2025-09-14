# Payment System

Laravel + React payment file processing system.

## Features

- Upload CSV payment files
- Background file processing
- S3 file storage
- Send email notifications

## Installation

1. **Clone and install**
   ```bash
   git clone https://github.com/gayannad/payment-system.git
   cd payment-system
   composer install
   npm install
   npm run build
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure .env**
   ```env
   DB_DATABASE=payment_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   AWS_ACCESS_KEY_ID=your_key
   AWS_SECRET_ACCESS_KEY=your_secret
   AWS_BUCKET=your_bucket
   AWS_ACCESS_KEY_ID=AKIA42YLNNI3HE2ZKTPE
   AWS_DEFAULT_REGION=region
   ```

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

## Usage

1. **Start server**
   ```bash
   php artisan serve
   php artisan queue:work
   ```

2. **Upload files**
    - Login to dashboard
    - Upload CSV/
    - Files are processed in the background
   
   
3. **Process Payout (Schedular)**
     ```bash
   php artisan process-payouts
   ```
   
4. **Access API Doc**
  - http://localhost:8000/docs/api

