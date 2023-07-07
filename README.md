# FirstVet challenge (Back)

This is the backend code of FirstVet's technical challenge, written in PHP with Laravel. Here we have different functions to obtain a list of available schedules for a veterinarians.

Visit https://firstvetbackend-30011751fb5b.herokuapp.com/api/schedule to see the result!

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Tests](#tests)
- [Technical Challenge Notes](#technical-challenge-notes)


## Prerequisites

1. Install PHP on your system
2. Install Composer
3. Install Laravel
   ```sh
   composer global require laravel/installer
   ```


## Installation
 1. Clone the repository.
   ```sh
   git clone https://github.com/oihi08/first_vet_challenge_back.git
   ```
 2. Install dependencies using Composer.
  ```sh
   composer install
   ```
3. Create a copy of the .env.example file and rename it to .env.

4. Generate an application key.
   ```sh
   php artisan key:generate
   ```

5. Copy and paste given key in .env in APP_KEY value.


## Usage
1. Start the development server.
  ```sh
   php artisan serve
   ```

2. Visit http://localhost:8000 


## Tests
1. To run the project's tests, execute the following command:
  ```sh
   php artisan test
   ```

## Technical Challenge Notes
This list provides an overview of the files modified for the technical challenge and their respective purposes.

1. **app/Services/VetScheduleService.php** -> This file contains all the service functions for retrieving the best solution..
2. **config/constants.php** -> A mock for rendering data has been created in this file. It is the same as in the case.
3. **routes/api.php** -> The API for rendering data to the frontend can be found in this file.
4. **tests/Mocks/ScheduleMock.php** -> This file includes a mock specifically created for the tests.
5. **tests/Unit/app/Services/VetScheduleServiceTests.php** -> All the tests related to the service functions are written in this file.
