<?php
/**
 * ProgressBar
 * @author Juan Carlos Jarquin
 */

/**
 * Clase ProgressBar que genera una barra de progreso para las ejecuciones mediante consola
 *
 */
class ProgressBar
{
    /**
     * Total de items por recorrer
     * @var int
     */
    private static $total;
    
    /**
     * Stepper
     * @var int
     */
    private static $increment;
    
    /**
     * Tiempo que consume el script
     * @var int
     */
    private static $time;
    
    /**
     * Conteo actual
     * @var int
     */
    private static $count;
    
    /**
     * El microtime que tiene el item al iniciar
     * @var int
     */
    private static $itemStart;
    
    /**
     * starts a CLI progress par counter
     *
     * @param int $total
     * @param int [OPTIONAL] $increment
     */
    public static function startUp($total, $increment = 0) {
        self::$total = $total;
        if ($increment) {
            self::$increment = $increment;
        } else {
            $increment = round($total/100);
            if ($increment < 1) {
                $increment = 1;
            }
            self::$increment = $increment;
            
        }
        self::$time = 0;
        self::$count = 0;
    }
    
    /**
     * To be included at the bottom of the operational loop
     */
    public static function step() {
        if (self::$count > 1) {
            $item_end = microtime(true);
            $item_time = $item_end - self::$itemStart;
            self::$time += $item_time;
            
        }
        
        self::$itemStart = microtime(true);
        self::$count++;
        if (self::$count % self::$increment == 0) {
            
            $numpercent = round(self::$count / self::$total * 100);
            $percent = str_pad($numpercent,3,' ', STR_PAD_LEFT);
            $avg_time = self::$time / self::$count;
            $remaining_time = round((self::$total - self::$count) * $avg_time).' seconds remain ('.self::$count.'/'.self::$total.')';
            $remaining_time = str_pad($remaining_time, 25, ' ', STR_PAD_RIGHT);
            $string = substr("$percent% - $remaining_time",0,35);
            $string = str_pad($string, 35, ' ', STR_PAD_RIGHT);
            $stars = '';
            for ($i=0; $i < $percent; $i++) { 
                $stars .= '*';
            }
            $stars = '|'.str_pad($stars, 100, ' ', STR_PAD_RIGHT).'|';
            fwrite(STDOUT, ' '.$stars.$string);
            for ($i=0; $i < 138; $i++) {
                fwrite(STDOUT, "\010");
            }
        }
        ob_flush();
    }
    
    
}

