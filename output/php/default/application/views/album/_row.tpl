_row.tpl

<tr>
    <td>{$album->getIdAlbum()}</td>
    <td>{$album->getIdUser()}</td>
    <td>{$album->getTitle()}</td>
    <td><a href="{url action=edit idAlbum=$album->getIdAlbum()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
    <td><a href="{url action=delete idAlbum=$album->getIdAlbum()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
</tr>
