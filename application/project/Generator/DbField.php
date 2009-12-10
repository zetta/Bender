<?php
/**
 * DbField
 * @author Juan Carlos Jarquin
 */

/**
 * Clase que representa un campo dentro de una tabla
 *
 */
class DbField
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
   * @var int
   */
  private $minlength = 0;
  
  /**
   * @var string
   */
  private $dataTypeValidator = NULL;
  
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
  private $phpName = '';
  
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
   * Tipo de dato del campo sin  hacer alteraciones
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
   * Constructor Class
   *
   * @param string $field
   * @return DbField
   */
  public function DbField($field)
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
  public function getPhpName()
  {
    return $this->phpName;
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
    $this->baseDataType = $baseDataType;
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
    $this->dataType = $dataType;
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
   * @param string $phpName
   */
  public function setPhpName($phpName)
  {
    $this->phpName = $phpName;
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
    $this->castDataType = $castDataType;
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
  public function getDataTypeValidator()
  {
    return $this->dataTypeValidator;
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
  public function getMinlength()
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
   * @param string $dataTypeValidator
   */
  public function setDataTypeValidator($dataTypeValidator)
  {
    $this->dataTypeValidator = $dataTypeValidator;
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

  
}
