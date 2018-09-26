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

class B64nmbController
{
    private $converter;
    
    public static $codes = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_";   
    private $bNmb = ''; 
    
    public function __construct() 
    {
        $this->converter = new B64nmbConvert;
    }
    
    public function generateRandom($length) 
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::$codes[rand(0, strlen(self::$codes) - 1)];
        }
        
        $this->setValue($randomString);
        
        return $this;        
    }
    
    public function generateUniqID()
    {
        $uniqid = uniqid("");
        $uniqid_b64 = $this->converter->hex2B64($uniqid);
        
        $this->setValue($uniqid_b64);
        
        return $this;
    }
    
    public function export()
    {
        return $this->bNmb;
    }    
    
    public function import($b64Nmb)
    {
        $this->setValue($b64Nmb);
        
        return $this;
    }

    public function importHex($hex)
    {
        $this->setValue($this->converter->hex2B64($hex));
        
        return $this;
    }    
    
    public function importDecimal($decimal)
    {
        $this->setValue($this->converter->dec2B64($decimal));
        
        return $this;
    }    
    
    public function importOctal($octal)
    {
        $this->setValue($this->converter->oct2B64($octal));
        
        return $this;
    }      
    
    public function importBinary($binary)
    {
        $this->setValue($this->converter->binary2B64($binary));
        
        return $this;
    }    

    public function exportHex()
    {       
        return $this->converter->b642Hex($this->bNmb);
    }   
    
    public function exportDecimal()
    {
        return $this->converter->b642Dec($this->bNmb);
    }    
    
    public function exportOctal()
    {
        return $this->converter->b642Oct($this->bNmb);
    }
    
    public function exportBinary()
    {
        return $this->converter->b642Binary($this->bNmb);
    }
            
    private function setValue($string)
    {
        $this->bNmb = $string;
    }
}

/**
 * Description of B64nmbConvert
 *
 * @author Markus Schlegel
 */
class B64nmbConvert
{    
    // -----------------------------------------------------------------------
    // B64 Conversion functions
    
    public function b642Hex($b64)
    {
        $binary = $this->b642Binary($b64);
        
        return $this->binary2Hex($binary);      
    }     
    
    public function b642Dec($b64)
    {
        $binary = $this->b642Binary($b64);
        
        return $this->binary2Dec($binary);
    }
    
    public function b642Oct($b64)
    {
        $binary = $this->b642Binary($b64);
        
        return $this->binary2Oct($binary);
    }
        
    
    public function b642Binary($b64)
    {
        $binary = "";
        $digits = str_split((string)$b64);
        
        $codepos = array_flip(str_split(B64nmbController::$codes));
        
        foreach ($digits as $digit) {
            $binary .= sprintf("%'.06b", $codepos[$digit]);
        }
        
        return $binary;        
    }
    
    // -----------------------------------------------------------------------    
    // Hex Conversion functions    
    
    public function hex2B64($hex)
    {
        $binary = $this->hex2Binary($hex);
        
        return $this->binary2B64($binary);
    }
    
    public function hex2Dec($hex)
    {
        $binary = $this->hex2Binary($hex);
        
        return $this->binary2Dec($binary);
    }
    
    public function hex2Binary($hex)
    {
        $binary = "";
        $digits = str_split((string)$hex);
        
        foreach ($digits as $digit) {
            $binary .= sprintf("%'.04b", hexdec($digit));
        }
        
        return $binary;
    }    
    
    // -----------------------------------------------------------------------  
    // Decimal Conversion functions    
    
    public function dec2B64($dec)
    {
        $binary = $this->dec2Binary($dec);
        
        return $this->binary2B64($binary);
    }
    
    public function dec2Hex($dec)
    {        
        return dechex($dec);
    }    
    
    public function dec2Binary($dec)
    {
        return decbin($dec);
    }    
    
    // -----------------------------------------------------------------------  
    // Octal Conversion functions    
    
    public function oct2B64($oct)
    {
        $binary = $this->oct2Binary($oct);
        
        return $this->binary2B64($binary);
    }
    
    public function oct2Hex($oct)
    {        
        return dechex(octdec($oct));
    }    
    
    public function oct2Binary($oct)
    {
        return decbin(octdec($oct));
    }       
    
    // -----------------------------------------------------------------------
    // Binary Conversion functions    
        
    public function binary2B64($binary)
    {
        $b64 = "";        
        $digits = str_split(strrev((string)$binary), 6);    
        
        $codes = str_split(B64nmbController::$codes);
        
        foreach ($digits as $digit) {
            $b64 .= $codes[bindec(strrev($digit))];
        }
        
        // revert string again (highest-bit first) and remove leading 0s
        return ltrim(strrev($b64), '0');
    }    
    
    public function binary2Hex($binary)
    {
        $hex = "";
        $digits = str_split(strrev((string)$binary), 4);
        
        foreach ($digits as $digit) {
            $hex .= dechex(bindec(strrev($digit)));
        }
        
        // revert string again (highest-bit first) and remove leading 0s
        $result = ltrim(strrev($hex), '0');
        
        // Return Hex as Uppercase
        return strtoupper($result);        
    }
    
    public function binary2Dec($binary)
    {        
        return bindec($binary);
    }
    
    public function binary2Oct($binary)
    {
        $hex = "";
        $digits = str_split(strrev((string)$binary), 3);
        
        foreach ($digits as $digit) {
            $hex .= decoct(bindec(strrev($digit)));
        }
        
        // revert string again (highest-bit first) and remove leading 0s
        $result = ltrim(strrev($hex), '0');
        
        // Return Hex as Uppercase
        return strtoupper($result);  
    }
           
}
