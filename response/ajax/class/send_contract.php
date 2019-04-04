<?php

spl_autoload_register(function ($class_name) {
    //var_dump($class_name);
    
    switch ($class_name) {
        case 'sendsms':
                include $class_name . '.php';
        break;
        
    }    
});

?>