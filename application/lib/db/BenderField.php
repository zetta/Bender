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
 * Clase que representa un campo dentro de una tabla
 *
 */
class BenderField
{
  
  /**
   * @var boolean
   */
  private $required = false;
  
  /**
   * @var int
   */
  private $maxlength = 0;
  
  /**
   * @var data format
   */
  private $format = '';
  
  /**
   * @var int
   */
  private $minlength = 0;
  
  /**
   * @var string
   */
  private $type = '';
  
  /**
   * Nombre de la tabla a la que se va a consultar
   * @var string
   */
  private $name = '';
  
  /**
   * Determina si el campo es una llave unica
   * @var boolean
   */
  private $unique = false;
  
  /**
   * Default value
   * @var string|int
   */
  private $defaultValue = null;
  
  /**
   * Nombre que se mostrará en el setter
   * @var string
   */
  private $setterName = '';
  
  /**
   * Nombre que se mostrará en el getter
   * @var string
   */
  private $getterName = '';
  
  /**
   * Nombre a mostrar en las variables
   * @var string
   */
  private $varName = '';
  
  /**
   * Nombre del campo (simple)
   * @var string
   */
  private $simpleName = '';
  
  /**
   * Nombre que se utilizará en las constantes
   * @var string
   */
  private $constantName = '';
  
  /**
   * Tipo de dato que se está manejando
   * @var string
   */
  private $dataType = '';
  
  /**
   * Comentario del campo
   * @var string
   */
  private $comment = '';
  
  /**
   * Is foreign key
   * @var boolean
   */
  private $isFk = false;
  
  /**
   * Tipo de dato del campo sin  hacer alteraciones (como venia en la base de datos)
   * @var string
   */
  private $baseDataType = '';
  
  /**
   * Si el tipo de dato lo amerita, esta propiedad 
   * albergará el nombre de la clase al que se va a castear
   *
   * @var string $castDataType
   */
  private $castDataType = '';
  
  /**
   * Define si el campo es una llave primaria
   * @var boolean
   */
  private $primaryKey = false;
  
  /**
   * Nombre con el que se accesa al dato dentro de los accesos, 
   * Si se usan constantes en las preferencias, este atributo deberá almacenar un 
   * nombre de constante en caso contrario la constante de la tabla y el nombre del campo
   */
  private $catalogAccesor = '';
  
  /**
   * Nombre del getter que se usará en los catálogos, completo por si necesita usas objetos dentro de objetos =)
   */
  private $completeGetterName = '';
  
  /**
   * Nombre que empieza con mayusculas bueno para usarlo en metodos
   * @var string
   */
  private $upperCaseName = '';
  
  /**
   * Nombre de la tabla a la que pertenece el field
   * @var string
   */
  private $table = '';
  
  /**
   * Si es llave externa esta guardara el nombre del object
   * @var string
   */
  private $foreignObject = '';
  
  /**
   * Si es llave externa esta guardara el nombre del object
   * @var string
   */
  private $foreignLowerObject = '';
  
  /**
   * more options
   * @var array
   */
  private $options = array();
  
  
  /**
   * Constructor Class
   *
   * @param string $field
   * @return DbField
   */
  public function BenderField($field)
  {
    $this->name = $field;
  }
  
  /**
   * @return string
   */
  public function getBaseDataType()
  {
    return $this->baseDataType;
  }
  
  /**
   * @return string
   */
  public function getComment()
  {
    return $this->comment;
  }
  
  /**
   * @return string
   */
  public function getConstantName()
  {
    return $this->constantName;
  }
  
  /**
   * @return string
   */
  public function getDataType()
  {
    return $this->dataType;
  }
  
  /**
   * @return string
   */
  public function getGetterName()
  {
    return $this->getterName;
  }
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  


  /**
   * @return string
   */
  public function getVarName()
  {
    return $this->varName;
  }
  
  /**
   * @return boolean
   */
  public function isPrimaryKey()
  {
    return $this->primaryKey;
  }
  
  /**
   * @return string
   */
  public function getSetterName()
  {
    return $this->setterName;
  }
  
  /**
   * @param string $baseDataType
   */
  public function setBaseDataType($baseDataType)
  {
    $this->baseDataType = strtolower($baseDataType);
  }
  
  /**
   * @param string $comment
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }
  
  /**
   * @param string $constantName
   */
  public function setConstantName($constantName)
  {
    $this->constantName = $constantName;
  }
  
  /**
   * @param string $dataType
   */
  public function setDataType($dataType)
  {
    $this->dataType = strtolower($dataType);
  }
  
  /**
   * @param string $getterName
   */
  public function setGetterName($getterName)
  {
    $this->getterName = $getterName;
  }
  
  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  
  
  /**
   * @param string $varName
   */
  public function setVarName($varName)
  {
     $this->varName = $varName;
  }
  
  /**
   * @param boolean $primaryKey
   */
  public function setIsPrimaryKey($primaryKey)
  {
    $this->primaryKey = $primaryKey;
  }
  
  /**
   * @param string $setterName
   */
  public function setSetterName($setterName)
  {
    $this->setterName = $setterName;
  }
  
  /**
   * @return string
   */
  public function getCastDataType()
  {
    return $this->castDataType;
  }
  
  /**
   * @param string $castDataType
   */
  public function setCastDataType($castDataType)
  {
    $this->castDataType = strtolower($castDataType);
  }
  
  /**
   * @return string
   */
  public function getTable()
  {
    return $this->table;
  }
  
  /**
   * @param string $table
   */
  public function setTable($table)
  {
    $this->table = $table;
  }
  
  /**
   * @param string $accesor
   */
  public function setCatalogAccesor($accesor)
  {
    $this->catalogAccesor = $accesor;
  }
  
  /**
   * @return string
   */
  public function getCatalogAccesor()
  {
    return $this->catalogAccesor;
  }
  
  /**
   * @param string $completeGetterName
   */
  public function setCompleteGetterName($completeGetterName)
  {
    $this->completeGetterName = $completeGetterName;
  }
  
  /**
   * @return string 
   */
  public function getCompleteGetterName()
  {
    return $this->completeGetterName;
  }
  
  /**
   * @return string
   */
  public function getSimpleName()
  {
    return $this->simpleName;
  }
  
  /**
   * @param string $simpleName
   */
  public function setSimpleName($simpleName)
  {
    $this->simpleName = $simpleName;
  }
  
  /**
   * @return string
   */
  public function getUpperCaseName()
  {
    return $this->upperCaseName;
  }
  
  /**
   * @param string $upperCaseName
   */
  public function setUpperCaseName($upperCaseName)
  {
    $this->upperCaseName = $upperCaseName;
  }
  
  /**
   * @return boolean
   */
  public function isUnique()
  {
    return $this->unique;
  }
  
  /**
   * @param boolean $unique
   */
  public function setIsUnique($unique)
  {
    $this->unique = $unique;
  }
  
  /**
   * @return string|int
   */
  public function getDefaultValue()
  {
    return $this->defaultValue;
  }
  
  /**
   * @param string|int $defaultValue
   */
  public function setDefaultValue($defaultValue)
  {
    $this->defaultValue = $defaultValue;
  }
  
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
  
  /**
   * @param string $type
   */
  public function setType($type)
  {
    $this->type = strtolower($type);
  }
  
  /**
   * @return int
   */
  public function getMaxlength()
  {
    return $this->maxlength;
  }
  
  /**
   * @return int
   */
  public function getMinLength()
  {
    return $this->minlength;
  }
  
  /**
   * @return boolean
   */
  public function isRequired()
  {
    return $this->required;
  }
  
  /**
   * @param int $maxlength
   */
  public function setMaxlength($maxlength)
  {
    $this->maxlength = $maxlength;
  }
  
  /**
   * @param int $minlength
   */
  public function setMinlength($minlength)
  {
    $this->minlength = $minlength;
  }
  
  /**
   * @param boolean $required
   */
  public function setRequired($required)
  {
    $this->required = $required;
  }
  
  /**
   * @param string $foreignObject
   */
  public function setForeignObject($foreignObject)
  {
    $this->foreignObject = $foreignObject;
  }
  
  /**
   * @return string
   */
  public function getForeignObject()
  {
    return $this->foreignObject;
  }
  
  /**
   * @param string $foreignObject
   */
  public function setForeignLowerObject($foreignLowerObject)
  {
    $this->foreignLowerObject = $foreignLowerObject;
  }
  
  /**
   * @return string
   */
  public function getForeignLowerObject()
  {
    return $this->foreignLowerObject;
  }
  
  /**
   * @return the $format
   */
  public function getFormat() 
  {
    return $this->format;
  }

  /**
   * @param $format the $format to set
   */
  public function setFormat($format) 
  {
    $this->format = $format;
  }

  /**
   * @param boolean
   */
  public function setIsForeignKey($value)
  {
    $this->isFk = $value;
  }

  /**
   * @return boolean
   */
  public function isForeignKey()
  {
    return $this->isFk;
  }
  
  /**
   * set options
   * @param array $options
   */
  public function setOptions(array $options)
  {
    $this->options = $options;  
  }
  
  /**
   * get option
   * @param int|string $index
   * @return mixed
   */
  public function getOption($index)
  {
    return isset($this->options[$index]) ? $this->options[$index] : null;
  }
  
  /*
   * has options
   * @param int|string $index
   * @return boolean
   */
  public function hasOptions($index)
  {
    return !is_null( $this->getOption($index) );
  }
  
}
