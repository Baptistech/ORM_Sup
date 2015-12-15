<?php
/**
 * Created by PhpStorm.
 * User: baptiste
 * Date: 07/12/2015
 * Time: 18:20
 */

spl_autoload_register(function ($class) {
    echo $class;
    $class = str_replace('\\', '/', $class);
    echo $class;
    if (file_exists($class.'.php')) {
        require_once( $class . '.php');
    }
});