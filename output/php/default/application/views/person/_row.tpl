_row.tpl

<tr>
    <td>{$person->getIdPerson()}</td>
    <td>{$person->getFirstName()}</td>
    <td>{$person->getLastName()}</td>
    <td><a href="{url action=edit idPerson=$person->getIdPerson()}">{icon src=pencil class=tip title=$i18n->_('Edit')}</a></td>
    <td><a href="{url action=delete idPerson=$person->getIdPerson()}" class="confirm">{icon src=delete class=tip title=$i18n->_('Delete')}</a></td>
</tr>
