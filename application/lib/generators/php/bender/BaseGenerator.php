<?php 
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */





abstract class BaseGenerator extends PhpGenerator
{

  public function start()
  {
    $this->view->mode = $this->request->getArg(101);
    $this->view->lang = $this->request->getArg(100);
    $this->view->Lang = ucfirst($this->request->getArg(100));
  }
  
  public function getFileName()
  {
      return $this->fileName;
  }

  /**
   * Sobreescribimos el metodo
   */
  public function end()
  {
    if($this->ignoreMe) return;
    $content = $this->view->render();
    $saver = new FileSaver();
    $saver->setEncoding(BenderSettings::getInstance()->getEncoding());
    if($this->fileName === null)
      throw new Exception("The generator must specify a destination file name ");
    $file = $this->getFileName();
    $saver->saveFile($file, $content);
  }

}











