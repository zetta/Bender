<form action="{url action=create}" method="post" class="validate ajaxForm">
<table class="center">
    <caption>{$i18n->_('Photo')}</caption>
    <tfoot>
        <tr>
            <td colspan="2"><input type="submit" value="{$i18n->_('Save')}" /></td>
        </tr>
    </tfoot>
    <tbody>
        {include file='photo/Form.tpl'}
    </tbody>
</table>
</form>
<hr/>


<table class="center">
    <caption>{$i18n->_('List')}</caption>
    <thead>
        <tr>
            <td>{$i18n->_('IdPhoto')}</td>
            <td>{$i18n->_('IdUser')}</td>
            <td>{$i18n->_('Title')}</td>
            <td>{$i18n->_('Uri')}</td>
            <td colspan="2">{$i18n->_('Actions')}</td>
        </tr>
    </thead>
    <tbody id="ajaxList">
        {foreach $photos as $photo}
            <tr class="{$photo@iteration|odd}">
                <td>{$photo->getIdPhoto()}</td>
                <td>{$photo->getIdUser()}</td>
                <td>{$photo->getTitle()}</td>
                <td>{$photo->getUri()}</td>
                <td><a href="{url action=edit idPhoto=$photo->getIdPhoto()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
                <td><a href="{url action=delete idPhoto=$photo->getIdPhoto()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
            </tr>
        {/foreach}
    </tbody>
</table>

