[![Build Status](https://travis-ci.com/andygi/gd-mylist.svg?branch=master)](https://travis-ci.com/andygi/gd-mylist)

# How to install

For Mac/Unix run install shell: `./install.sh`

## Install PHPUnit

Manual installation

_Note_: 
- **WP_UnitTestCase** works with PHPUnit 7 or below only.
- Use **Mysql** on host: `127.0.0.1` (*localhost* not works)

run `composer require phpunit/phpunit:7`
install Docker `docker run -p 3306:3306 --name wp-mysql-5.7 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=root -e MYSQL_PASSWORD=root -d mysql:5.7`
run `bash bin/install-wp-tests.sh wordpress_test root 'root' 127.0.0.1:3306 latest`

## Use PHPUnit

Apparently we need to declare everytime the testing file :
`./vendor/bin/phpunit tests/test-gd-mylist.php`
I have include PHPUnit into npm test script so we can use:
`npm test`

# Build the project

In root directory run the command 
```
gulp buil
```
the project will be build in **dist** directory ready to use.
Check `gulpfile.js` for more information.

# Translations

I use PoEditor and this is the repo: https://poeditor.com/projects/view?id=66915
