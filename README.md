# Sky Ecommerce

## Setup Instructions

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- Laravel 10.x
- Postman (for testing the API)

### Installation Steps

1. Clone the repository:

   ```bash
   git clone [https://github.com/your-repo.git](https://github.com/Hamid-Balabel/sky-ecommerce.git)
   cd sky-ecommerce
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy the `.env.example` file to `.env` and configure your environment variables:

   ```bash
   cp .env.example .env
   ```

   - Set database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Run database migrations and seeders:

   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:

   ```bash
   php artisan serve
   ```

7. Access the application at `http://localhost:8000`.

---

## API Documentation

### Option 1: View Documentation via Postman

- [Postman API Documentation](https://documenter.getpostman.com/view/29090481/2sAYQdjALv)


## Database Schema

### Entity-Relationship Diagram (ERD)

```
[user]
    - id (PK)
    - name
    - email
    - password
    - type
    - avatar
    - otp
    - email_verified_at
    - remember_token
    - created_at
    - updated_at

[Order]
    - id (PK)
    - customer_id (FK to User.id)
    - total_amount
    - status
    - created_at
    - updated_at
    - deleted_at

[Product]
    - id (PK)
    - name
    - price
    - created_at
    - updated_at
    - deleted_at

[Order_Product]
    - order_id (FK to Order.id)
    - product_id (FK to Product.id)
    - quantity
    - price
```

### Table Descriptions

#### users

| Field       | Type         | Description             |
| ----------- | ------------ | ----------------------- |
| id          | BIGINT       | Primary key             |
| name        | VARCHAR(255) | User name               |
| email       | VARCHAR(255) | User email              |
| type        | VARCHAR(255) | User type               |
| avatar      | VARCHAR(255) | User avatar             |
| created\_at | TIMESTAMP    | Record creation time    |
| updated\_at | TIMESTAMP    | Record last update time |

#### Orders

| Field         | Type          | Description                             |
| ------------- | ------------- | --------------------------------------- |
| id            | BIGINT        | Primary key                             |
| customer\_id  | BIGINT        | Foreign key to Customers                |
| total\_amount | DECIMAL(10,2) | Total order amount                      |
| status        | ENUM          | Order status (e.g., pending, succesful) |
| created\_at   | TIMESTAMP     | Record creation time                    |
| updated\_at   | TIMESTAMP     | Record last update time                 |
| deleted\_at   | TIMESTAMP     | Soft delete timestamp                   |

#### Products

| Field       | Type          | Description             |
| ----------- | ------------- | ----------------------- |
| id          | BIGINT        | Primary key             |
| name        | VARCHAR(255)  | Product name            |
| price       | DECIMAL(10,2) | Product price           |
| created\_at | TIMESTAMP     | Record creation time    |
| updated\_at | TIMESTAMP     | Record last update time |

#### Order\_Product (Pivot Table)

| Field       | Type          | Description             |
| ----------- | ------------- | ----------------------- |
| order\_id   | BIGINT        | Foreign key to Orders   |
| product\_id | BIGINT        | Foreign key to Products |
| quantity    | INTEGER       | Quantity ordered        |
| price       | DECIMAL(10,2) | Product price at order  |

---

