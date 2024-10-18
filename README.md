# Fade_ta

Simple single api-platform endpoint for creating a user.

## Prerequisites

- Docker
- Docker Compose
- Symfony CLI

## Getting Started

1. Clone the repository:

   ```
   git clone git@github.com:paulroon/fade_ta.git
   cd fade_ta
   ```

2. Start the project:
   ```
   make start
   ```
   This command will:
   - Start Docker containers
   - Drop the existing database (if any)
   - Create a new database
   - Run migrations
   - start the local development server

## Running Tests

To run the tests, use the following command:

```
make test
```

This command will:

- Drop the test database (if it exists)
- Create a new test database
- Run migrations for the test environment
- Execute PHPUnit tests

## Makefile Commands

- `make start` - Start the project
- `make test` - Run tests

## API Endpoints

The main API endpoint for creating a user is:

- POST `/api/users`

For more details on the API, refer to the API documentation or the `User` entity in `src/Entity/User.php`.

## Project Structure

- `src/`: Contains the main application code
- `tests/`: Contains test files
- `migrations/`: Database migration files
- `config/`: Configuration files
