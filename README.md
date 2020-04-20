# Web applications vulnerable to mongoDB injection attacks

Two web apps are presented intentionally vulnerable to MongoDB injection attacks. This project was designed to be used as target in penetration tests done through Zap Proxy (by uploading a specific add-on that you can find already available in this project).

## Getting Started

In order to start the project it is necessary to perform some preliminary steps:

1. installing Apache Web Server (or whatever you prefer);
2. installing MongoDB shell version (I used the v3.6.6 version);
3. installing PHP and PHP-MongoDB driver;
4. populate the DB by a script that you can found in the apposite folder;
5. load on Zap the ascanureles add-on that contains the rules to scan MongoDB;
6. starts a scan that targets php scripts.

### Prerequisites

- MongoDB shell version v3.6.6
- PHP 7.2.9 (cli)
- PHP-MongoDB driver (http://php.net/manual/en/mongodb.tutorial.library.php).

### Installing

With Fedora 28:
- sudo dnf install php-pecl php-devel php-json
- sudo pecl install mongodb
- wget https://getcomposer.org/download/1.7.3/composer.phar
- mv composer.phar /usr/local/bin/composer
- Install mongodb-php driver. Go to php_webapp folder and type:
  composer require mongodb/mongodb
- sudo setsebool -P httpd_can_network_connect on (if necessary)

## Running the tests

First of all we need to populate the db. I have prepared a simple collection that you can use by running the script in the populate folder after ceating and positioning on a mongodb db named "test" (that is the default one).
This is a basic collection of users with three fields: user, pass and evod (rappresenting respectively: username, password and a even/odd number.

### search_by_evod_value.php

It is a very simple script. You must enter an integer and it will return the user to whom that value is associated. The vulnerability is that if you replace the query string searchInput=some_someInteger with searchInput[$ne]= it will return the first tuple of the collection which has an evod value other than the empty string.

### login.php

It is a basic login script. The vulnerability is the same of the previous script, but in this case it is necessary to attack two fields at the same time (or enter a correct known value in one of and attack the other).

### loginMD5.php

It is login script slightly more articulated than the previous one because both user and password are stored in hash format. $where clause permit to insert an arbitrary javascript function as a predicate of the interrogation, and  inspired by official documentation (https://docs.mongodb.com/manual/reference/operator/query/where/) it is used to calculate the md5 value of both user and password fields (in the population script the usermd5 was designed specifically for this script).
This script is attackable through a javascript code injection, the following: zap") || sleep(1000) && md5("zap.
Note: the javascript function is executed one time for each tuple of db, so if you have not entered other tuples in the collection the time you will have to wait before receiving the answer is 12 seconds.

### get_users_by_evod.php

It is a variant of the previous script. It can be attacked with the following javascript code injection: 0 || sleep(1999).

## Zap Proxy

To detect these vulnerabilities automatically you can use OWASP ZAP (https://github.com/zaproxy/zaproxy) by adding the plugin that you find in the Zap_Proxy_plugin folder.
