<?php
    // load f3
    $f3 = require('../vendor/f3/base.php');
    
    // autoloader
    $f3->set('AUTOLOAD','../app/');
    
    // load configs
    $f3->config('../app/config/config.ini', false);
    $f3->config('../app/config/routing.ini', true);
           
    // Database init 
    try {        
        $f3->set('DB', Controller\ServiceController\DatabaseController::init());
        
        if (!$f3->get('DB')->exists('entries')) {
            $f3->map('/', '\Controller\ViewController\InstallViewController');
        }  
    } catch(\Exception $e) {
        $f3->set('SESSION.errormsg', $e->getMessage());
    }

    // i18n
    $f3->set('LOCALES','../app/dict/');
    $f3->set('PREFIX', 'DICT');
    
    // run
    try {     
        $f3->run();      
    } catch (Exception $e) {
        echo "An error occured (".$e->getMessage()." [".$e->getCode()."]). Please try again!";
    }

