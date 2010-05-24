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
 * The code generable interace
 */
abstract class CodeGenerator 
{ 
 
  /**
   * @var 
   */
  protected $view;
  private $path;
  private $mode;
  private $lang;
  protected $fileName = null;
  protected $request = null;
  protected $dataTypes = array();
  protected $castDataTypes = array();
  protected $types = array();
  protected $ignoreMe = false;

  /**
   * 
   * @var BenderTable
   */
  protected $table;

  public function CodeGenerator()
  {
    $this->request = BenderRequest::getInstance();
  }

  
  /**
   * Initialize the class
   */
  final public function initialize($methodName)
  {
    $st = BenderSettings::getInstance();
    $class = new ReflectionClass($this);
    if($methodName == 'run')
      $this->view = BenderView::getInstance( $class->getName() , $this->path);
    else
      $this->view = BenderView::getInstance( $class->getName() . '_' . $methodName, $this->path);
    $this->view->copyright = $st->getCopyright();
    $this->view->brandName = $st->getBrandName();
    $this->view->description = $st->getDescription();
    $this->view->author = $st->getAuthor();
    $this->view->version = Bender::VERSION;
    $this->view->showBenderSignature = $st->addBenderSignature();
    $this->view->benderSignature = BENDER::SIGNATURE;
    $this->view->route = new Route();
    $this->view->formatter = new Formatter;
    $this->view->flags = $this->request->getFlags();
    $this->setupDataTypes();
  }
  
  /** 
   * change de data types of the used lang/mode
   */
  final private function setupDataTypes()
  {
    BenderDatabase::getInstance()->setUpLangDataTypes($this->dataTypes,$this->types,$this->castDataTypes,$this->formats);
  }
  
  /**
   * end	
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
    $saver->saveFile($this->lang.'/'.$this->mode .'/'.$file, $content);
  }
  
  /**
   *
   */
  public function invoke(ReflectionMethod $method)
  {
    return $method->invoke($this);
  }
  
  
  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
  }
  
  /**
   * 
   * @param BenderTable $table
   */
  public function setTable(BenderTable $table = null)
  {
    $this->table = $table;
  }
  
  public function setLang($lang)
  {
    $this->lang = $lang;
    return $this;
  }
  
  public function setMode($mode)
  {
    $this->mode = $mode;
    return $this;
  }
  
  /**
   * @param string $path
   */
  public function setPath($path)
  {
    $this->path = $path;
    return $this;
  }
  
  /**
   * Set the ignore flag on true
   */
  protected function ignore()
  {
    $this->ignoreMe = true;
  }
  


}
