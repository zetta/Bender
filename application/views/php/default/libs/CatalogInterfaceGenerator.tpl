<?php
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
 * @author    zetta
 * @version    {{ version }} SVN: $Id$
 */

/**
 * Interfaz Catalog
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
interface CatalogInterface 
{
    /**
     * Método público abstracto que permite tener solo una instancia del catálogo
     */
    public static function getInstance();


    /**
     * Guarda en la base de datos
     * @param mixed Un bean para guardar
     */
    public function create($object);
    
    /**
     * Actualiza el objeto en la base de datos
     * @param mixed Un bean para actualizar
     */
    public function update($object);
    
    /**
     * Elimina de  la base de datos
     * @param mixed El bean que se va a eliminar
     */
    public function delete($object);
    
    /**
     * Elimina de la base de datos por medio de llave primaria
     * @param int $idObject El id del bean que se borrará
     */
    public function deleteById($idObject);
    
    /**
     * Obtiene un BEAN de la base de datos
     * @param int $idObject 
     * @return mixed EL BEAN
     */
    public function getById($idObject);
    
    /**
     * Obtiene un arreglo de todos los ids =S
     * @return array
     */
    public function retrieveAllIds();
    
  
    /**
     * Obtiene un arreglo de ids determinados por un criteria
     * @param Criteria $criteria
     * @return array 
     */
    public function getIdsByCriteria(Criteria $criteria = null);
    
    /**
     * Obtiene un BEAN por el criteria especificado
     * @param Criteria $criteria
     * @return mixed EL BEAN
     */
    public function getByCriteria(Criteria $criteria = null);
    
    /**
     * Obtiene un arreglo de valores establecidos por el usuario
     * @param Criteria $criteria
     * @param string $field Nombre del campo
     * @return array
     */
    public function getCustomFieldByCriteria($field, Criteria $criteria = null);
    

{% if flags["use-behaviors"] %}
  
    /**
     * Agrega un BehaviorObserver a la lista de observers del catálogo
     * @param BehaviorObserver $behaviorObserver El objeto observer que estará escuchando lo que haga el catálogo
     */
    public function addObserver( BehaviorObserver $behaviorObserver);
    
    /**
     * Notifica a los observers de los eventos del catálogo
     * @param mixed $object El bean que disparó el evento
     * @param int $event 
     */
    public function notifyObservers($object, $event);
    
{% endif %}
}


