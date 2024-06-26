<?php

return array(
    'environment' => 'development',
    'debug' => true,
    'name' => 'Planetaria',
    'version' => '0.1.0',
    'author' => 'Borut Balazek',

    // Admin email (& name)
    'email' => 'planetaria@corcosoft.com',
    'emailName' => 'Planetaria Mailer',

    // Default Locale / Language stuff
    'locale' => 'en_US', // Default locale
    'languageCode' => 'en', // Default language code
    'languageName' => 'English',
    'countryCode' => 'us', // Default country code
    'flagCode' => 'us',
    'dateFormat' => 'd.m.Y',
    'dateTimeFormat' => 'd.m.Y H:i:s',

    'locales' => array( // All available locales
        'en_US' => array(
            'languageCode' => 'en',
            'languageName' => 'English',
            'countryCode' => 'us',
            'flagCode' => 'us',
            'dateFormat' => 'd.m.Y',
            'dateTimeFormat' => 'd.m.Y H:i:s',
        ),
        /* 'de_DE' => array(
            'languageCode' => 'de',
            'languageName' => 'Deutsch',
            'countryCode' => 'de',
            'flagCode' => 'de',
            'dateFormat' => 'd.m.Y',
            'dateTimeFormat' => 'd.m.Y H:i:s',
        ), */
    ),

    // Environments
    'environments' => array(
        'staging' => array(
            'domain' => 'planetaria.corcosoft.com',
            'uri' => '/',
            'directory' => '/home/corcosoft/subdomains/planetaria',
        ),
    ),

    // Time and date
    'currentTime' => date('H:i:s'),
    'currentDate' => date('Y-m-d'),
    'currentDateTime' => date('Y-m-d H:i:s'),

    // Database / Doctrine options
    // http://silex.sensiolabs.org/doc/providers/doctrine.html#parameters
    'databaseOptions' => array(
        'default' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'planetaria',
            'user' => 'planetaria',
            'password' => 'planetaria',
            'charset' => 'utf8',
        ),
    ),

    // Swiftmailer options
    // http://silex.sensiolabs.org/doc/providers/swiftmailer.html#parameters
    'swiftmailerOptions' => array(
        'host' => 'corcosoft.com',
        'port' => 465,
        'username' => 'planetaria@corcosoft.com',
        'password' => 'mySuperSuperSecurePassword',
        'encryption' => 'ssl',
        'auth_mode' => null,
    ),

    // Remember me Options
    // http://silex.sensiolabs.org/doc/providers/remember_me.html#options
    'rememberMeOptions' => array(
        'key' => 'someRandomKey',
        'name' => 'user',
        'remember_me_parameter' => 'remember_me',
    ),

    // User System Options
    'userSystemOptions' => array(
        'roles' => array(
            'ROLE_SUPER_ADMIN' => 'Super admin',
            'ROLE_ADMIN' => 'Admin',
            'ROLE_USERS_EDITOR' => 'Users editor',
            'ROLE_POSTS_EDITOR' => 'Posts editor',
            'ROLE_USER' => 'User',
        ),
        'registrationEnabled' => true,
    ),

    // Game options
    'gameOptions' => array(
        'townBuildRadius' => 10, // How far away from the town center (capitol) can the building be build?
    ),

    // Default settings (the setting values from the DB
    //   will override this values)
    'settings' => array(
        'foo' => 'bar',
    ),
);
