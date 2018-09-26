<?php
/*
 * Copyright (C) 2015 Markus Schlegel <g42@gmx.net>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Controller\ViewController;

use Controller\ServiceController\SecurityServiceController;

class ViewController
{    
    /**
     * @var DB\SQL
     */
    public $db;    
    
    /**
     * @var SecurityServiceController 
     */
    protected $security;    
    
    
    public function __construct()
    {
        $this->db = \Base::instance()->get('DB');
        $this->security = new SecurityServiceController;        
    }

    /**
     * Before Routing Event
     * Apply some security
     */    
    public function beforeroute()
    {
        $f3 = \Base::instance();

        // params checking
        if(!$this->security->checkParams()) {
            // Bad Parameter - possible hacking attempt
            $f3->set('SESSION.errormsg', $f3->get('DICT.urlunknown'));
            $f3->reroute('/');                
        }
    }
 
    /**
     * After Routing Event
     * Render the Page
     */       
    public function afterroute() 
    {        
        $f3 = \Base::instance();
        
        $classPath = explode('\\', get_called_class());

        $calledClass = substr(end($classPath), 0, strpos(end($classPath), "Controller"));
        
        $f3->set('content',$calledClass.'\\'.$calledClass.'.phtml');
        
        // Render HTML layout
        echo \View::instance()->render('layout.phtml');
    }       
}
