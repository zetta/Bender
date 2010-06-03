<tr>
    <th>{$i18n->_('id_person')}</th>
    <td># {$post['id_person']}<input type="hidden" name="id_person" id="id_person" value="{$post['id_person']}" /></td>
</tr>
<tr>
    <th>{$i18n->_('first_name')}</th>
    <td><textarea name="first_name" id="first_name" class="">{$post['first_name']}</textarea></td>
</tr>
<tr>
    <th>{$i18n->_('last_name')}</th>
    <td><textarea name="last_name" id="last_name" class="">{$post['last_name']}</textarea></td>
</tr>

<!--
$idPerson = $this->getRequest()->getParam('id_person');
$firstName = $this->getRequest()->getParam('first_name');
$lastName = $this->getRequest()->getParam('last_name');
-->

<!--
$post = array(
    'id_person' => $person->getIdPerson(),
    'first_name' => $person->getFirstName(),
    'last_name' => $person->getLastName(),
);
-->
