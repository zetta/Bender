_row.tpl

<tr>
    <td>{$user->getIdUser()}</td>
    <td>{$user->getIdPerson()}</td>
    <td>{$user->getUsername()}</td>
    <td>{$user->getPassword()}</td>
    <td><a href="{url action=edit idUser=$user->getIdUser()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
    <td><a href="{url action=delete idUser=$user->getIdUser()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
</tr>
