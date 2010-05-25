<?php 
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CacheController extends BenderController 
{

    /**
     * Limpia la carpeta `output` y el caché
     */
    public function clearAction()
    {
        $dumper = new BenderDumper();
        $dumper->deleteDirectoryContent('output');
        if(BenderRequest::getInstance()->getFlag('keep-autoloader') == FALSE)
          $dumper->deleteFile('cache/autoloadCache.file');
    }
    
}
