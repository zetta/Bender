<tr>
    <th>{$i18n->_('id_album')}</th>
    <td># {$post['id_album']}<input type="hidden" name="id_album" id="id_album" value="{$post['id_album']}" /></td>
</tr>
<tr>
    <th>{$i18n->_('id_user')}</th>
    <td>{html_options name=id_user id=id_user options=$Users selected=$post['id_user'] }</td>
</tr>
<tr>
    <th>{$i18n->_('title')}</th>
    <td><textarea name="title" id="title" class="">{$post['title']}</textarea></td>
</tr>

<!--
$idAlbum = $this->getRequest()->getParam('id_album');
$idUser = $this->getRequest()->getParam('id_user');
$title = $this->getRequest()->getParam('title');
-->

<!--
$post = array(
    'id_album' => $album->getIdAlbum(),
    'id_user' => $album->getIdUser(),
    'title' => $album->getTitle(),
);
-->
