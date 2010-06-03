<tr>
    <th>{$i18n->_('name')}</th>
    <td><textarea name="name" id="name" class="">{$post['name']}</textarea></td>
</tr>
<tr>
    <th>{$i18n->_('number')}</th>
    <td><input type="text" name="number" id="number" value="{$post['number']}" class="" /></td>
</tr>
<tr>
    <th>{$i18n->_('data')}</th>
    <td><textarea name="data" id="data" class="">{$post['data']}</textarea></td>
</tr>

<!--
$name = $this->getRequest()->getParam('name');
$number = $this->getRequest()->getParam('number');
$data = $this->getRequest()->getParam('data');
-->

<!--
$post = array(
    'name' => $test->getName(),
    'number' => $test->getNumber(),
    'data' => $test->getData(),
);
-->
