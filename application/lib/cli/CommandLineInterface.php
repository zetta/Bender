<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


// define stream constants
if (!defined('STDIN'))
  define('STDIN', fopen('php://stdin', 'r'));
if (!defined('STDOUT'))
  define('STDOUT', fopen('php://stdout', 'w'));
if (!defined('STDERR'))
  define('STDERR', fopen('php://stderr', 'w'));

class CommandLineInterface
{
    const SIZE = 80;
    
    /**
     * Styles And Colors
     */
    private $styles = array(
        'WARNING' => array('fg' => 'yellow', 'bold' => true), 
        'ERROR' => array('fg' => 'red', 'bold' => true), 
        'INFO' => array('fg' => 'green', 'bold' => true,), 
        'MESSAGE' => array('fg' => 'yellow','underscore' => true), 
        'SECTION' => array('fg' => 'magenta', 'bold' => true), 
        'COMMENT' => array('fg' => 'yellow'),
        'NOTE' => array('fg' => 'cyan'),
        'NONE' => array()
    );
    private $options = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8);
    private $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37);
    private $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);
    
    /**
     * Instancia de la clase
     * @var CommandLineInterface $instance
     */
    private static $instance = null;
    
    /**
     * Constructor privado 
     *
     * @return CommandLineInterface
     */
    protected function CommandLineInterface()
    {
        $this->fixCgi();
    }
    
    /**
     * Método para obtener la instancia del objeto
     * @return CommandLineInterface
     */
    public static function getInstance()
    {
        if (! isset(self::$instance))
        {
            self::$instance = new CommandLineInterface();
        }
        return self::$instance;
    }
        
    /**
     * Renders an exception.
     *
     * @param Exception $e An exception object
     */
    public function renderException(Exception $e)
    {
        if(BenderRequest::getInstance()->getFlag('debug'))
          $this->printMessage($e,'ERROR');
        else
          $this->printMessage($e->getMessage(),'ERROR');
        $this->out(STDOUT,"\n");
    }
    
    
    
    /**
     * Fixes php behavior if using cgi php.
     *
     * @see http://www.sitepoint.com/article/php-command-line-1/3
     */
    private function fixCgi()
    {
        // PHP ini settings
        set_time_limit(0);
        ini_set('track_errors', true);
        ini_set('html_errors', false);
        ini_set('magic_quotes_runtime', false);
        
        if (false === strpos(PHP_SAPI, 'cgi'))
        {
            return;
        }
        
        // change directory
        if (isset($_SERVER['PWD']))
        {
            chdir($_SERVER['PWD']);
        }
        
        // close the streams on script termination
        register_shutdown_function(create_function('', 'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;'));
    }
    
    /**
     * Returns true if the stream supports colorization.
     *
     * Colorization is disabled if not supported by the stream:
     *
     *  -  windows
     *  -  non tty consoles
     *
     * @param  mixed  $stream  A stream
     *
     * @return Boolean true if the stream supports colorization, false otherwise
     */
    public function supportsColors($stream)
    {
        if(BenderRequest::getInstance()->getFlag('no-color')) return false;
        return DIRECTORY_SEPARATOR != '\\' && function_exists('posix_isatty') && @posix_isatty($stream);
    }
    
    /**
     * Formats a text according to the given style or parameters.
     *
     * @param  string   $text       The test to style
     * @param  mixed    $parameters An array of options or a style name
     * @param  resource $stream     The stream to format for
     *
     * @return string The styled text
     */
    public function format($text = '', $parameters = array(), $stream = STDOUT)
    {
        if (! $this->supportsColors($stream))
        {
            return $text;
        }
        
        if (! is_array($parameters) && 'NONE' == $parameters)
        {
            return $text;
        }
        
        if (! is_array($parameters) && isset($this->styles[$parameters]))
        {
            $parameters = $this->styles[$parameters];
        }
        
        $codes = array();
        if (isset($parameters['fg']))
        {
            $codes[] = $this->foreground[$parameters['fg']];
        }
        if (isset($parameters['bg']))
        {
            $codes[] = $this->background[$parameters['bg']];
        }
        foreach ( $this->options as $option => $value )
        {
            if (isset($parameters[$option]) && $parameters[$option])
            {
                $codes[] = $value;
            }
        }
        
        return "\033[" . implode(';', $codes) . 'm' . $text . "\033[0m";
    }
    
    /**
     * Formats a message within a section
     *
     * @param string  $section  The section name
     * @param string  $text     The text message
     * @param string  $style    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param string  $style    The color scheme to apply to the section string (INFO, ERROR, or COMMENT)
     * @param integer $size     The maximum size allowed for a line (65 by default)
     * @return string the message
     */
    public function formatSection($section, $text, $styleText = 'COMMENT',$styleSection = 'SECTION',$size = null)
    {
        $styleSection = ! array_key_exists($styleSection, $this->styles) ? 'SECTION' : $styleSection;
        $styleText = ! array_key_exists($styleText, $this->styles) ? 'INFO' : $styleText;
        $width = 10 + strlen($this->format('', $styleSection));
        return sprintf("> %-${width}s %s", $this->format($section, $styleSection), $this->format($this->excerpt($text, $size,$styleText), $styleText))."\n";
    }
    
    /**
     * prints a formated message within a section
     *
     * @param string  $section      The section name
     * @param string  $text         The text message
     * @param string  $styleText    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param string  $styleSection The color scheme to apply to the section string (INFO, ERROR, or COMMENT)
     * @param integer $size         The maximum size allowed for a line (65 by default)
     */
    public function printSection($section, $text, $styleText = 'COMMENT',$styleSection = 'SECTION',$size = null)
    {
        $this->out(STDOUT,$this->formatSection($section,$text,$styleText,$styleSection,$size));
    }
    
    /**
     * prints a formated message 
     *
     * @param string  $text     The text message
     * @param string  $style    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param integer $size     The maximum size allowed for a line (65 by default)
     */
    public function printMessage($text, $style = 'MESSAGE', $size = null)
    {
        $this->out(STDOUT,$this->formatMessage($text,$style,$size));
    }
    
    /**
     * format a text
     *
     * @param string  $text     The text message
     * @param string  $style    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param integer $size     The maximum size allowed for a line (65 by default)
     */
    public function formatMessage($text, $style = 'MESSAGE')
    {
        $style = ! array_key_exists($style, $this->styles) ? 'NOTE' : $style;
        return sprintf("  %s", $this->format($text, $style));
    }
    
    /**
     * prints a formated message 
     *
     * @param string  $text     The text message
     * @param string  $style    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param integer $size     The maximum size allowed for a line (65 by default)
     */
    public function printText($text, $style = 'MESSAGE', $size = null)
    {
        $this->out(STDOUT,$this->formatText($text,$style,$size));
    }
    
    /**
     * format a text
     *
     * @param string  $text     The text message
     * @param string  $style    The color scheme to apply to the text string (INFO, ERROR, or COMMENT)
     * @param integer $size     The maximum size allowed for a line (65 by default)
     */
    public function formatText($text, $style = 'MESSAGE')
    {
        $style = ! array_key_exists($style, $this->styles) ? 'NOTE' : $style;
        return sprintf("%s", $this->format($text, $style));
    }
    
    /**
     * Hace una pregunta al usuario y espera la respuesta
     */
    public function prompt($txt)
    {
        $this->printText($txt);
        return trim(fgets(STDIN));
    }
    
    /**
     * Output a string
     */
    private function out($resource,$string)
    {
        if(!BenderRequest::getInstance()->getFlag('quiet'))
          fwrite($resource,$string);
    }
    
    
    /**
     * Truncates a line.
     *
     * @param string  $text The text
     * @param integer $size The maximum size of the returned string (80 by default)
     * @param string $style
     * @return string The truncated string
     */
    public function excerpt($text, $size = null,$style = 'INFO')
    {
        if (! $size)
        {
            $size = CommandLineInterface::SIZE;
        }
        if( BenderRequest::getInstance()->getFlag('no-truncate-text') )
           $size = 99999;
           
        if (strlen($text) < $size)
        {
            return $text;
        }
        
        $subsize = floor(($size - 3) / 2);
        
        return substr($text, 0, $subsize) . $this->format('...', 'INFO') . $this->format(substr($text, - $subsize), $style);
    }
}
