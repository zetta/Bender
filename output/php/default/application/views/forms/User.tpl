<tr>
    <th>{$i18n->_('id_user')}</th>
    <td># {$post['id_user']}<input type="hidden" name="id_user" id="id_user" value="{$post['id_user']}" /></td>
</tr>
<tr>
    <th>{$i18n->_('id_person')}</th>
    <td>{html_options name=id_person id=id_person options=$Persons selected=$post['id_person'] }</td>
</tr>
<tr>
    <th>{$i18n->_('username')}</th>
    <td><textarea name="username" id="username" class="">{$post['username']}</textarea></td>
</tr>
<tr>
    <th>{$i18n->_('password')}</th>
    <td><textarea name="password" id="password" class="">{$post['password']}</textarea></td>
</tr>

<!--
$idUser = $this->getRequest()->getParam('id_user');
$idPerson = $this->getRequest()->getParam('id_person');
$username = $this->getRequest()->getParam('username');
$password = $this->getRequest()->getParam('password');
-->

<!--
$post = array(
    'id_user' => $user->getIdUser(),
    'id_person' => $user->getIdPerson(),
    'username' => $user->getUsername(),
    'password' => $user->getPassword(),
);
-->
