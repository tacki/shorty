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
namespace Controller\ServiceController;

class DatabaseController 
{   
    public static function init()
    {
        $f3 = \Base::instance();
        
        $dbdsn  = getenv('SHORTY_DATABASE_DSN')? 
                    getenv('SHORTY_DATABASE_DSN') :
                    $f3->get('DATABASE.DSN');
        $dbtype = $f3->get('DATABASE.TYPE');      
        $dbhost = $f3->get('DATABASE.HOST');
        $dbport = $f3->get('DATABASE.PORT');
        $dbname = $f3->get('DATABASE.DBNAME');
        $dbuser = getenv('SHORTY_DATABASE_USERNAME') ? 
                    getenv('SHORTY_DATABASE_USERNAME') : 
                    $f3->get('DATABASE.USERNAME');
        $dbpass = getenv('SHORTY_DATABASE_PASSWORD_FILE') ? 
                    trim(file_get_contents(getenv('SHORTY_DATABASE_PASSWORD_FILE'))) :
                    $f3->get('DATABASE.PASSWORD');

        if ($dbtype == 'sqlite') {
            $connstr = $dbtype.":".$dbpath;
        } else {
            $connstr = $dbtype.":".
                       "host=".$dbhost.";".
                       "port=".$dbport.";".
                       "dbname=".$dbname.";";    
        }
        
        $db =  new \DB\SQL(
            $dbdsn ? $dbdsn : $connstr,
            $dbuser,
            $dbpass
        );        
        
        if (!$db->exec("SHOW DATABASES LIKE '$dbname';")) {
            throw new \Exception("Database '$dbname' not found");
        }

        return $db;
    }
}

