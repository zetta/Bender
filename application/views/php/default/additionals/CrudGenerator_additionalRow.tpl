_row.tpl

<tr>
{% for field in fields %}
    <td>{${{ bean }}->{{ field.getGetterName() }}()}</td>
{% endfor %}
    <td><a href="{url action=edit {{ primaryField.getVarName() }}=${{ bean }}->{{ primaryField.getGetterName() }}()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
    <td><a href="{url action=delete {{ primaryField.getVarName() }}=${{ bean }}->{{ primaryField.getGetterName() }}()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
</tr>
