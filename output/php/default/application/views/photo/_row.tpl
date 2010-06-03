_row.tpl

<tr>
    <td>{$photo->getIdPhoto()}</td>
    <td>{$photo->getIdUser()}</td>
    <td>{$photo->getTitle()}</td>
    <td>{$photo->getUri()}</td>
    <td><a href="{url action=edit idPhoto=$photo->getIdPhoto()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
    <td><a href="{url action=delete idPhoto=$photo->getIdPhoto()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
</tr>
