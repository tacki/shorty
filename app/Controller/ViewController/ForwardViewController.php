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

class ForwardViewController extends ViewController
{

    /**
     * Handle GET-Request
     * @param Base $f3
     */    
    public function get($f3)
    {
        $result = $this->db->exec(
            'SELECT url FROM entries WHERE id=?',
            $f3->get('PARAMS.token')
        );
        
        if (isset($result[0]['url'])) {        
            $f3->reroute($result[0]['url']);
        } else {
            $f3->set('SESSION.errormsg', $f3->get('DICT.urlunknown'));
            $f3->reroute("/", true);
        }
    }
}
