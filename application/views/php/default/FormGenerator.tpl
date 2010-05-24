{% if primaryField %}
<tr>
    <th>{$i18n->_('{{ primaryField.getSimpleName() }}')}</th>
    <td># {$post['{{ primaryField.getSimpleName() }}']}<input type="hidden" name="{{ primaryField.getSimpleName() }}" id="{{ primaryField.getSimpleName() }}" value="{$post['{{ primaryField.getSimpleName() }}']}" /></td>
</tr>
{% endif  %}
{% for field in foreignKeys %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td>{html_options name={{ field.getSimpleName() }} id={{ field.getSimpleName() }} options=${{ field.getForeignObject() }}s selected=$post['{{ field.getSimpleName() }}'] }</td>
</tr>
{% endfor %}
{% for field in nonForeignKeys %}
{% if field.getBaseDataType() == 'int' %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td><input type="text" name="{{ field.getSimpleName() }}" id="{{ field.getSimpleName() }}" value="{$post['{{ field.getSimpleName() }}']}" class="number{% if field.isRequired() == true %} required{% endif %}" /></td>
</tr>
{% elseif field.getBaseDataType() == 'text' %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td><textarea name="{{ field.getSimpleName() }}" id="{{ field.getSimpleName() }}" class="{% if field.isRequired() == true %} required{% endif %}">{$post['{{ field.getSimpleName() }}']}</textarea></td>
</tr>
{% elseif field.getBaseDataType() == 'date' or field.getBaseDataType() == 'timestamp' %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td><input type="text" name="{{ field.getSimpleName() }}" id="{{ field.getSimpleName() }}" value="{$post['{{ field.getSimpleName() }}']}" class="datePicker dateISO{% if field.isRequired() == true %} required{% endif %}" /></td>
</tr>
{% elseif field.getBaseDataType() == 'tinyint' %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td><input type="checkbox" name="{{ field.getSimpleName() }}" id="{{ field.getSimpleName() }}" value="1" class="{% if field.isRequired() == true %} required{% endif %}" {if $post['{{ field.getSimpleName() }}']}checked="checked"{/if} /></td>
</tr>
{% elseif field.getBaseDataType() == 'time' %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td>{html_select_time prefix={{ field.getSimpleName() }} display_seconds=false display_meridian=false time=$post['{{ field.getSimpleName() }}']}</td>
</tr>
{% else %}
<tr>
    <th>{$i18n->_('{{ field.getSimpleName() }}')}</th>
    <td><input type="text" name="{{ field.getSimpleName() }}" id="{{ field.getSimpleName() }}" value="{$post['{{ field.getSimpleName() }}']}" class="{% if field.isRequired() == true %} required{% endif %}" /></td>
</tr>
{% endif %}
{% endfor %}

<!--
{% for field in table.getFields() %}
{% if field.getBaseDataType() == 'tinyint' %}
${{ field.getVarName()}} = $this->getRequest()->getParam('{{ field.getSimpleName()}}', 0);
{% else %}
${{ field.getVarName()}} = $this->getRequest()->getParam('{{ field.getSimpleName()}}');
{% endif %}
{% endfor %}
-->

<!--
$post = array(
{% for field in table.getFields() %}
    '{{ field.getSimpleName()}}' => ${{ table.getLowerObject() }}->{{ field.getGetterName() }}(),
{% endfor %}
);
-->
