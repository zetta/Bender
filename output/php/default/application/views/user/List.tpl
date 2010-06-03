<form action="{url action=create}" method="post" class="validate ajaxForm">
<table class="center">
    <caption>{$i18n->_('User')}</caption>
    <tfoot>
        <tr>
            <td colspan="2"><input type="submit" value="{$i18n->_('Save')}" /></td>
        </tr>
    </tfoot>
    <tbody>
        {include file='user/Form.tpl'}
    </tbody>
</table>
</form>
<hr/>


<table class="center">
    <caption>{$i18n->_('List')}</caption>
    <thead>
        <tr>
            <td>{$i18n->_('IdUser')}</td>
            <td>{$i18n->_('IdPerson')}</td>
            <td>{$i18n->_('Username')}</td>
            <td>{$i18n->_('Password')}</td>
            <td colspan="2">{$i18n->_('Actions')}</td>
        </tr>
    </thead>
    <tbody id="ajaxList">
        {foreach $users as $user}
            <tr class="{$user@iteration|odd}">
                <td>{$user->getIdUser()}</td>
                <td>{$user->getIdPerson()}</td>
                <td>{$user->getUsername()}</td>
                <td>{$user->getPassword()}</td>
                <td><a href="{url action=edit idUser=$user->getIdUser()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
                <td><a href="{url action=delete idUser=$user->getIdUser()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
            </tr>
        {/foreach}
    </tbody>
</table>

