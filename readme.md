
###Setting up the project locally
This project is using Docker as the development environment so that you will need to install Docker and the docker-compose command on your machine.
There are so many articles available online how to install Docker and docker-compose command. It is easier for Mac OS.

After installing the required tools successfully, you need to run the following commands to get the project up and running locally within the project's root folder.
Also you need to make sure that you started the Docker on your machine.

- `docker-compose up --build -d`
- `docker-compose exec php-fpm cp .env.example .env`
- `docker-compose exec php-fpm composer install`
- `docker-compose exec php-fpm php artisan passport:install`
- `docker-compose exec php-fpm php artisan migrate:fresh --seed`
- `docker-compose exec db mysql -uroot -proot -e 'CREATE DATABASE IF NOT EXISTS testing;'`
- `docker-compose exec db mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON testing.* TO 'xanda'@'%';"`
- `npm install`
- `npm run watch`

That's it. Now if you go to `xanda.localhost`, you should be able to see the Laravel default page.

###Integration Tests

I have also added the Integration Tests which you can find in the `{project_folder}/tests/Feature` folder.
To run the tests using PHPUnit, you need to run the following command from within the php Docker container.

- `docker-compose exec php-fpm vendor/bin/phpunit --filter=SpacecraftControllerTest`


####Running the Composer/ Artisan command

To run the Composer/ Artisan commands, you will also need to run them within the PHP Docker image container too.

For example,
- `docker-compose exec php-fpm composer install`
- `docker-compose exec php-fpm php artisan migrate:fresh --seed`
- `docker-compose exec php-fpm php artisan passport:install`

####Consuming/ Testing the REST API

This application is using Laravel Passport to implement REST API.
You can use the REST Client/ Postman or other similar tools to test the API.
I am using Laravel Passport because in the test it is mentioned to implement a REST API.

You will need to use the following credentials to consume protected endpoints of the API and those credentials are seeded into the database using the Seeder class too.

#####Access Client
- `Client ID: 1`
- `Client secret: XGePat23p90f7VP4p3EOaePl8gqLZlGO47uwDP26`

#####Grant Client
- `Client ID: 2`
- `Client secret: cjW4TBGkYJmoR7e4iMcciNgeBPAoQYWlXp4dPsuY`

To access the protected endpoints of the API, first, you need to generate OAuth Access Token.
To do that, you will need to make `POST` JSON request to the following endpoint passing the payload mentioned below in JSON format.

- `http://xanda.localhost/oauth/token`

#####Payload

```
{
    "grant_type": "password",
    "client_id": 2,
    "client_secret": "cjW4TBGkYJmoR7e4iMcciNgeBPAoQYWlXp4dPsuY",
    "username": "general@xanda.net",
    "password": "password",
    "scope": ""
}
```
The `client_id` and `client_secret` are the `Grant Client` and `Client secret` which are mentioned above.
If the credentials did not work out, you can generate the new one and use the credentials printed out in the terminal after running the following command.
- `docker-compose exec php-fpm php artisan passport:install`

You can see `username` and `password` in the `UserSeeder` database seeder class.

After you made the request to generate token, you can see the Access token in the response. To make the call to the endpoints related to the game, you need to pass that token in the header in following format.
You need to make sure that you are only copying the token from the `access_token` field.

`Authorization: Bearer {access_token}`

You can see the API routes in the `routes/api.php` file. If the request validation failed, the API will return 422 HTTP status code.

####GUI to visualise the API

The goal of the project is to build the REST API. But I have built a admin interface with very basic UI to be able to test the API that have the same functionality as the API.

If you looked into the `Controllers` folder, there are two controllers `SpacecraftController` and `Api\SpacecraftController`. The `SpacecraftController` is used by the admin interface whereas the `Api\SpacecraftController` is the actual REST API.

To login, you have to go to the login page and use the following credentials to login. After successful login, you will be redirected to the admin dashboard.

- `email: general@xanda.net`
- `password: password`

####Notes
- If you cannot use those credentials to login, you might need to refresh the database with seeds by running the fresh command `docker-compose exec php-fpm php artisan migrate:fresh --seed`.
- Sometimes, while you are setting up the project running the commands mentioned in the beginning, you might have an issue with this command, `docker-compose exec php-fpm cp .env.example .env`. Sometimes, it create a folder instead of a file. Som in this case, please create a `.env` file manually and copy the content from `.env.example` file.
- I did not use `Vue JS` or `React JS` because it just developed a very basic UI within the time frame I got using `JQuery` to provide a way to visualize the data and the test the functionality
- You can find the routes in the `routes/api.php` and `routes/web.php` files.
- As I mentioned I created 2 controllers one is for the REST API routes and the other one is for backend routes for GUI. The reason I developed REST API using Laravel Passport is because it is mentioned in the question to build a REST API.
- I have written the Integration tests for the REST API Controller, `Api\SpacecraftController` only for the time-being. But both controllers are very identical.

If you have any question, please let me know, `iljimae.ic@gmail.com`.

This project has areas to be improved and I had left them out for the time-being. But as a test, I it is enough to assess a candidate.

Thanks a lot for giving you time.
