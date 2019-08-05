<p align="center">
<img src="https://www.saloodo.ae/wp-content/uploads/2018/04/logo.svg"></p>

<p align="center">
======


API Documentation
=============
Please feel freee to contact me :

Imran Omar Bukhsh<br>
Email: imranomar@gmail.com<br>
Mobile: 00971 50 4225054<br>

NOTE: Has been created with Larevel 5.8.29

NOTE: The api is available online running at http://3.86.155.55/api/...

NOTE: api documentation can be found here at
https://documenter.getpostman.com/view/7566754/SVYqQKF7?version=latest

CONFIGURATION
-------------
Database configuration can be found in .env file in root

Authorization
-------------
Orders - only admin can update, view all orders, and destroy orders
       - user can create and view his own orders only
       - user needs to be logged in to view his orders

Proucts -only admin can create, update, delete
	-users can view products list and product details

Note: to make a user admin. chang role_id to 1 for that user in the users table


LOGIC
-----
.Price is stored in cents and not in euro and is converted using setters and getters

.Paging is enabled for products list and order list. View sample end points in postman

VALIDATIONS CODE
-----------------
.can be found in http>requests folder

.custom validation in app>rules folder

.Note: product title should be unique

.For product bubdles and orders the product ids will be checked if they exists


DATABASE
--------
.migrations can be found in database>migrations . Install with php artisan migrate

CODE DETAILS
------------
.validation can be found in http>requests folder

.custom validation in app>rules folder

.model can be be found in /app

.contollers can be found in /app/http/controllers

FILE PERMISSIONS
----------------
Make sure the following are writable:

/opt/bitnami/apache2/htdocs/storage/logs 

/opt/bitnami/apache2/htdocs/storage/framework

TO INSTALL
----------
Requirements: php7, composer, mysql

.download from github - https://github.com/imranomar/solodoo-laravel.git

.setup the .env file for your database

.run the folowing in the root folder:

php artisan migrate

php artisan passport:install

db:seed

.Go to the oauth_clients table and note the 'id' and the 'secret' of "Laravel Password Grant Client"
the 'id' and 'secret' will be used in http://{{url}}/oauth/token to get the token for the user. Please see the sample endpoint in postman doc

UNIT TESTS ( initial )
---------------------------
.can be found in /vendor/bin/phpunit

.can be run with 'phpunit' (/vendor/bin/phpunit) in the root



</p>
