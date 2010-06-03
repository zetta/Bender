<?php 
/**
 * ZendFramework ModelMapper 
 * {{ description }}
 * {{ brandName }}
 *
 * @category   models
 * @package    models
 * @copyright  {{ copyright }}
 * @author     {{ author }}, $LastChangedBy$
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta
{% endif %}
 * @version    {{ version }} SVN: $Id$
 * @see http://framework.zend.com/manual/en/learning.quickstart.create-model.html
 */

class {{ DbTable }} extends Zend_Db_Table_Abstract
{

    /** 
     * Table name
     * @var string
     */
    protected $_name    = '{{ table.getTableName() }}';
}


