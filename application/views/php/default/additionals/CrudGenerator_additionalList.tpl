<form action="{url action=create}" method="post" class="validate ajaxForm">
<table class="center">
    <caption>{$i18n->_('{{ Bean }}')}</caption>
    <tfoot>
        <tr>
            <td colspan="2"><input type="submit" value="{$i18n->_('Save')}" /></td>
        </tr>
    </tfoot>
    <tbody>
        {include file='{{ bean }}/Form.tpl'}
    </tbody>
</table>
</form>
<hr/>


<table class="center">
    <caption>{$i18n->_('List')}</caption>
    <thead>
        <tr>
{% for field in fields %}
            <td>{$i18n->_('{{ field.getUpperCaseName() }}')}</td>
{% endfor %}
            <td colspan="2">{$i18n->_('Actions')}</td>
        </tr>
    </thead>
    <tbody id="ajaxList">
        {foreach ${{ bean }}s as ${{ bean }}}
            <tr class="{${{ bean }}@iteration|odd}">
{% for field in fields %}
                <td>{${{ bean }}->{{ field.getGetterName() }}()}</td>
{% endfor %}
                <td><a href="{url action=edit {{ primaryField.getVarName() }}=${{ bean }}->{{ primaryField.getGetterName() }}()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
                <td><a href="{url action=delete {{ primaryField.getVarName() }}=${{ bean }}->{{ primaryField.getGetterName() }}()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
            </tr>
        {/foreach}
    </tbody>
</table>

