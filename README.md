Symfony Standard Edition
========================

API
--------------

In order to see the API endpoints you need to install Postman and 
import the file `comm.postman_collection` . 
After that create a new environment in Postman and add two new
variables to that environment:
1. `baseUrl` = the actual URL like http://localhost/comm/
2. `token` = `Bearear ` + the token generated on login (firstly follow
the steps from `Setup` and then get back to this)


Setup
--------------

1. Depending on your PHP version you may get a deprecation warning. 
Please proceed according to that warning and set `always_populate_raw_post_data`
to `-1` in your php.ini
2. Open the project's root folder
3. Run `composer install`
4. Run `php app/console doctrine:migrations:migrate`
5. Add a new user using Postman `POST /users`
6. Test with Postman if you can log in with the new user

What's inside?
--------------

1. Restful API following the Richardson Maturity Model, Level 2.
2. Controllers are services in order to make testing easier
3. Model is split in entities and repositories as in Data Mapper pattern.
4. Business logic is located in services.
5. Authentication is using JWT
