Symfony Standard Edition
========================

API
--------------

Postman
In order to see the API endpoints you need to install Postman and 
import the file `postman.coolection.json` . 
After that create a new environment in Postman and add two new
variables to that environment:
1. `baseUrl` = the actual URL like http://localhost/comm/
2. `token` = `Bearear ` + the token generated on login


Setup
--------------

1. Depending on your PHP version you may get a deprecation warning. 
Please proceed according to that warning and set `always_populate_raw_post_data`
to `-1` in your php.ini
2. Add a new user using Postman `POST /users`
