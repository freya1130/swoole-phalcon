<?php
/**
 * File Description
 * User: Kp
 * Date: 2015/10/21
 * Time: 15:00
 */
define('ROOT_PATH', dirname(__DIR__));
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    ROOT_PATH . '/application/controller/',
    ROOT_PATH . '/application/model/'
));
$loader->register();
$di = new \Phalcon\Di\FactoryDefault();
//Registering the view component
$di->set('view', function(){
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir(ROOT_PATH . '/application/views/');
    return $view;
});

try {
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo $e->getMessage();
}