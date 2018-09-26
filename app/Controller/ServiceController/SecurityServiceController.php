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

class SecurityServiceController 
{
    /**
     * Check if the F3 Params are correct
     * @return boolean
     */
    public function checkParams()
    {
        $f3 = \Base::instance();
        
        // params checking
        foreach($f3->get('PARAMS') as $param => $value) {
            if ($f3->get('params.'.$param) && !preg_match("/^".$f3->get('params.'.$param)."$/", $value)) {
                // Bad Parameter - possible hacking attempt
                return false;                
            }
        }        
        
        return true;
    }
}
