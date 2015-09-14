<?php
  defined('_JEXEC') or die('Direct Access to this location is not allowed.');
  jimport( 'joomla.environment.request' );
  JHTML::_('behavior.mootools');
  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
  $order_id = JRequest::getString('orderid', '');
  $db =& JFactory::getDBO();
  $db->setQuery('SELECT p.*,c.name AS category'
  .' FROM #__datsogallery AS p'
  .' JOIN #__datsogallery_catg AS c ON c.cid = p.catid'
  .' JOIN #__datsogallery_purchases AS t ON t.image_id = p.id'
  .' WHERE t.order_id = "'.$order_id.'"'
  .' ORDER BY p.id'
  );
  $images = $db->loadObjectList();
  $total = count($images);
?>
<style type="text/css">
/*<![CDATA[*/
body,html {
  font-family:"Segoe UI", Arial, sans-serif;
  font-size: 12px;
  color: #696969;
}
.dgimg {
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
  padding:4px;
  background-color:#fff;
  border-left: 1px solid #e1e1e1;
  border-top: 1px solid #F2F2F2;
  border-right: 1px solid #e1e1e1;
  border-bottom: 1px solid #D3D3D3;
}
div {
  display: inline;
  font-size: 10px
}
.label {
  text-transform: uppercase;
  color: #A9A9A9;
}
/*]]>*/
</style>
<?php
  echo '<h2 align="center">'.JText::_('COM_DATSOGALLERY_ORDER_ID').': '.$order_id.'</h2>';
  if ($total != 0) {
    echo '<ul style="list-style:none">';
    foreach ($images as $image) {
      $pic = resize($image->imgoriginalname, 170, 170, 1, '3:2', 0, $image->catid);
      echo '<li style="float:left;background-image:none;padding:10px;margin-right:0;margin-left:6px">';
      echo '<p><img src="'.$pic.'" class="dgimg" title="'.$image->imgtitle.'" alt="'.$image->imgtitle.'" /></p>';
      echo '<div><div class="label">'.JText::_('COM_DATSOGALLERY_CATEGORY').':</div> '.$image->category.'</div><br />';
      echo '<div><div class="label">'.JText::_('COM_DATSOGALLERY_TITLE').':</div> '.$image->imgtitle.'</div><br />';
      echo '<div><div class="label">'.JText::_('COM_DATSOGALLERY_PRICE').':</div> '.$image->imgprice.' '.$ad_pp_currency.'</div>';
      echo '</li>';
    }
    echo '</ul>';
  }
?>