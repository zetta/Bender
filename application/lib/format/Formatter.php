<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * Formatter
 *   - camelCase
 *   - UpperCamelCase
 *   - UPPER_CASE
 *   - under_score
 *   - slug-format
 */
final class Formatter
{
    /**
     * MY_PUBLIC_VAR >>  myPubliVar
     * @param string $string
     * @return string
     */
    public static function upperCaseToCammelCase($string)
    {
      return self::toCamelCase(strtolower($string),'_',false);
    }
    
    /**
     * MY_PUBLIC_VAR >> MyPublicVar
     * @param string $string
     * @return string
     */
    public static function upperCaseToUpperCammelCase($string)
    {
      return self::toCamelCase(strtolower($string),'_',true);
    }
    
    /**
     * MY_PUBLIC_VAR >> my_public_var
     * @param string $string
     * @return string
     */
    public static function upperCaseToUnderScore($string)
    {
      return strtolower($string);
    }
    
    /**
     * MY_PUBLIC_VAR >> my-public-var
     * @param string $string
     * @return string
     */
    public static function upperCaseToSlug($string)
    {
      return str_replace('_','-',strtolower($string));
    }
    
    /**
     * myPublicVar >> MY_PUBLIC_VAR
     * @param string $string
     * @return string
     */
    public static function camelCaseToUpperCase($string)
    {
      return strtoupper(self::toSeparatedString($string,'_'));
    }
    
    /**
     * myPublicVar >> my_public_var
     * @param string $string
     * @return string
     */
    public static function camelCaseToUnderscore($string)
    {
      return self::toSeparatedString($string,'_');
    }
        
    /**
     * myPublicVar >> MyPublicVar
     * @param string $string
     * @return string
     */
    public static function camelCaseToUpperCamelCase($string)
    {
      return ucfirst($string);
    }
    
    /**
     * myPublicVar >> my-public-var
     * @param string $string
     * @return string
     */
    public static function camelCaseToSlug($string)
    {
      return self::toSeparatedString($string,'-');
    }
    
    /**
     * MyPublicVar >> myPublicVar
     * @param string $string
     * @return string
     */
    public static function upperCamelCaseToCamelCase($string)
    {
      $string{0} = strtolower($string{0});
      return $string;
    }
    
    /**
     * MyPublicVar >> MY_PUBLIC_VAR
     * @param string $string
     * @return string
     */
    public static function upperCamelCaseToUpperCase($string)
    {
      return strtoupper(self::toSeparatedString($string,'_'));
    }
    
    /**
     * MyPublicVar >> my_public_var
     * @param string $string
     * @return string
     */
    public static function upperCamelCaseToUnderScore($string)
    {
      return self::toSeparatedString($string,'_');
    }
    
    /**
     * MyPublicVar >> my-public_var
     * @param string $string
     * @return string
     */
    public static function upperCamelCaseToSlug($string)
    {
      return self::toSeparatedString($string,'-');
    }
     
    /**
     * my_public_var >> myPublicVar
     * @param string $string
     * @return string
     */   
    public static function underScoreToCamelCase($string)
    {
      return self::toCamelCase($string,'_',false);
    }
    
    /**
     * my_public_var >> MyPublicVar
     * @param string $string
     * @return string
     */   
    public static function underScoreToUpperCamelCase($string)
    {
      return self::toCamelCase($string,'_',true);
    }
    
    /**
     * my_public_var >> MY_PUBLIC_VAR
     * @param string $string
     * @return string
     */   
    public static function underScoreToUpperCase($string)
    {
      return strtoupper($string);
    }
    
    /**
     * my_public_var >> my-public-var
     * @param string $string
     * @return string
     */   
    public static function underScoreToSlug($string)
    {
      return str_replace('_','-',$string);
    }
    
    /**
     * my-public-var >> MY_PUBLIC_VAR
     * @param string $string
     * @return string
     */
    public static function slugToUpperCase($string)
    {
      return str_replace('-','_',strtoupper($string));
    }
    
    /**
     * my-public-var >> MyPublicVar
     * @param string $string
     * @return string
     */
    public static function slugToUpperCamelCase($string)
    {
      return self::toCamelCase($string,'-',true);
    }
    
    /**
     * my-public-var >> myPublicVar
     * @param string $string
     * @return string
     */
    public static function slugToCamelCase($string)
    {
      return self::toCamelCase($string,'-',false);
    }
    
    /**
     * my-public-var >> my_public_var
     * @param string $string
     * @return string
     */
    public static function slugToUnderScore($string)
    {
      return str_replace('-','_',$string);
    }
        
    /**
     * Convierte un string a camelCase 
     * @param string $string (un-string/un_string/un.string)
     * @param string $separator [OPTIONAL]
     * @param boolean $first [OPTIONAL]
     */
    private static function toCamelCase($string, $separator = '-', $first = false)
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
    
    /**
     * Convierte un sring en camel case a uno separado por [separator]
     * @param string $string
     * @param string $separator
     */
    private static function toSeparatedString($string,$separator = '-')
    {
      $string[0] = strtolower($string[0]);
      $func = create_function('$c', 'return "'.$separator.'" . strtolower($c[1]);');
      return preg_replace_callback('/([A-Z])/', $func, $string);
    }
    
    /**
     * Limpia los comentarios (les quita los acentos para que no se muera esta cosa)
     *
     * @param string $comment
     * @return string $comment
     */
    public static function formatComment($string)
    {
      $string = strtr($string,
         "\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5".
         "\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed".
         "\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2".
         "\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa\xda".
         "\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf\xf8",
         "aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNo".
         "OoOoOoOoOoOoouUuUuUuUyYyaAso");
      return $string;
    }
    
    
    /**
     * Formatea un valor para ser escrito en el php
     *
     * @param mixed $value
     * @return string
     */
    public function formatValue($value)
    {
        if (is_bool($value))
        {
            $value = $value ? 'true' : 'false';
        } else if (is_string($value))
        {
            $value = "'{$value}'";
        } elseif (is_array($value))
        {
            $value = $this->getArrayString($value);
        }
        return $value;
    }
    
     /**
     * Obtiene la representación de un arreglo
     *
     * @param array $array
     * @return string
     */
    public function getArrayString($array)
    {
        $temporalArray = array();
        foreach ( $array as $index => $value )
        {
            $index = $this->formatValue($index);
            $value = $this->formatValue($value);
            $temporalArray[] = "{$index} => {$value}";
        }
        return "array(" . implode(",", $temporalArray) . ")";
    }
    
    
    
}


