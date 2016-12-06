<?php
// DIC configuration

$container = $app->getContainer();



// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Register component on container
$container['view'] = function ($container) {
   $view = new \Slim\Views\Twig( __DIR__ .'/../views');

   // Instantiate and add Slim specific extension
   $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
   $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

   return $view;
};

// Database
$container['db'] = function ($c) {
   $db = $c['settings']['db'];
   $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
       $db['user'], $db['pass']);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   return $pdo;
};


 // User model
$container['user'] = function ($c) {
    return new \Ghysbrecht\Checkmein\Models\User($c['db']);
};

$container['session'] = function($c){
        return new \SlimSession\Helper;
};
