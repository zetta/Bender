<?php
/**
 * StringFormatter
 * @author Juan Carlos Jarquin
 */





class StringFormatter
{

        
    /**
     * Convierte un string a camelCase (para los nombres de controladores o acciones)
     * @param string $string
     * @param string $separator [OPTIONAL]
     * @param boolean $first [OPTIONAL]
     */
    public static function toCamelCase($string, $separator = '-', $first = false)
    {
        $parts = explode($separator, $string);
        $newString = '';
        $i = 0;
        foreach ( $parts as $part )
        {
            if ($i == 0 && ! $first)
                $newString = $part;
            else
                $newString .= ucfirst($part);
            $i ++;
        }
        return $newString;
    }
    
    
    
}


