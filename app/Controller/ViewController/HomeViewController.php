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

use Controller\ServiceController\B64nmbController;

class HomeViewController extends ViewController
{
    /**
     * Handle GET-Request
     * @param Base $f3
     */
    public function get($f3)
    {

    }
    
    /**
     * Handle POST-Request
     * @param Base $f3
     */    
    public function post($f3)
    {
        $url = $this->sanitize($f3->get('POST.url'));
        
        $b64nmb = new B64nmbController; 
        $errorcounter = 5;
                
        do {
            $token = $b64nmb->generateRandom(6)->export();   

            $tokenfound = $this->db->exec(
                    'SELECT id FROM entries WHERE id =?',
                    $token
            );          
            
            $errorcounter--;
        } while ($tokenfound && $errorcounter > 0);
        
        if ($errorcounter == 0) {
            $f3->set('SESSION.errormsg', $f3->get('DICT.couldnotcreate'));
            $f3->reroute("/");            
        }
        
        $result = $this->db->exec(
            'INSERT INTO entries (id, url, datetime) VALUES (?, ?, ?)',
            array($token, $url, date('Y-m-d h:i:s'))
        );
        
        if ($result) {
            $f3->set('SESSION.token', $token);      
        } else {          
            $f3->set('SESSION.errormsg', $f3->get('DICT.couldnotcreate'));
            $f3->reroute("/");
        }
        
    }
    
    /**
     * Sanitize unsecure URL received via POST
     * @param string $posturl
     */    
    protected function sanitize($posturl)
    {
        $f3 = \Base::instance();        
        
        $url = filter_var($posturl, FILTER_SANITIZE_URL);
        
        if (substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://') {
            $url = 'http://' . $url;
        }
        
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $f3->set('SESSION.errormsg', $f3->get('DICT.invalidurl'));
            
            $f3->reroute("/");
        }        
        
        return $url;
    }
}
