# Stubborn

E-Commerce website built with Symfony and Stripe.

## :magic_wand: Features

- :sparkles: Symfony
- :sparkles: Stripe

## :building_construction: Getting Started

### :page_facing_up: Prerequisites

You'll need a local server like Wamp : https://wampserver.aviatechno.net/

### :hammer: Installation

1. First download all files or, from your command line,  clone the project :

```sh
# Clone repository
$ git clone https://github.com/AlexD004/CEF_Stubborn.git
```

2. After that, you have to save all files in Wamp folders.

a. Locate the good folder
📂 C: --> 📂 wamp64 --> 📂 www 

b. Create a new site
In 📂 www, create a new folder named Stubborn
The new path is 📂 C: --> 📂 wamp64 --> 📂 www --> 📂 Stubborn

c. Place files
You can now place all content of 'CEF_Stubborn-main' folder that you just downloaded into the folder 📂 Stubborn

3. Upload the database

a. Start wampserver
b. In your browser, visit the URL : localhost/
c. Open 'PhpMyAdmin' (link below 'Your Aliases')
d. Click 'Import' tab and upload the file "stubborn_database.sql" to create the database

4. Open the project on your favorite IDE

a. Create a file named '.env.local' and copy / paste info from the pdf received with the url (only for CEF homework, you can config the project as you want...)
b. Send command on terminal to test the add of product on cart :
```sh
php bin/phpunit tests/CartTest.php
```
c. Send command on terminal  to launch the app :
```sh
symfony server:start
```

