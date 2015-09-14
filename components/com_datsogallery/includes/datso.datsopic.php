<?php
  defined('_JEXEC') or die('Direct Access to this location is not allowed.');
  JHTML::_('behavior.mootools');
  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
  $app = JFactory::getApplication('site');
  $document = JFactory::getDocument();
  $db = JFactory::getDBO();
  $user = JFactory::getUser();
  $categories[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_SELECT_CATEGORY'));
  $db->setQuery('SELECT cid AS value, name AS text FROM #__datsogallery_catg WHERE access IN ('.$groups.')');
  $categories = array_merge($categories, $db->loadObjectList());
  $clist = JHTML::_('select.genericlist', $categories, 'catid', 'class="input" onchange="this.form.submit();"', 'value', 'text', $catid);
  $catid = $app->getUserStateFromRequest('datsopic.button.catid', 'catid', 0, 'int');
  $js = "
    function insertDatsoPicId(id) {
      align = document.getElementById('align').value;
      if(align){ align = ' '+align;
      } else { align = ''; }
      datso = '{datsopic:'+id+align+'}';
      window.parent.jInsertEditorText(datso, 'text');
      window.parent.document.getElementById('sbox-window').close();
    }";
  $document->addScriptDeclaration($js);
  $document->addStyleDeclaration("body{background-color:#fff;background:none!important;}");
  $db->setQuery('SELECT *'
  .' FROM #__datsogallery'
  .' WHERE published = 1'
  .' AND approved = 1'
  .' AND catid = '.$catid
  );
  $images = $db->loadObjectList();
  $total = count($images);
  ?>
  <div style="display:block;padding:10px;height:30px">
    <form method="post">
      <div style="float:left"> <?php echo JText::_('COM_DATSOGALLERY_CATEGORY_LIST'); ?> <?php echo $clist; ?> </div>
    </form>
    <div style="float:left;padding-left:10px"> <?php echo JText::_('COM_DATSOGALLERY_ALIGN'); ?>
      <select id="align">
        <option value=""> <?php echo JText::_('COM_DATSOGALLERY_NOT_SET'); ?> </option>
        <option value="left"> <?php echo JText::_('COM_DATSOGALLERY_ALIGN_LEFT'); ?> </option>
        <option value="right"> <?php echo JText::_('COM_DATSOGALLERY_ALIGN_RIGHT'); ?> </option>
      </select>
    </div>
  </div>
  <div style="clear:both"></div>
  <?php
  if ($total != 0) {
    echo '<p><ul style="list-style:none">';
    foreach ($images as $image) {
      $pic = resize($image->imgoriginalname, 50, 50, 1, '1:1', 0, $catid);
      echo '<li style="float:left;background-image:none;padding:0;margin-left:6px">';
      echo '<a href="javascript:insertDatsoPicId('.$image->id.');">';
      echo '<img src="'.$pic.'" class="dgimg" alt="'.$image->imgtitle.'" />';
      echo '</a>';
      echo '</li>';
    }
    echo '</ul></p>';
  }
?>