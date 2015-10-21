<?php

/**
 * File Description
 * User: Kp
 * Date: 2015/10/21
 * Time: 14:07
 */
class HomeController extends \Phalcon\Mvc\Controller
{
    public function indexAction(){
        echo 111;
    }

    public function helloAction(){
        print_r($_GET);
        echo 'hello world';
    }
}