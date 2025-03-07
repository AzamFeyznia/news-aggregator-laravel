# News Aggregator - Laravel

A news aggregator application built with Laravel 12, designed to collect and display news articles from various sources.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [License](#license)

## Features

- **Data Aggregation:** Fetches news articles from multiple sources (NewsAPI, The Guardian, New York Times).
- **Scheduled Data Fetching:** Automatically updates articles using Laravel's task scheduler and queues.
- **API Endpoints:** Provides RESTful API endpoints for accessing articles.
- **Search and Filtering:** Allows users to search and filter articles by keyword, source, and category.
- **Pagination:** Implements pagination for efficient retrieval of large datasets.
- **API Versioning:** Supports multiple API versions for backward compatibility.
- **Rate Limiting:** Protects API endpoints from abuse using rate limiting.
- **Centralized Exception Handling:** Provides consistent error responses.
- **Automated Testing:** Comprehensive test suite with high code coverage.

## Technologies Used

- [Laravel](https://laravel.com/) (PHP Framework) - Version 12
- [PHP](https://www.php.net/) - Version 8.2 or higher
- [Guzzle HTTP Client](http://docs.guzzlephp.org/en/stable/) - For making HTTP requests to APIs
- [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/) - Relational database
- [Redis](https://redis.io/) - For caching and queueing
- [Swagger/OpenAPI](https://swagger.io/) - For API documentation
- [Composer](https://getcomposer.org/) - Dependency Management

## Installation

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/AzamFeyznia/news-aggregator-laravel.git
    cd news-aggregator-laravel
    ```

2.  **Install dependencies:**

    ```bash
    composer install
    ```

3.  **Copy the environment file:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate the application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Configure your database:**

    Edit the `.env` file to configure your database connection settings.

6.  **Run the database migrations:**

    ```bash
    php artisan migrate
    ```

## Configuration

Configure your services in `.env`
```apacheconf
NEWSAPI_API_KEY=
GUARDIAN_API_KEY=
NYTIMES_API_KEY=
```

## Usage

### Running the Application Locally (Without Docker)

1.  **Serve the application:**

    ```bash
    php artisan serve
    ```

2.  **Access the application:**

    Open your web browser and navigate to `http://localhost:8000`.

## API Endpoints

The API endpoints are documented using Swagger/OpenAPI.

1.  **Generate the API documentation:**

    ```bash
    php artisan l5-swagger:generate
    ```

2.  **Access the Swagger UI:**

    Open your web browser and navigate to `http://localhost:8000/api/documentation`.

    You can access all of the API endpoints with their documentation.

## Testing

1.  **Run the tests:**

    ```bash
    php artisan test
    ```

## License

This project is licensed under the [Apache License 2.0](LICENSE).
