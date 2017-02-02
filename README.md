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

What's inside?
--------------



[1]:  https://symfony.com/doc/2.8/setup.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/2.8/doctrine.html
[8]:  https://symfony.com/doc/2.8/templating.html
[9]:  https://symfony.com/doc/2.8/security.html
[10]: https://symfony.com/doc/2.8/email.html
[11]: https://symfony.com/doc/2.8/logging.html
[12]: https://symfony.com/doc/2.8/assetic/asset_management.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
