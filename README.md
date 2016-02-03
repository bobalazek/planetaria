README
======
**Planetaria**

The game!

Requirements & Tools & Helpers
-------------------
* PHP > 5.3.9
* [Composer](https://getcomposer.org/)
* [Bower](http://bower.io/)
* [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) (optional)

Setup / Development
-------------------
* Navigate yor your web directory: `cd /var/www`
* Clone this repo: `git clone git@bitbucket.org:bobalazek/planetaria.git`
* Configure database (and maybe other stuff if you want): `app/configs/global-local.php.dist` (create your own global-local.php by copy / paste-ing this file)
* Run the following commands:
    * `composer install`
    * `bin/console orm:schema-tool:update --force` (to install the database schema)
    * `bower update` (to install the front-end dependencies - you will need to install [Bower](http://bower.io/) first - if you haven't already)
    * `bin/console application:database:hydrate-data` (to hydrate some data)
* You are done! Start developing!

Database
-------------------
* We use the Doctrine database
* Navigate to your project directory: `cd /var/www/planetaria`
* Check the entities: `bin/console orm:info` (optional)
* Update the schema: `bin/console orm:schema-tool:update --force`
* Database updated!

Administrator login
-------------------
With the `bin/console application:database:hydrate-data` command, you will, per default hydrate the following users (which you can change inside the `app/fixtures/users.php` file):

* "borut"
    * Username: `borut`
    * Email: `bobalazek124@gmail.com`
    * Password: `test`
* "ana"
    * Username: `ana`
    * Email: `anakociper124@gmail.com`
    * Password: `test`

Commands
--------------------
* `bin/console application:environment:prepare` - Will create the global-local.php and development-local.php files (if they do not exist)
* `bin/console application:database:hydrate-data [-r|--remove-existing-data]` - Will hydrate the tables with some basic data, like: 2 users and 6 roles (the `--remove-existing-data` flag will truncate all tables before re-hydrating them)
* `bin/console application:storage:prepare` - Will prepare all the storage (var/) folders, like: cache, logs, sessions, etc.
* `bin/console application:translations:prepare` - Prepares all the untranslated string into a separate (app/locales/{locale}_untranslated.yml) file. Accepts an locale argument (defaults to 'en_US' - usage: `bin/console application:translations:prepare --locale de_DE` or `bin/console application:translations:prepare -l de_DE` )

Gulp Command
--------------------
First you'll need to install gulp and gulp's auto plugin loader `sudo npm install gulp gulp-load-plugins`

* `gulp optimize-tile-images` - Optimizes the tile images

Other commands
----------------------
* `php-cs-fixer fix .` - if you want your code fixed before each commit. You will need to install [PHP Coding Standards Fixer](http://cs.sensiolabs.org/)

License
----------------------
Proprietary to Borut Balazek. All Rights reserved.
