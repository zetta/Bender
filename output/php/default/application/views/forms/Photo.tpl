<tr>
    <th>{$i18n->_('id_photo')}</th>
    <td># {$post['id_photo']}<input type="hidden" name="id_photo" id="id_photo" value="{$post['id_photo']}" /></td>
</tr>
<tr>
    <th>{$i18n->_('id_user')}</th>
    <td>{html_options name=id_user id=id_user options=$Users selected=$post['id_user'] }</td>
</tr>
<tr>
    <th>{$i18n->_('title')}</th>
    <td><textarea name="title" id="title" class="">{$post['title']}</textarea></td>
</tr>
<tr>
    <th>{$i18n->_('uri')}</th>
    <td><textarea name="uri" id="uri" class="">{$post['uri']}</textarea></td>
</tr>

<!--
$idPhoto = $this->getRequest()->getParam('id_photo');
$idUser = $this->getRequest()->getParam('id_user');
$title = $this->getRequest()->getParam('title');
$uri = $this->getRequest()->getParam('uri');
-->

<!--
$post = array(
    'id_photo' => $photo->getIdPhoto(),
    'id_user' => $photo->getIdUser(),
    'title' => $photo->getTitle(),
    'uri' => $photo->getUri(),
);
-->
