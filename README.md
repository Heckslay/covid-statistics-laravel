# COVID-19 Statistics Laravel API 🚀
## Introduction
This Laravel app handles fetching/synchronizing of the COVID-19 statistics
on hourly basis. Also, provides sanctum auth protected endpoints for fetching total summary 
and per-country summary of the following statistical information:
1. Confirmed Disease Cases
2. Recovered Cases
3. Death Cases

The task was done based on requirements available on this 
[link](https://gist.github.com/giunashvili/279f4ae108501b30caae4c7486c8ba64).

## Installation
This is a regular Laravel project. The basic requirements and guide for Laravel project installation 
can be found [here](https://laravel.com/docs/9.x).
Since, it was required app to be run via Octane, you would want to select RoadRunner binary to run
locally. You'll be asked to select the preferred binary during composer install.
You should create .env file and update it with values for the following entries:
> OCTANE_SERVER=roadrunner
> COVID_API_BASE_URL="https://devtest.ge/"

Also populate .env with correct database connection details.<br>
Run migrations after:
> php artisan migrate

To fetch the countries run:
> php artisan command:sync-countries

For local test run, to fetch statistics without waiting for scheduler go to console/Kernel.php
and change:
> $schedule->command('command:sync-statistics')->hourly();

to

> $schedule->command('command:sync-statistics')->everyTwoMinutes();

Then open up two terminal windows and run in the first one:
> php artisan schedule:work

and 

> php artisan queue:listen

Since this is just a test project, you'll need to populate the users table with a seeder.
Simply run:
> php artisan db:seed

To create 10 users in the database. Use your DBMS to fetch the users, take email and default password
which is directly "password" string to do an auth.

To execute the available feature tests run:
> php artisan test

in the second one. You'll be able to see the sync queue execution progress in the second terminal.
Once it's done, you can close these terminals and run:
> php artisan octane:start

To start the web server.

## API Documentation
It didn't feel right to put the API docs in Readme file. You can find the Postman collection
with included API 
documentation [here](https://drive.google.com/file/d/1j9byz71nzRdrFFcKZYS5mFHh-nQ6McPA/view?usp=sharing).

## Project Improvement Ideas
1. Integrate Redis caching to reduce calls to database
2. Implement sign up
3. More to come...




