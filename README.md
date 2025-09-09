POS System

A modern, feature-rich Point of Sale system built with Laravel

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![License](https://img.shields.io/badge/License-MIT-green)



## Features

- Category management
- Product inventory management
- Customer management
- Sales processing with multiple payment methods
- Receipt generation and printing
- Low stock alerts
- Sales reporting and analytics




## Installation

1. Clone the repository:
   - git clone https://github.com/yourusername/pos-system.git
   
2. Install dependencies:
   - composer install

3. Set up environment:
   - cp .env.example .env
   - php artisan key:generate

4. Configure database in .env file

5. Run migrations:
   - php artisan migrate --seed

6. Start the development server:
   - php artisan serve




## Usage

- Create Product & Categories
- Making a Sale
- Navigate to the POS interface
- Scan or search for products
- Select customer 
- Apply discounts if needed
- Process payment
- Print receipt



## Generating Reports

- Daily sales summaries
- Product performance
- Customer purchase history
