[![Build Status](https://travis-ci.com/andygi/gd-mylist.svg?branch=master)](https://travis-ci.com/andygi/gd-mylist)

# How to install

For Mac/Unix run install shell: `./install.sh`

## Install PHPUnit

Manual installation

#### Prerequisite
- **WP_UnitTestCase** works with **PHPUnit 7** or below ONLY.
- Use **Mysql** on host: `127.0.0.1` (*localhost* not works)

#### Steps
1. run 
    `composer require phpunit/phpunit:7`
    and the will be load new files
    ```
    PLUGIN_NAME
        |— vendor [dir]
        |— composer.json
        `— composer.lock
    ```

2. install Docker 
    ```
    docker run -p 3306:3306 --name wp-mysql-5.7 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=root -e MYSQL_PASSWORD=root -d mysql:5.7
    ```

3. Change your terminal’s directory to the root of your WordPress installation, and run the command below to generate the plugin test files.
    `wp scaffold plugin-tests PLUGIN_NAME`
    and the plugin directory load new files/dir
    ```
    PLUGIN_NAME
    |— bin
    |    `— install-wp-tests.sh
    |— tests
    |    |— boostrap.php
    |    `— test-sample.php
    |— .phpcs.xml.dist
    |— .travis.yml
    `— phpunit.xml.dist
    ```

4. run
    `bash bin/install-wp-tests.sh wordpress_test root 'root' 127.0.0.1:3306 latest`
    
    **Note**
    In case of error after `install-wp-tests.sh` run:
    ```
    alias mysql=/usr/local/mysql/bin/mysql
    export PATH=$PATH:/usr/local/mysql/bin/
    ```
    then run again
    `bash bin/install-wp-tests.sh wordpress_test root 'root' 127.0.0.1:3306 latest`

## Use PHPUnit

Apparently we need to declare everytime the testing file :
`./vendor/bin/phpunit tests/test-gd-mylist.php`

I have include PHPUnit into npm test script so we can use:
`npm test`

# Build the project

In root directory run the command 
```
gulp prod
```
the project will be build in **dist** directory ready to use.

Check `gulpfile.js` for more information.

_Note: `gulp prod` is fire at pre-commit and push action.

# Translations

Translation file are available/editable with PoEditor and this is the repo: https://poeditor.com/projects/view?id=66915
