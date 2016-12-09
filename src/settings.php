<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../template/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        //Databse settings
        'db' => [
            'host' => getenv("DB_HOST"),
            'user' => getenv("DB_USER"),
            'pass' => getenv("DB_PASS"),
            'dbname' => getenv('DB_NAME'),
            'driver' => getenv('DB_DRIVER')
        ],
    ],
];
