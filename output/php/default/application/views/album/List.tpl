<form action="{url action=create}" method="post" class="validate ajaxForm">
<table class="center">
    <caption>{$i18n->_('Album')}</caption>
    <tfoot>
        <tr>
            <td colspan="2"><input type="submit" value="{$i18n->_('Save')}" /></td>
        </tr>
    </tfoot>
    <tbody>
        {include file='album/Form.tpl'}
    </tbody>
</table>
</form>
<hr/>


<table class="center">
    <caption>{$i18n->_('List')}</caption>
    <thead>
        <tr>
            <td>{$i18n->_('IdAlbum')}</td>
            <td>{$i18n->_('IdUser')}</td>
            <td>{$i18n->_('Title')}</td>
            <td colspan="2">{$i18n->_('Actions')}</td>
        </tr>
    </thead>
    <tbody id="ajaxList">
        {foreach $albums as $album}
            <tr class="{$album@iteration|odd}">
                <td>{$album->getIdAlbum()}</td>
                <td>{$album->getIdUser()}</td>
                <td>{$album->getTitle()}</td>
                <td><a href="{url action=edit idAlbum=$album->getIdAlbum()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
                <td><a href="{url action=delete idAlbum=$album->getIdAlbum()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
            </tr>
        {/foreach}
    </tbody>
</table>

