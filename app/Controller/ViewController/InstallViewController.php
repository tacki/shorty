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

class InstallViewController extends ViewController
{
    /**
     * Handle GET-Request
     * @param Base $f3
     */
    public function get($f3)
    {

    }
    
    public function post($f3)
    {
        $db = $f3->get('DB');
        
        $db->begin();
        if ($db->exec(file_get_contents('../app/db/0.1_'.$f3->get('DB')->driver().'.sql')) !== false) {
            $f3->set('INSTALL_success', true);
            $db->commit();
        } else {
            $f3->set('INSTALL_failure', true);
            $db->rollback();
        }        
    }
}    