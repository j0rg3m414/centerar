<?php
  defined('_JEXEC') or die('Restricted access');
  define('DG_VER', '1.14');
  jimport('joomla.application.component.controller');
  jimport('joomla.filesystem.path');
  jimport('joomla.filesystem.folder');
  jimport('joomla.filesystem.file');
  require_once (JPATH_COMPONENT.DS.'class.datsogallery.php');
  require_once (JPATH_COMPONENT.DS.'images.datsogallery.php');
  require (JPATH_COMPONENT.DS.'config.datsogallery.php');
  $db = JFactory::getDBO();
  $document = JFactory::getDocument();
  $app = JFactory::getApplication('administrator');
  $task = JRequest::getCmd('task');
  $vName = JRequest::getCmd('task') ;
  $id = JRequest::getVar('id', 0, 'get', 'int');
  $catid = JRequest::getVar('catid', 0, '', 'int');
  $cid = JRequest::getVar('cid', array(0), '', 'array');
  JArrayHelper::toInteger($cid, array(0));
  $document->addStyleSheet(JURI::base(true).'/components/com_datsogallery/css/toolbar.css');
  $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/css/datso.base.css.css');
  $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/css/datso.lighttheme.css');
  if (preg_match('/\b MSIE \b/x', $_SERVER['HTTP_USER_AGENT'])) {
    $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/css/datso.img.ie.css.css');
  }
  else {
    $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/css/datso.img.css.css');
  }
  addSubmenus($vName);

  //checkMooPath();
  JHTML::script('jquery.min.js', 'components/com_datsogallery/libraries/');
  JHTML::script('datso.javascript.js', 'components/com_datsogallery/libraries/');
  JHTML::script('datso.tooltip.js', 'components/com_datsogallery/libraries/');
  $siteurl = 'var siteurl = "'.JURI::root(true).'/";';
  $document->addScriptDeclaration($siteurl);
  if(isJ25()==1){
    $jto = "jQuery(document).ready(function () {
      var replace_jto = jQuery('body').html().replace(/Joomla.tableOrdering/g, 'tableOrdering');
      jQuery('body').html(replace_jto);
    });";
    if($task =='' || $task == 'showcatg'){
      $document->addScriptDeclaration($jto);
    }
  }
  require_once (JApplicationHelper::getPath('admin_html'));
  if ($task == "vieworder") {
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'vieworder.php');
      exit;
  }
  switch ($task) {

    case "publish" :
      publishPicture($cid, 1);
      break;

    case "unpublish" :
      publishPicture($cid, 0);
      break;

    case 'movepic' :
      movePic($cid[0]);
      break;

    case 'movepicres' :
      movePicResult($cid);
      break;

    case "edit" :
      editPicture($cid[0]);
      break;

    case "remove" :
      removePicture();
      break;

    case "save" :
      savePicture();
      break;

    case "comments" :
      showComments();
      break;

    case "publishcmt" :
      publishComment($id, 1);
      break;

    case "unpublishcmt" :
      publishComment($id, 0);
      break;

    case "removecmt" :
      removeComment();
      break;

    case "showupload" :
      showUpload();
      break;

    case "uploading" :
      require_once (JPATH_COMPONENT_SITE.DS.'includes'.DS.'datso.upload.php');
      break;

    case "upload" :
      dgUpload();
      break;

    case "batchimport" :
      showBatchImport();
      break;

    case "batchimporthandler" :
      batchImportHandler();
      break;

    case "wmupload" :
      wmUpload();
    break;

    case "resetvotes" :
      showVotes();
      break;

    case "reset" :
      resetVotes();
      break;

    case "blacklist" :
      showBlacklist();
      break;

    case "publishbl" :
      publishBlacklist($id, 1);
      break;

    case "unpublishbl" :
      publishBlacklist($id, 0);
      break;

    case "removebl" :
      removeBlacklist();
      break;

    case "settings" :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'settings.php');
      break;

    case "savesettings" :
      saveConfig();
      break;

    case "newcatg" :
      editCatg(0);
      break;

    case "editcatg" :
      editCatg($cid[0]);
      break;

    case "showcatg" :
      viewCatg();
      break;

    case "savecatg" :
      saveCatg();
      break;

    case "removecatg" :
      removeCatg($cid);
      break;

    case "publishcatg" :
      publishCatg($cid, 1);
      break;

    case "unpublishcatg" :
      publishCatg($cid, 0);
      break;

    case "approvecatg" :
      approveCatg($cid, 1);
      break;

    case "unapprovecatg" :
      approveCatg($cid, 0);
      break;

    case "approvepic" :
      approvePicture($cid, 1);
      break;

    case "rejectpic" :
      approvePicture($cid, 0);
      break;

    case "orderup" :
      orderPic($cid[0], -1);
      break;

    case "orderdown" :
      orderPic($cid[0], 1);
      break;

    case "saveorder" :
      saveOrder($cid);
      break;

    case "cancelcatg" :
      cancelCatg();
      break;

    case "orderupcatg" :
      orderCatg($cid[0], -1);
      break;

    case "orderdowncatg" :
      orderCatg($cid[0], 1);
      break;

    case "savecatorder" :
      saveCatOrder($cid);
    break;

    case "cancel" :
      $app->redirect('index.php?option=com_datsogallery');
      break;

    case "transactions" :
      showTransactions();
      break;

    case "removetransaction" :
      removeTransaction();
      break;

    default :
      showPictures();
      break;
  }

  function showPictures() {
    $app = JFactory::getApplication('administrator');
    $task = JRequest::getCmd('task');
	echo base64_decode("PGgzIHN0eWxlPSdjb2xvcjojZjAwOyc+PGEgaHJlZj0naHR0cDovL3JlZGJpdHouY29tJyB0YXJnZXQ9J190YXJnZXQnPkRvd25sb2FkIFByb3ZpZGVyIDo6IFJFZEJJdFouQ09NPC9hPjwvaDM+");
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_IMAGES_MANAGER_TITLE'), 'tb-images');
	JToolBarHelper::custom('publish', 'dg-show.png', 'dg-show.png', JText::_('COM_DATSOGALLERY_PUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('unpublish', 'dg-hide.png', 'dg-hide.png', JText::_('COM_DATSOGALLERY_UNPUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('movepic', 'dg-move.png', 'dg-move.png', JText::_('COM_DATSOGALLERY_MOVE'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('approvepic', 'dg-approve.png', 'dg-approve.png', JText::_('COM_DATSOGALLERY_APPROVE'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('showupload', 'dg-add.png', 'dg-add.png', JText::_('COM_DATSOGALLERY_UPLOAD_IMAGES'), false);
    JToolBarHelper::spacer();
    JToolBarHelper::custom('edit', 'dg-edit.png', 'dg-edit.png', JText::_('COM_DATSOGALLERY_EDIT'));
    JToolBarHelper::spacer();
    $bar = JToolBar::getInstance('toolbar');
    $bar->appendButton('Confirm', JText::_('COM_DATSOGALLERY_SURE_DELETE_IMAGES'), 'dg-delete', JText::_('COM_DATSOGALLERY_DELETE'), 'remove', true);

    $db = JFactory::getDBO();
    $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', 15, 'int');
    $limitstart = $app->getUserStateFromRequest('com_datsogallery.limitstart', 'limitstart', 0, 'int');
    $catid = $app->getUserStateFromRequest('com_datsogallery.catid', 'catid', 0, 'int');
    $filter_order = $app->getUserStateFromRequest('com_datsogallery.filter_order', 'filter_order', 'a.ordering', 'cmd');
    $filter_order_Dir = $app->getUserStateFromRequest('com_datsogallery.filter_order_Dir', 'filter_order_Dir', '', 'word');
    $sort = $app->getUserStateFromRequest('com_datsogallery.sort', 'sort', 0);
    $search = $app->getUserStateFromRequest('com_datsogallery.search', 'search', '', 'string');
    $search = JString::strtolower($search);
    $where = array();
    if ($catid > 0) {
      $where[] = 'a.catid = '.$catid;
    }
    if ($sort == 1) {
      $where[] = 'a.approved = 0';
    }
    if ($sort == 2) {
      $where[] = 'a.approved = 1';
    }
    if ($sort == 3) {
      $where[] = 'a.useruploaded = 1';
    }
    if ($sort == 4) {
      $where[] = 'a.useruploaded = 0';
    }
    if (!in_array($filter_order, array(
    'a.id', 'a.imgprice', 'a.imgtitle', 'a.imgdate', 'a.imgcounter',
    'a.imgdownloaded', 'a.imgvotesum', 'a.published', 'a.ordering',
    'a.approved', 'category', 'u.username'))) {
      $filter_order = 'a.ordering';
    }
    if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
      $filter_order_Dir = '';
    }
    $orderCol = $filter_order;
    $orderDirn = $filter_order_Dir;
    if ($orderCol == 'a.ordering' || $orderCol == 'category') {
    	$orderCol = 'category '.$orderDirn.', a.ordering';
    }
    $orderby = ' ORDER BY '.$db->getEscaped($orderCol.' '.$orderDirn);
    if ($search) {
      $searchEscaped = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
      $where[] = '( imgtitle LIKE '.$searchEscaped.' OR imgtext LIKE '.$searchEscaped.' )';
    }
    $db->setQuery("SELECT count(*) FROM #__datsogallery as a "
    .(count($where) ? ' WHERE '.implode(' AND ', $where) : ''));
    $total = $db->loadResult();
    jimport('joomla.html.pagination');
    $pagination = new JPagination($total, $limitstart, $limit);
    $where[] = 'a.catid = cc.cid';
    $where[] = 'a.owner_id = u.id';
    $picincat = count($where) ? ' WHERE '.implode(' AND ', $where) : '';
    $query = 'SELECT a.*, cc.name AS category, u.username'
    .' FROM #__datsogallery AS a,'
    .' #__datsogallery_catg AS cc,'
    .' #__users AS u'
    . $picincat
    . $orderby
    ;
    $db->setQuery($query, $pagination->limitstart, $pagination->limit);
	$rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    $clist = CategoryList($catid, 'catid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"');
    $s_options[] = JHTML::_('select.option', JText::_('COM_DATSOGALLERY_ALL_IMAGES'), 0);
    $s_options[] = JHTML::_('select.option', "1", JText::_('COM_DATSOGALLERY_NOT_APPROVED'));
    $s_options[] = JHTML::_('select.option', "2", JText::_('COM_DATSOGALLERY_APPROVED'));
    $s_options[] = JHTML::_('select.option', "3", JText::_('COM_DATSOGALLERY_USER_UPLOADED'));
    $s_options[] = JHTML::_('select.option', "4", JText::_('COM_DATSOGALLERY_ADMIN_UPLOADED'));
    $slist = JHTML::_('select.genericlist', $s_options, 'sort', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $sort);
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order'] = $filter_order;
    datsogallery_html::showPictures($rows, $clist, $slist, $lists, $search, $pagination);
  }

  function orderPic($uid, $inc) {
    JRequest::checkToken() or jexit( 'Invalid Token' );
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $row = new DatsoImages($db);
    $row->load($uid);
    if (!$row->move($inc, 'catid = '.$db->Quote($row->catid))) {
      $this->setError($row->getError());
      return false;
    }
    $app->redirect('index.php?option=com_datsogallery');
  }

  function saveOrder(&$cid) {
    $app = JFactory::getApplication('administrator');
    JRequest::checkToken() or jexit('Invalid Token');
    $db = JFactory::getDBO();
    $total = count($cid);
    $order = JRequest::getVar('order', array(0), 'post', 'array');
    JArrayHelper::toInteger($order, array(0));
    $row = new DatsoImages($db);
    $groupings = array();
    for ($i = 0; $i < $total; $i++) {
      $row->load((int) $cid[$i]);
      $groupings[] = $row->catid;
      if ($row->ordering != $order[$i]) {
        $row->ordering = $order[$i];
        if (!$row->store()) {
          JError::raiseError(500, $db->getErrorMsg());
        }
      }
    }
    $groupings = array_unique($groupings);
    foreach ($groupings as $group) {
      $row->reorder('catid = ' . (int) $group);
    }
    $app->redirect('index.php?option=com_datsogallery', JText::_('COM_DATSOGALLERY_ORDERING_OK'));
  }

  function removePicture() {
    $app = JFactory::getApplication('administrator');
    require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.path');
    $db = JFactory::getDBO();
    $cid = JRequest::getVar('cid', array(), 'post', 'array');
    JArrayHelper::toInteger($cid);
    if (count($cid) < 1) {
      $msg = JText::_('Select an item to delete');
      $app->redirect('index.php?option=com_datsogallery', $msg, 'error');
    }
    $cids = implode(',', $cid);
    for ($i = 0; $i < count($cid); $i++) {
      $db->setQuery('SELECT * from #__datsogallery where id = '.$cid[$i]);
      $row = new DatsoImages($db);
      $row->load($cid[$i]);
      if (JFile::exists(JPATH_ROOT.$ad_pathoriginals.DS.$row->imgoriginalname)) {
        JFile::delete(JPATH_ROOT.$ad_pathoriginals.DS.$row->imgoriginalname);
      }
      $query = 'DELETE from #__datsogallery_tags WHERE image_id = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_votes WHERE vpic = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_comments WHERE cmtpic = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_favorites WHERE image_id = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_basket WHERE image_id = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_downloads WHERE pid = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_hits WHERE pid = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $query = 'DELETE from #__datsogallery_purchases WHERE image_id = '.$row->id;
      $db->setQuery($query);
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
    }
    $query = 'DELETE from #__datsogallery WHERE id IN ( '.$cids.' )';
    $db->setQuery($query);
    if (!$db->query()) {
      JError::raiseError(500, $db->getErrorMsg());
      return false;
    }
    $msg = JText::sprintf(count($cid).' selected image(s) successfully removed');
    $app->redirect('index.php?option=com_datsogallery', $msg);
  }

  function publishPicture($cid = null, $publish = 1) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    if (!is_array($cid) || count($cid) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $cids = implode(',', $cid);
    $db->setQuery("update #__datsogallery set published='$publish' where id IN ($cids)");
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit ();
    }
    $app->redirect("index.php?option=com_datsogallery");
  }

  function approvePicture($cid = null, $approve = 1) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    if (!is_array($cid) || count($cid) < 1) {
      $action = $approve ? 'approve' : 'reject';
      echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $cids = implode(',', $cid);
    $db->setQuery("update #__datsogallery set approved='$approve' where id IN ($cids)");
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit ();
    }
    $app->redirect('index.php?option=com_datsogallery');
  }

  function editPicture($uid) {
    $app = JFactory::getApplication('administrator');
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_EDIT_IMAGE'), 'tb-editimage');
    JToolBarHelper::custom('save', 'dg-save.png', 'dg-save.png', JText::_('COM_DATSOGALLERY_SAVE'), false);
    JToolBarHelper::spacer();
    JToolBarHelper::custom('cancel', 'dg-cancel.png', 'dg-cancel.png', JText::_('COM_DATSOGALLERY_CANCEL'), false);
    require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    require_once (JPATH_COMPONENT.DS.'images.datsogallery.php');
    $db = JFactory::getDBO();
    $row = new DatsoImages($db);
    $row->load($uid);
    $clist = CategoryList($row->catid, "catid", ' size="1"');
    if (!$uid)
      $row->published = 0;
    datsogallery_html::editPicture($row, $clist, $ad_pathoriginals, $ad_thumbwidth, $ad_thumbheight, $ad_crop, $ad_cropratio);
  }

  function savePicture() {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $post = JRequest::get('post');
    $post['imgtext'] = JRequest::getVar('imgtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
    $row = new DatsoImages($db);
    if (!$row->bind( $post )) {
		JError::raiseError(500, $row->getError() );
	}
	if (!$row->id) {
		$where = "catid = " . (int) $row->catid;
		$row->ordering = $row->getNextOrder( $where );
	}
    if (!$row->store()) {
		JError::raiseError(500, $row->getError() );
	}
    $msg = JText::_('COM_DATSOGALLERY_SUCCESS_UPDATED');
    $app->redirect("index.php?option=com_datsogallery", $msg);
  }

  function showUpload() {
    $app = JFactory::getApplication('administrator');
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_UPLOAD_FORM_TITLE'), 'tb-newimage');
    JToolBarHelper::back();
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $document = JFactory::getDocument();
    require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    $clist = CategoryList(0, 'catid', ' class="inputbox" size="1" style="width:266px"');
    if (JFolder::exists(JPATH_ROOT.DS.'tmp'.DS.$user->id)) {
      JFolder::delete(JPATH_ROOT.DS.'tmp'.DS.$user->id);
    }
    $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/libraries/plupload/css/plupload.queue.css');
    $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/jquery.min.js');
    $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/datso.noconflict.js');
    $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/plupload/js/plupload.full.js');
    $document->addScript(JURI::root(true).'/components/com_datsogallery/libraries/plupload/js/jquery.plupload.queue.js');
    $resize = ($ad_max_wh) ? 'resize: { width: '.$ad_max_wu.', height: '.$ad_max_hu.', quality: 100 },' : '';
    $msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
    $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
    if ($msie) {
      $runtimes = "runtimes: 'silverlight,flash',\n";
    }
    elseif ($chrome) {
      $runtimes = "runtimes: 'flash,silverlight,html5',\n";
    }
    else {
      $runtimes = "runtimes: 'flash,silverlight,html5',\n";
    }
    $maxupl = ini_get('upload_max_filesize');
  ?>

<script type="text/javascript">

    plupload.addI18n({
        'Select files': '<?php echo JText::_("COM_DATSOGALLERY_SELECT_FILES");?>',
        'Add files to the upload queue and click the start button.': '<?php echo JText::_("COM_DATSOGALLERY_ADD_FILES_TO_UPLOAD");?><div id="up_methods"><?php echo JText::_("COM_DATSOGALLERY_UPLOAD_STEP_ONE_FILES_TYPE");?></div>',
        'Filename': '<?php echo JText::_("COM_DATSOGALLERY_FILENAME");?>',
        'Status': '<?php echo JText::_("COM_DATSOGALLERY_STATUS");?>',
        'Size': '<?php echo JText::_("COM_DATSOGALLERY_FILESIZE");?>',
        'Add files': '<?php echo JText::_("COM_DATSOGALLERY_ADD_FILES");?>',
        'Add files.': '<?php echo JText::_("COM_DATSOGALLERY_ADD_FILES");?>.',
        'Stop current upload': '<?php echo JText::_("COM_DATSOGALLERY_STOP_UPLOAD");?>',
        'Start uploading queue': '<?php echo JText::_("COM_DATSOGALLERY_START_UPLOADING");?>',
        'Start upload': '<?php echo JText::_("COM_DATSOGALLERY_START_UPLOAD");?>',
        'Drag files here.': '<?php echo JText::_("COM_DATSOGALLERY_DRAG_FILES_HERE");?>',
        'N/A': '<?php echo JText::_("COM_DATSOGALLERY_NA");?>',
        'Uploaded %d/%d files': '<?php echo JText::_("COM_DATSOGALLERY_UPLOADED"); ?> %d/%d <?php echo JText::_("COM_DATSOGALLERY_FILES"); ?>'
    });
    datso(function () {
        uploader = datso("#stepone").pluploadQueue({
            <?php echo $runtimes;?>
            url: '<?php echo JURI::base();?>index.php?option=com_datsogallery&task=uploading',
            max_file_size : '<?php echo $maxupl; ?>',
	     	chunk_size : '500kb',
		    unique_names : false,
            dragdrop : true,
            <?php echo $resize;?>
            filters: [
            {title: '<?php echo JText::_("COM_DATSOGALLERY_IMAGE_FILES");?>', extensions: 'jpg,jpeg,gif,png'},
            {title : "Zip files", extensions : "zip"}
            ],
            flash_swf_url: '<?php echo JURI::root(true);?>/components/com_datsogallery/libraries/plupload/js/plupload.flash.swf',
            silverlight_xap_url: '<?php echo JURI::root(true);?>/components/com_datsogallery/libraries/plupload/js/plupload.silverlight.xap'
        });
        var uploader = datso('#stepone').pluploadQueue();
        uploader.bind('FilesAdded', function(up, files) {
        var filesizeAlert = false;
        datso(files).each(function(index, element) {
            if(up.settings.max_file_size<element.size){
                filesizeAlert = true;
            }
        });
        if(filesizeAlert){
            datso.each(files, function(i, file) {
            alert("Image file "+ file.name +" is too big. The limit is " + up.settings.max_file_size/1024/1024 + "MB");
            });
          }
        });
        uploader.bind('QueueChanged', function (up, files) {
            if (up.files.length > uplimit) {
                up.splice(0, 1);
            }
        });
        uploader.bind('FileUploaded', function (up, file, res) {
            if (this.total.queued == 0) {
                datso('#stepone').delay(1000).fadeOut().slideUp(300);
                datso('#steptwo').delay(1400).fadeIn().slideDown(300);
            }
        });
    });
    function checkForm() {
        var form = document.upForm;
        if (form.catid.value == '0') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_SELECT_CATEGORY');?>");
            return false;
        } else if (form.gentitle.value == '') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_HAVE_TITLE');?>");
            return false;
        } else {
            document.upForm.action = 'index.php';
            document.upForm.submit();
        }
    }
</script>
<div class="stepsform">
<div id="stepone">
  <p><?php echo JText::_('COM_DATSOGALLERY_BROWSER_SUPPORT');?></p>
</div>

<div id="steptwo">
   <div class="plupload_header_title"><?php echo JText::_('COM_DATSOGALLERY_STEP_TWO');?></div>
     <div class="plupload_header_text"><?php echo JText::_('COM_DATSOGALLERY_STEP_TWO_TEXT');?></div>
        <form method="post" name="upForm">
          <table width="100%" border="0">
            <tr align="left">
              <td width="30%"><?php echo JText::_('COM_DATSOGALLERY_SELECT_CATEGORY');?> *</td>
              <td width="70%">
                <?php
                  $catid = $app->getUserStateFromRequest("catid{com_datsogallery}", 'catid', 0, 'int');
                  $clist = CategoryList(0, 'catid', ' class="inputbox" size="1" style="width:362px"');
                  echo $clist.'<div style="float:right;padding-top:5px">'.dgTip(JText::_('COM_DATSOGALLERY_SELECT_CATEGORY')).'</div>';
                ?>
             </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_GENERIC_TITLE');?> *</td>
              <td>
                <input type="text" name="gentitle" style="width:360px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_GENERIC_TITLE_TIP'));?></div>
                </td>
            </tr>
            <tr>
              <td valign="top"><?php echo JText::_('COM_DATSOGALLERY_GENERIC_DESC');?></td>
              <td><textarea class="inputbox" cols="35" rows="8" name="gendesc" style="width:360px"></textarea>
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_GENERIC_DESCRIPTION_TIP'));?></div>
              </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR');?></td>
              <td><input type="text" name="genimgauthor" style="width:360px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_TIP'));?></div>
              </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL');?></td>
              <td><input type="text" name="genimgauthorurl" style="width:360px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL_TIP'));?></div>
              </td>
            </tr>
            <?php if ($ad_shop) {?>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_PRICE');?></td>
              <td><input class="inputbox" type="text" name="imgprice" value="0.00" style="width:50px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_PRICE_DESC'));?></div>
              </td>
            </tr>
             <?php }?>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">
                  <div><button type="submit" class="act" onclick="checkForm();return false;"><?php echo JText::_('COM_DATSOGALLERY_SAVE');?></button></div>
              </td>
            </tr>
          </table>
          <input type="hidden" name="option" value="com_datsogallery" />
          <input type="hidden" name="task" value="upload" />
        </form>
</div>
</div>
  <?php
  }

  function showBatchImport() {
    $app = JFactory::getApplication('administrator');
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_IMPORT_FORM'), 'tb-batchimport');
    JToolBarHelper::back();
    $db =& JFactory::getDBO();
    $document =& JFactory::getDocument();
    $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/libraries/plupload/css/plupload.queue.css');
    require (JPATH_COMPONENT.DS.'config.datsogallery.php');
    jimport('joomla.filesystem.folder');
    $path = JPATH_ROOT.DS.'zipimport';
    if (!JFolder::exists($path)) {
      return JError::raiseWarning(- 1, JText::_('COM_DATSOGALLERY_ZIPIMPORT_NOT_EXIST_TIP'));
    }
  ?>

<script type=text/javascript>
    function checkForm() {
        var form = document.importForm;
        if (form.catid.value == 0) {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_SELECT_CATEGORY');?>");
            return false;
        } else if (form.gentitle.value == '') {
            alert("<?php echo JText::_('COM_DATSOGALLERY_MUST_HAVE_TITLE');?>");
            return false;
        } else {
            document.importForm.action = 'index.php';
            document.importForm.submit();
        }
    }
</script>


<div class="stepsform">
  <div class="plupload_header_title"><?php echo JText::_('COM_DATSOGALLERY_IMPORT_FORM');?></div>
    <div class="plupload_header_text"><?php echo JText::_('COM_DATSOGALLERY_IMPORT_FORM_TEXT');?></div>
      <div id="up_methods"><?php echo JText::_('COM_DATSOGALLERY_IMPORT_FORM_METHODS');?><br /><br /><?php echo JText::_('COM_DATSOGALLERY_STEP_TWO_TEXT');?></div>
        <form action="index.php" method="post" name="importForm" enctype="multipart/form-data">
          <table width="100%" border="0">
            <tr align="left">
              <td width="30%"><?php echo JText::_('COM_DATSOGALLERY_SELECT_CATEGORY');?> *</td>
              <td width="70%">
                <?php
                  $catid = $app->getUserStateFromRequest("catid{com_datsogallery}", 'catid', 0, 'int');
                  $clist = CategoryList(0, 'catid', ' class="inputbox" size="1" style="width:362px"');
                  echo $clist.'<div style="float:right;padding-top:5px">'.dgTip(JText::_('COM_DATSOGALLERY_ALLOWED_CAT')).'</div>';
                ?>
             </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_GENERIC_TITLE');?> *</td>
              <td>
                <input type="text" name="gentitle" style="width:360px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_GENERIC_TITLE_BI_I'));?></div>
                </td>
            </tr>
            <tr>
              <td valign="top"><?php echo JText::_('COM_DATSOGALLERY_GENERIC_DESC');?></td>
              <td><textarea class="inputbox" cols="35" rows="10" name="gendesc" style="width:360px"></textarea>
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_OPT'));?></div>
              </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_SHOW_AUTHOR');?></td>
              <td><input type="text" name="genimgauthor" style="width:360px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_TIP'));?></div>
                </td>
            </tr>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL');?></td>
              <td><input type="text" name="genimgauthorurl" style="width:360px" />
              <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_IMAGE_AUTHOR_URL_TIP'));?></div>
              </td>
            </tr>
            <?php if ($ad_shop): ?>
            <tr>
              <td><?php echo JText::_('COM_DATSOGALLERY_PRICE');?></td>
              <td><input class="inputbox" type="text" name="imgprice" value="0.00" style="width:50px" />
                <div style="float:right;padding-top:5px"><?php echo dgTip(JText::_('COM_DATSOGALLERY_PRICE_DESC'));?></div>
              </td>
            </tr>
             <?php endif; ?>
            <tr>
              <td></td>
              <td colspan="2">
                  <div><button type="submit" class="act" onclick="checkForm();return false;"><?php echo JText::_('COM_DATSOGALLERY_ZIP_IMPORT');?></button></div>
              </td>
            </tr>
          </table>
          <input type="hidden" name="option" value="com_datsogallery" />
          <input type="hidden" name="task" value="batchimporthandler" />
        </form>
</div>
  <?php
  }

function batchImportHandler() {
  jimport('joomla.filesystem.*');
  jimport('joomla.filesystem.archive');
  $app = JFactory::getApplication('administrator');
  $catid = JRequest::getVar('catid', 0, 'post', 'int');
  @ini_set('max_execution_time', '240');
  $time_start = getmicrotime();
  $max_wait = @ini_get('max_execution_time') - 2;
  $filelist = array();
  require (JPATH_COMPONENT.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT.DS.'images.datsogallery.php');
  $dir = JPATH_SITE.DS.'zipimport';
  dgChmod($dir, 0777);
  $directory_zip = opendir($dir);
  while ($file_name = readdir($directory_zip)) {
    $ext = strtolower(substr($file_name, - 4));
    if ($ext == ".zip") {
      if (JArchive::extract($dir.DS.$file_name, $dir) == TRUE) {
        JFile::delete($dir.DS.$file_name);
      }
    }
  }
  closedir($directory_zip);
  $directory_zip = opendir($dir);
  while (false !== ($file = readdir($directory_zip))) {
    $files[] = $file;
  }
  sort($files);
  $i = 0;
  foreach ($files as $file) {
    if ($file != '.'
    && $file != '..'
    && (strcasecmp($file, 'index.html') != 0)
    && (strcasecmp($file, '__MACOSX') != 0)) {
      $i++;
      $count = ($i > 1) ? ' '.$i : '';
      $origfilename = $file;
      $imagetype = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
      $imginfo = getimagesize($dir.DS.$origfilename);
      $ext = strtolower($imagetype[$imginfo[2]]);
      if (is_dir($dir.DS.$origfilename)) {
        JFolder::delete($dir.DS.$origfilename);
        $msg = JText::sprintf('COM_DATSOGALLERY_ZIP_PACKAGE_ERROR','<a href="http://www.datso.fr/en/video-guidelines.html" target="_blank">','</a>');
        $app->redirect('index.php?option=com_datsogallery&task=pictures',$msg);
      }
      if (!$ext) {
        JFile::delete($dir.DS.$origfilename);
      }
      else {
        $newfilename = ($ad_fname) ? $origfilename : dgImgId($catid, $ext);
        JFile::copy($dir.DS.$origfilename, JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$newfilename));
        JFile::delete($dir.DS.$origfilename);
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $row = new DatsoImages($db);
        if (!$row->bind(JRequest::get('post'))) {
          return JError::raiseWarning(500, $row->getError());
        }
        $row->ordering = $row->getNextOrder('catid = '.(int) $catid);
        $row->imgtitle = JRequest::getVar('gentitle').$count;
        $row->imgtext = JRequest::getVar('gendesc');
        $row->imgauthor = JRequest::getVar('genimgauthor');
        $row->imgauthorurl = JRequest::getVar('genimgauthorurl');
        $row->imgdate = mktime();
        $row->owner_id = $user->id;
        $row->published = 1;
        $row->approved = 1;
        $row->imgoriginalname = $newfilename;
        $row->useruploaded = 0;
        if (!$row->store()) {
          JError::raiseError(500, $row->getError());
        }
        $time_end = getmicrotime();
        if ($max_wait < ($time_end - $time_start)) {
          $time = $time_end - $time_start;
          $timelimit = ini_get('max_execution_time');
          closedir($directory_zip);
          dgChmod($dir, 0755);
          $msg = JText::sprinf('COM_DATSOGALLERY_TIME_LIMIT_MSG', $timelimit);
          $app->redirect('index.php?option=com_datsogallery&task=pictures', $msg);
        }
      }
    }
  }
  closedir($directory_zip);
  dgChmod($dir);
  $msg = 'Зашибись!';
  $app->redirect('index.php?option=com_datsogallery&task=pictures',$msg);
}

  $ad_cr = "DatsoGallery ".DG_VER."<br />By <a href='http://www.datso.fr'>Andrey Datso</a>";

  function saveConfig() {
    $app = JFactory::getApplication('administrator');
    $ad_download_resolutions = JRequest::getVar('ad_download_resolutions', array(0), 'post', 'array');
    $ad_download_resolutions = implode(",", $ad_download_resolutions);
    $ad_category = (JRequest::getVar('user_categories') == 0) ? JRequest::getVar('ad_category', array(0), 'post', 'array'):JRequest::getVar('ad_category', 0, 'post', 'int');
    $configfile = JPATH_COMPONENT.DS.'config.datsogallery.php';
    $ad_category = (JRequest::getVar('user_categories') == 0) ? implode(",", $ad_category) : $ad_category;
    $config = "<?php\n";
    $config .= "defined( '_JEXEC' ) or die( 'Restricted access' );\n";
    $config .= "\$ad_pathoriginals = \"".JRequest::getVar('ad_pathoriginals', '/images/dg_originals', 'post', 'string')."\";\n";
    $config .= "\$ad_protect = \"".JRequest::getVar('ad_protect', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_orgwidth = \"".JRequest::getVar('ad_orgwidth', '800', 'post', 'int')."\";\n";
    $config .= "\$ad_orgheight = \"".JRequest::getVar('ad_orgheight', '800', 'post', 'int')."\";\n";
    $config .= "\$ad_thumbwidth = \"".JRequest::getVar('ad_thumbwidth', '100', 'post', 'int')."\";\n";
    $config .= "\$ad_thumbheight = \"".JRequest::getVar('ad_thumbheight', '100', 'post', 'int')."\";\n";
    $config .= "\$ad_thumbwidth1 = \"".JRequest::getVar('ad_thumbwidth1', '100', 'post', 'int')."\";\n";
    $config .= "\$ad_thumbheight1 = \"".JRequest::getVar('ad_thumbheight1', '100', 'post', 'int')."\";\n";
    $config .= "\$ad_crop = \"".JRequest::getVar('ad_crop', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_cropratio = \"".JRequest::getVar('ad_cropratio', 0, 'post', 'string')."\";\n";
    $config .= "\$ad_thumbquality = \"".JRequest::getVar('ad_thumbquality', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_fname = \"".JRequest::getVar('ad_fname', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showdetail = \"".JRequest::getVar('ad_showdetail', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showrating = \"".JRequest::getVar('ad_showrating', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_access_rating = \"".JRequest::getVar('ad_access_rating', 0, 'post', 'int')."\";\n";
    $config .= "\$quick_rating = \"".JRequest::getVar('quick_rating', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_allow_tags = \"".JRequest::getVar('ad_allow_tags', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_access_tags = \"".JRequest::getVar('ad_access_tags', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_max_tags = \"".JRequest::getVar('ad_max_tags', '5', 'post', 'int')."\";\n";
    $config .= "\$ad_min_tag_chars = \"".JRequest::getVar('ad_min_tag_chars', '3', 'post', 'int')."\";\n";
    $config .= "\$ad_showcomment = \"".JRequest::getVar('ad_showcomment', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showpanel = \"".JRequest::getVar('ad_showpanel', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_userpannel = \"".JRequest::getVar('ad_userpannel', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_special = \"".JRequest::getVar('ad_special', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_rating = \"".JRequest::getVar('ad_rating', 0, 'post', 'int')."\";\n";
    $config .= "\$most_downloaded_menu = \"".JRequest::getVar('most_downloaded_menu', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_lastadd = \"".JRequest::getVar('ad_lastadd', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_lastcomment = \"".JRequest::getVar('ad_lastcomment', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showinformer = \"".JRequest::getVar('ad_showinformer', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_periods = \"".JRequest::getVar('ad_periods', '12', 'post', 'int')."\";\n";
    $config .= "\$ad_search = \"".JRequest::getVar('ad_search', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_comtitle = \"".JRequest::getVar('ad_comtitle', 0, 'post', 'int')."\";\n";
    $config .= "\$show_grid = \"".JRequest::getVar('show_grid', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_na = \"".JRequest::getVar('ad_na', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showsend2friend = \"".JRequest::getVar('ad_showsend2friend', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_picincat = \"".JRequest::getVar('ad_picincat', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_powered = \"".JRequest::getVar('ad_powered', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showwatermark = \"".JRequest::getVar('ad_showwatermark', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_wmpos = \"".JRequest::getVar('ad_wmpos', 'bottomright', 'post', 'word')."\";\n";
    $config .= "\$ad_showdownload = \"".JRequest::getVar('ad_showdownload', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_download_options = \"".JRequest::getVar('ad_download_options', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_download_wm = \"".JRequest::getVar('ad_download_wm', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_download_resolutions = \"".$ad_download_resolutions."\";\n";
    $config .= "\$ad_downpub = \"".JRequest::getVar('ad_downpub', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_favorite = \"".JRequest::getVar('ad_favorite', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_gmap = \"".JRequest::getVar('ad_gmap', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_shop = \"".JRequest::getVar('ad_shop', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_anoncomment = \"".JRequest::getVar('ad_anoncomment', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_comment_notify = \"".JRequest::getVar('ad_comment_notify', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_comment_wl = \"".JRequest::getVar('ad_comment_wl', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_kunena = \"".JRequest::getVar('ad_kunena', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_perpage = \"".JRequest::getVar('ad_perpage', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_catsperpage = \"".JRequest::getVar('ad_catsperpage', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_sortby = \"".JRequest::getVar('ad_sortby', 'ASC', 'post', 'word')."\";\n";
    $config .= "\$ad_catimg = \"".JRequest::getVar('ad_catimg', 'RAND()', 'post', 'string')."\";\n";
    $config .= "\$ad_toplist = \"".JRequest::getVar('ad_toplist', 0, 'post', 'int')."\";\n";
    $config .= "\$dg_theme = \"".JRequest::getVar('dg_theme', 'lighttheme', 'post', 'word')."\";\n";
    $config .= "\$dg_border = \"".JRequest::getVar('dg_border', '606060', 'post', 'string')."\";\n";
    $config .= "\$dg_link_color = \"".JRequest::getVar('dg_link_color', '75BAFF', 'post', 'string')."\";\n";
    $config .= "\$dg_input_background = \"".JRequest::getVar('dg_input_background', '909090', 'post', 'string')."\";\n";
    $config .= "\$dg_input_hover = \"".JRequest::getVar('dg_input_hover', 'C0C0C0', 'post', 'string')."\";\n";
    $config .= "\$dg_head_background = \"".JRequest::getVar('dg_head_background', '505050', 'post', 'string')."\";\n";
    $config .= "\$dg_head_color = \"".JRequest::getVar('dg_head_color', 'B0B0B0', 'post', 'string')."\";\n";
    $config .= "\$dg_body_background = \"".JRequest::getVar('dg_body_background', '808080', 'post', 'string')."\";\n";
    $config .= "\$dg_body_background_td = \"".JRequest::getVar('dg_body_background_td', '707070', 'post', 'string')."\";\n";
    $config .= "\$dg_body_background_td_hover = \"".JRequest::getVar('dg_body_background_td_hover', '909090', 'post', 'string')."\";\n";
    $config .= "\$dg_body_color = \"".JRequest::getVar('dg_body_color', 'D0D0D0', 'post', 'string')."\";\n";
    $config .= "\$dg_captcha_color = \"".JRequest::getVar('dg_captcha_color', '99CCFF', 'post', 'string')."\";\n";
    $config .= "\$ad_slideshow = \"".JRequest::getVar('ad_slideshow', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_slideshow_auto = \"".JRequest::getVar('ad_slideshow_auto', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_slideshow_delay = \"".JRequest::getVar('ad_slideshow_delay', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_fader = \"".JRequest::getVar('ad_fader', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_approve = \"".JRequest::getVar('ad_approve', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_upload_notify = \"".JRequest::getVar('ad_upload_notify', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_acl_registered = \"".JRequest::getVar('ad_acl_registered', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_acl_author = \"".JRequest::getVar('ad_acl_author', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_acl_editor = \"".JRequest::getVar('ad_acl_editor', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_acl_publisher = \"".JRequest::getVar('ad_acl_publisher', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_maxfilesize = \"".JRequest::getVar('ad_maxfilesize', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_allow_zip = \"".JRequest::getVar('ad_allow_zip', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_max_wh = \"".JRequest::getVar('ad_max_wh', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_max_wu = \"".JRequest::getVar('ad_max_wu', '1600', 'post', 'int')."\";\n";
    $config .= "\$ad_max_hu = \"".JRequest::getVar('ad_max_hu', '1200', 'post', 'int')."\";\n";
    $config .= "\$ad_maxwidth = \"".JRequest::getVar('ad_maxwidth', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_maxheight = \"".JRequest::getVar('ad_maxheight', 0, 'post', 'int')."\";\n";
    $config .= "\$user_categories = \"".JRequest::getVar('user_categories', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_max_categories = \"".JRequest::getVar('ad_max_categories', 0, 'post', 'int')."\";\n";
    $config .= "\$user_categories_approval = \"".JRequest::getVar('user_categories_approval', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_category_notify = \"".JRequest::getVar('ad_category_notify', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_category = \"".$ad_category."\";\n";
    $config .= "\$ad_ncsc = \"".JRequest::getVar('ad_ncsc', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_cr = \"DatsoGallery<br />By <a href='http://www.datso.fr'>Andrey Datso</a>\";\n";
    $config .= "\$ad_showimgtitle = \"".JRequest::getVar('ad_showimgtitle', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showimgtext = \"".JRequest::getVar('ad_showimgtext', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showfimgdate = \"".JRequest::getVar('ad_showfimgdate', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showimgcounter = \"".JRequest::getVar('ad_showimgcounter', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showdownloads = \"".JRequest::getVar('ad_showdownloads', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showfrating = \"".JRequest::getVar('ad_showfrating', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showcomments = \"".JRequest::getVar('ad_showcomments', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showres = \"".JRequest::getVar('ad_showres', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showfimgsize = \"".JRequest::getVar('ad_showfimgsize', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showimgauthor = \"".JRequest::getVar('ad_showimgauthor', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_showsubmitter = \"".JRequest::getVar('ad_showsubmitter', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_cp = \"".JRequest::getVar('ad_cp', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_sbcat = \"".JRequest::getVar('ad_sbcat', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_shadowbox = \"".JRequest::getVar('ad_shadowbox', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_shadowbox_fa = \"".JRequest::getVar('ad_shadowbox_fa', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_name_or_user = \"".JRequest::getVar('ad_name_or_user', 'name', 'post', 'word')."\";\n";
    $config .= "\$ad_metagen = \"".JRequest::getVar('ad_metagen', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_js = \"".JRequest::getVar('ad_js', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_cb = \"".JRequest::getVar('ad_cb', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_exif = \"".JRequest::getVar('ad_exif', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_pnthumb = \"".JRequest::getVar('ad_pnthumb', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_bookmarker = \"".JRequest::getVar('ad_bookmarker', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_google = \"".JRequest::getVar('ad_google', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_facebook = \"".JRequest::getVar('ad_facebook', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_twitter = \"".JRequest::getVar('ad_twitter', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_myspace = \"".JRequest::getVar('ad_myspace', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_linkedin = \"".JRequest::getVar('ad_linkedin', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_yahoo = \"".JRequest::getVar('ad_yahoo', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_digg = \"".JRequest::getVar('ad_digg', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_del = \"".JRequest::getVar('ad_del', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_live = \"".JRequest::getVar('ad_live', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_furl = \"".JRequest::getVar('ad_furl', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_reddit = \"".JRequest::getVar('ad_reddit', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_technorati = \"".JRequest::getVar('ad_technorati', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_datef = \"".JRequest::getVar('ad_datef', '%d %b %Y %H:%M', 'post', 'string')."\";\n";
    $config .= "\$ad_pp_email = \"".JRequest::getVar('ad_pp_email', 'your@email.com', 'post', 'string')."\";\n";
    $config .= "\$ad_pp_mode = \"".JRequest::getVar('ad_pp_mode', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_pp_currency = \"".JRequest::getVar('ad_pp_currency', 'EUR', 'post', 'word')."\";\n";
    $config .= "\$ad_pp_tax_type = \"".JRequest::getVar('ad_pp_tax_type', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_pp_tax_value = \"".JRequest::getVar('ad_pp_tax_value', '0', 'post', 'string')."\";\n";
    $config .= "\$ad_terms = \"".JRequest::getVar('ad_terms', 0, 'post', 'int')."\";\n";
    $config .= "\$ad_terms_name = \"".JRequest::getVar('ad_terms_name', '', 'post', 'string')."\";\n";
    $config .= "\$ad_terms_id = \"".JRequest::getVar('ad_terms_id', 0, 'post', 'int')."\";\n";
    $config .= "?>";
    jimport('joomla.filesystem.file');
    JFile::write($configfile, $config);
    saveWords();
    $app->redirect("index.php?option=com_datsogallery&task=settings", JText::_('COM_DATSOGALLERY_SETT_SAVED'));
  }

  function publishComment($id = null, $publish = 1) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $id = JRequest::getVar('id', array(), 'post', 'array');
    if (!is_array($id) || count($id) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $ids = implode(',', $id);
    $db->setQuery('UPDATE #__datsogallery_comments'
    .' SET published = '.$publish
    .' WHERE cmtid IN ('.$ids.')'
    );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit ();
    }
    $app->redirect("index.php?option=com_datsogallery&task=comments");
  }

  function removeComment() {
    $app = JFactory::getApplication('administrator');
    $db =& JFactory::getDBO();
    $id = JRequest::getVar('id', array(), 'post', 'array');
    if (count($id) < 1) {
      $msg = JText::_('Select an item to delete');
      $app->redirect('index.php?option=com_datsogallery&task=comments', $msg, 'error');
    }
    $ids = implode(',', $id);
    $db->setQuery('delete from #__datsogallery_comments where cmtid in ('.$ids.')');
    if (!$db->query()) {
      JError::raiseError(500, $db->getErrorMsg());
      return false;
    }
    $app->redirect('index.php?option=com_datsogallery&task=comments');
  }

  function showComments() {
    $app = JFactory::getApplication('administrator');
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_COMMENTS_MANAGER_TITLE'), 'tb-comments');
    JToolBarHelper::custom('publishcmt', 'dg-show.png', 'dg-show.png', JText::_('COM_DATSOGALLERY_PUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('unpublishcmt', 'dg-hide.png', 'dg-hide.png', JText::_('COM_DATSOGALLERY_UNPUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('removecmt', 'dg-delete.png', 'dg-delete.png', JText::_('COM_DATSOGALLERY_DELETE'));
    require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
    $db = JFactory::getDBO();
    $limit = $app->getUserStateFromRequest('com_datsogallery.limit', 'limit', 10);
    $limitstart = $app->getUserStateFromRequest('com_datsogallery.limitstart', 'limitstart', 0);
    $search = $app->getUserStateFromRequest('com_datsogallery.search', 'search', '', 'string');
    $search = JString::strtolower($search);
    $where = array();
    if ($search) {
      $searchEscaped = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
      $where[] = '( cmttext LIKE '.$searchEscaped.' OR cmtname LIKE '.$searchEscaped.' OR cmtmail LIKE '.$searchEscaped.' OR cmtip LIKE '.$searchEscaped.' )';
    }
    $where[] = 'c.cmtpic = p.id';
    $db->setQuery("SELECT count(*) FROM #__datsogallery_comments AS c, #__datsogallery AS p".(count($where) ? "\nwhere ".implode(' AND ', $where) : ""));
    $total = $db->loadResult();
    echo $db->getErrorMsg();
    if ($limit > $total) {
      $limitstart = 0;
    }
    $db->setQuery("SELECT c.*, p.imgtitle, p.imgoriginalname, p.catid"
    ." FROM #__datsogallery_comments AS c,"
    ." #__datsogallery AS p"
    . (count($where) ? " WHERE "
    . implode(' AND ', $where) : "")
    ." ORDER BY c.cmtdate DESC"
    ." LIMIT $limitstart, $limit"
    );
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);
    datsogallery_html::showComments($rows, $search, $pageNav, $ad_datef);
  }

  function showTransactions() {
    $app = JFactory::getApplication('administrator');
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_TRANSACTIONS_MANAGER_TITLE'), 'tb-transactions');
    $bar = JToolBar::getInstance('toolbar');
    $bar->appendButton('Confirm', JText::_('COM_DATSOGALLERY_SURE_DELETE_TRANSACTIONS'), 'dg-delete', JText::_('COM_DATSOGALLERY_DELETE'), 'removetransaction', true);
    require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
    $db =& JFactory::getDBO();
    $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
    $limitstart = $app->getUserStateFromRequest('com_datsogallery.limitstart', 'limitstart', 0, 'int');
    $search = $app->getUserStateFromRequest('com_datsogallery.search', 'search', '', 'string');
    $search = JString::strtolower($search);
    $where = array();
    if ($search) {
      $searchEscaped = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
      $where[] = '(t.date LIKE '.$searchEscaped
      .' OR t.status LIKE '.$searchEscaped
      .' OR t.hash LIKE '.$searchEscaped
      .' OR t.order_id LIKE '.$searchEscaped
      .' OR t.user_ip LIKE '.$searchEscaped
      .' OR u.username LIKE '.$searchEscaped
      .' OR u.email LIKE '.$searchEscaped
      .')';
    }
    $where[] = 't.image_id = p.id';
    $db->setQuery('SELECT COUNT(order_id)'
    .' FROM #__datsogallery_purchases'
    .' GROUP BY order_id'
    );
    $total = $db->loadResult();
    echo $db->getErrorMsg();
    if ($limit > $total) {
      $limitstart = 0;
    }
    jimport('joomla.html.pagination');
    $pagination = new JPagination($total, $limitstart, $limit);
    $query = 'SELECT t.*, SUM(p.imgprice) AS amount, u.id AS user, u.username, u.email'
    .' FROM #__datsogallery_purchases AS t'
    .' LEFT JOIN #__datsogallery AS p ON p.id = t.image_id'
    .' LEFT JOIN #__users AS u ON u.id = t.user_id'
    . (count($where) ? ' WHERE '
    . implode(' AND ', $where) : '')
    .' GROUP BY t.hash'
    .' ORDER BY t.date DESC'
    ;
    $db->setQuery($query, $pagination->limitstart, $pagination->limit);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    datsogallery_html::showTransactions($rows, $search, $pagination);
  }

  function removeTransaction() {
    global $app;
    $db =& JFactory::getDBO();
    $order_id = JRequest::getVar('order_id', array(), 'post', 'array');
    if (!$order_id) {
      $msg = JText::_('Select an item to delete');
      $app->redirect('index.php?option=com_datsogallery&task=transactions', $msg, 'error');
    }
    $query = "DELETE FROM #__datsogallery_purchases WHERE order_id IN ('".implode("','", $order_id)."')";
    $db->setQuery($query);
    if (!$db->query()) {
      JError::raiseError(500, $db->getErrorMsg());
      return false;
    }
    $msg = JText::_('COM_DATSOGALLERY_TRANSACTION_DELETED');
    $app->redirect('index.php?option=com_datsogallery&task=transactions', $msg);
  }

  function viewCatg() {
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_CATEGORY_MANAGER_TITLE'), 'tb-categories');
    JToolBarHelper::custom('publishcatg', 'dg-show.png', 'dg-show.png', JText::_('COM_DATSOGALLERY_PUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('unpublishcatg', 'dg-hide.png', 'dg-hide.png', JText::_('COM_DATSOGALLERY_UNPUBLISH'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('approvecatg', 'dg-approve.png', 'dg-approve.png', JText::_('COM_DATSOGALLERY_APPROVE'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('newcatg', 'dg-add.png', 'dg-add.png', JText::_('COM_DATSOGALLERY_CREATE'), false);
    JToolBarHelper::spacer();
    JToolBarHelper::custom('editcatg', 'dg-edit.png', 'dg-edit.png', JText::_('COM_DATSOGALLERY_EDIT'));
    JToolBarHelper::spacer();
    JToolBarHelper::custom('removecatg', 'dg-delete.png', 'dg-delete.png', JText::_('COM_DATSOGALLERY_DELETE'));
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
    $limitstart = $app->getUserStateFromRequest('com_datsogallery.limitstart', 'limitstart', 0, 'int');
    $levellimit = $app->getUserStateFromRequest('com_datsogallery.levellimit', 'levellimit', 10, 'int');
    $filter_order = $app->getUserStateFromRequest('com_datsogallery.filter_order', 'filter_order', 'c.ordering', 'cmd');
    $filter_order_Dir = $app->getUserStateFromRequest('com_datsogallery.filter_order_Dir', 'filter_order_Dir', '', 'word');
    $search = $app->getUserStateFromRequest('com_datsogallery.search', 'search', '', 'string');
    if (strpos($search, '"') !== false) {
      $search = str_replace(array('=', '<'), '', $search);
    }
    $search = JString::strtolower($search);
    if ($search) {
      $query = 'SELECT c.cid'
      .' FROM #__datsogallery_catg AS c'
      .' WHERE LOWER( c.name ) LIKE '.$db->Quote('%'.$db->getEscaped($search, true).'%', false);
      $db->setQuery($query);
      $search_rows = $db->loadResultArray();
    }
    if (!in_array($filter_order, array('id', 'title', 'c.ordering', 'groupname', 'c.approved', 'c.published', 'c.date', 'owner'))) {
      $filter_order = 'c.ordering';
    }
    if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
      $filter_order_Dir = '';
    }
    $orderby = ' ORDER BY parent_id, '.$filter_order.' '.$filter_order_Dir;
    $query = 'SELECT c.*, c.cid as id, c.parent AS parent_id, c.name AS title, g.title AS groupname, u.username AS owner'
    .' FROM #__datsogallery_catg AS c'
    .' LEFT JOIN #__users AS u ON u.id = c.user_id'
    .' LEFT JOIN #__viewlevels AS g ON g.id = c.access'
    .' WHERE c.published != -2'
    .$orderby
    ;
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    $children = array();
    foreach ($rows as $row) {
      $parent = $row->parent_id;
      $list = @$children[$parent] ? $children[$parent] : array();
      array_push($list, $row);
      $children[$parent] = $list;
    }
    //$dash = str_repeat('<span class="gi">|&mdash;</span>', $levellimit-1);
    $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, max(0, $levellimit - 1));
    if ($search) {
      $list1 = array();
      foreach ($search_rows as $cid) {
        foreach ($list as $item) {
          if ($item->id == $cid) {
            $list1[] = $item;
          }
        }
      }
      $list = $list1;
    }
    $total = count($list);
    jimport('joomla.html.pagination');
    $pagination = new JPagination($total, $limitstart, $limit);
    $list = array_slice($list, $pagination->limitstart, $pagination->limit);
    $cats = $list;
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order'] = $filter_order;
    datsogallery_html::showCatgs($cats, $lists, $search, $pagination);
  }

  function editCatg($uid) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $task = JRequest::getCmd('task');
    if ($task == 'newcatg') {
      JToolBarHelper::title(JText::_('COM_DATSOGALLERY_CREATE_CATEGORY'), 'tb-addcategory');
      JToolBarHelper::custom('savecatg', 'dg-save.png', 'dg-save.png', JText::_('COM_DATSOGALLERY_SAVE'), false);
      JToolBarHelper::spacer();
      JToolBarHelper::custom('cancelcatg', 'dg-cancel.png', 'dg-cancel.png', JText::_('COM_DATSOGALLERY_CANCEL'), false);
    }
    else {
      JToolBarHelper::title(JText::_('COM_DATSOGALLERY_EDIT_CATEGORY'), 'tb-editcategory');
      JToolBarHelper::custom('savecatg', 'dg-save.png', 'dg-save.png', JText::_('COM_DATSOGALLERY_SAVE'), false);
      JToolBarHelper::spacer();
      JToolBarHelper::custom('cancelcatg', 'dg-cancel.png', 'dg-cancel.png', JText::_('COM_DATSOGALLERY_CANCEL'), false);
    }
    $row = new DatsoCategories($db);
    $row->load($uid);
    if ($row->ordering !=0){
      $row->ordering = $row->ordering;
    }
    if ($row->user_id == 0){
      $row->user_id = $user->id;
    }
    $yesno[] = JHTML::_('select.option', '0', JText::_('COM_DATSOGALLERY_NO'));
    $yesno[] = JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_YES'));
    $publist = JHTML::_('select.genericlist', $yesno, 'published', 'class="inputbox"', 'value', 'text', $row->published);
    $db->setQuery("SELECT id as value, title as text from #__viewlevels order by id");
    $groups = $db->loadObjectList();
    $glist = JHTML::_('select.genericlist', $groups, 'access', 'class="inputbox"', 'value', 'text', intval($row->access));
    $Lists["catgs"] = ShowDropDownCategoryList($row->parent, 'parent', '', $uid);
    datsogallery_html::editCatg($row, $publist, $glist, $Lists);
  }

  function saveCatg() {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $post = JRequest::get('post');
    $query = 'SELECT ordering, user_id, date'
    .' FROM #__datsogallery_catg'
    .' WHERE cid = ' . $post['cid'];
    $db->setQuery($query);
    $obj = $db->loadObject();
    $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
    $row = new DatsoCategories($db);
    if (!$post['cid'] || $obj->ordering == 0) {
      $row->ordering = $row->getNextOrder('parent = '.$post['parent']);
      $row->approved = 1;
    }
    if ($obj->user_id == 0){
      $row->user_id = $user->id;
    }
    if (!$obj->date){
      jimport('joomla.utilities.date');
      $dtz  = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
	  $date = new JDate($row->date);
	  $date->setTimezone($dtz);
	  $row->date = $date->toMySQL(true);
    }
    if (!$row->bind($post)) {
      JError::raiseError(500, $row->getError());
    }
    if (!$row->check()) {
      JError::raiseError(500, $row->getError());
    }
    if (!$row->store()) {
      JError::raiseError(500, $row->getError());
    }
    $app->redirect("index.php?option=com_datsogallery&task=showcatg");
  }

  function publishCatg($cid = null, $publish = 1) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    if (!is_array($cid) || count($cid) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $cids = implode(',', $cid);
    $db->setQuery('UPDATE #__datsogallery_catg SET published = '.$publish.' WHERE cid IN ('.$cids.')');
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (count($cid) == 1) {
      $row = new DatsoCategories($db);
      $row->checkin($cid[0]);
    }
    $app->redirect('index.php?option=com_datsogallery&task=showcatg');
  }

  function approveCatg ($cid = null, $approve = 1) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    if (!is_array($cid) || count($cid) < 1) {
      $action = $approve ? 'approve' : 'unapprove';
      echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $cids = implode(',', $cid);
    $db->setQuery('UPDATE #__datsogallery_catg SET approved = '.$approve.' WHERE cid IN ('.$cids.')');
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (count($cid) == 1) {
      $row = new DatsoCategories($db);
      $row->checkin($cid[0]);
    }
    $app->redirect('index.php?option=com_datsogallery&task=showcatg');
  }

  function removeCatg($cid) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    if (count($cid)) {
      $cids = implode(',', $cid);
      foreach ($cid as $id) {
        $parents = GetNumberOfParents($id);
        $images = GetNumberOfLinks($id);
        if ($images) {
          $app->redirect('index.php?option=com_datsogallery&task=showcatg', JText::_('COM_DATSOGALLERY_ERROR_DELETE_CATEGORY'), 'error');
        }
        elseif ($parents) {
          $app->redirect('index.php?option=com_datsogallery&task=showcatg', JText::_('COM_DATSOGALLERY_ERROR_DELETE_SUBCATEGORY'), 'error');
        }
        else {
          $db->setQuery('DELETE FROM #__datsogallery_catg WHERE cid = '.$id);
          if (!$db->query()) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
          }
        }
      }
    }
    $app->redirect('index.php?option=com_datsogallery&task=showcatg', JText::_('COM_DATSOGALLERY_CATEGORY_DELETED'), 'note');
  }

  function cancelCatg() {
    $app = JFactory::getApplication('administrator');
    $app->redirect("index.php?option=com_datsogallery&task=showcatg");
  }

  function ShowCategoryPath($cat) {
    $app = JFactory::getApplication('administrator');
    $db =& JFactory::getDBO();
    $cat = intval($cat);
    $parent = 1000;
    while ($parent) {
      $db->setQuery("SELECT * from #__datsogallery_catg where cid=$cat");
      $rows = $db->loadObjectList();
      $row =&$rows[0];
      $parent = @ $row->parent;
      $name = @ $row->name;
      if (empty ($path)) {
        $path = $name;
      }
      else {
        $path = $name.' &raquo; '.$path;
      }
      $cat = $parent;
    }
    return $path." ";
  }

  function SortCatArray($a, $b) {
    return strcmp($a->name, $b->name);
  }

  function ShowDropDownCategoryList($cat, $cname = "catid", $extra = null, $orig = null) {
    $db = JFactory::getDBO();
    $db->setQuery("SELECT cid, parent,name,'0' as ready
            FROM #__datsogallery_catg");
    $rows = $db->loadObjectList("cid");
    if ($cname == 'parent' && $orig != null) {
      $ignore = array();
    }
    $output = "<select name=\"$cname\" class=\"inputbox\" $extra >\n";
    $output .= "  <option value=\"0\"></option>\n";
    if (count($rows) == 0) {
      $output .= "</select>\n";
      return $output;
    }
    foreach ($rows as $key => $obj) {
      $parent = $obj->parent;
      if ($cname == 'parent' && $orig != null) {
        if ($parent == $orig || in_array($parent, $ignore)) {
          if (!in_array($key, $ignore)) {
            $ignore[] = $key;
            continue;
          }
        }
        else {
          $parentcat = null;
          $parentcats = array();
          $parentcat = $rows[$key]->parent;
          while ($parentcat != 0 && $parentcat != $orig) {
            $parentcat = $rows[$parentcat]->parent;
            $parentcats[] = $parentcat;
          }
          if (!empty ($parentcats) && in_array($orig, $parentcats)) {
            $ignore[] = $key;
            $ignore = array_merge($ignore, $parentcats);
            $parentcats = array();
            continue;
          }
        }
      }
      if ($parent != 0) {
        if ($rows[$parent]->ready) {
          $rows[$key]->name = $rows[$parent]->name.' &raquo; '.$rows[$key]->name;
        }
        else {
          while ($parent != 0) {
            $rows[$key]->name = $rows[$parent]->name.' &raquo; '.$rows[$key]->name;
            if ($rows[$parent]->ready) {
              break;
            }
            else {
              $parent = $rows[$parent]->parent;
            }
          }
        }
      }
      $rows[$key]->ready = "1";
    }
    if ($cname == 'parent' && $orig != null) {
      foreach ($ignore as $catignore) {
        unset ($rows[$catignore]);
      }
    }
    usort($rows, "SortCatArray");
    foreach ($rows as $key => $obj) {
      if ($cname != 'parent' || ($cname == 'parent' && $obj->cid != $orig)) {
        $output .= "<option value=\"".$obj->cid."\"";
        if ($cat == $obj->cid) {
          $output .= " selected=\"selected\"";
        }
        $output .= ">".$obj->name."</option>\n";
      }
    }
    $output .= "</select>\n";
    $rows = array();
    return $output;
  }

  function orderCatg($uid, $inc) {
    $app = JFactory::getApplication('administrator');
    JRequest::checkToken() or jexit( 'Invalid Token' );
    $db =& JFactory::getDBO();
    $row = new DatsoCategories($db);
    $row->load($uid);
    if (!$row->move($inc, 'parent = '.$db->Quote($row->parent))) {
      $this->setError($row->getError());
      return false;
    }
    $app->redirect('index.php?option=com_datsogallery&task=showcatg');
  }

  function saveCatOrder(&$cid) {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $total = count($cid);
    $order = JRequest::getVar('order', array(0), 'post', 'array');
    JArrayHelper::toInteger($order, array(0));
    $row = new DatsoCategories($db);
    for ($i = 0; $i < $total; $i++) {
      $row->load((int) $cid[$i]);
      if ($row->ordering != $order[$i]) {
        $row->ordering = $order[$i];
        if (!$row->store()) {
          JError::raiseError(500, $db->getErrorMsg());
        }
      }
    }
    $app->redirect('index.php?option=com_datsogallery&task=showcatg', JText::_('COM_DATSOGALLERY_ORDERING_OK'));
  }

  function showVotes() {
    JToolBarHelper::title(JText::_('COM_DATSOGALLERY_RESET_VOTES_TITLE'), 'tb-resetvotes');
    JToolBarHelper::back();
    $document =& JFactory::getDocument();
    $document->addStyleSheet(JURI::root(true).'/components/com_datsogallery/libraries/plupload/css/plupload.queue.css');
  ?>
  <script type="text/javascript">
    function confirmSubmit() {
    var agree=confirm('<?php echo JText::_('COM_DATSOGALLERY_SURE_RESET_VOTES');?>');
    if (agree)
    return true;
    else
    return false;
    }
  </script>

  <div class="stepsform">
    <div class="plupload_header_title">
      <?php echo JText::_('COM_DATSOGALLERY_RESET_VOTES_TITLE');?>
    </div>

    <form action="index.php" name="adminForm" method="post" id="adminForm">
      <table width="100%" style="text-align: center">
        <tr>
          <td>
            <p><?php echo JText::_('COM_DATSOGALLERY_RESET_VOTES_DESCRIPTION');?></p>
            <p style="padding-left:210px">
              <button type="submit" class="act" name="reset" onclick="return confirmSubmit();return false;"><?php echo JText::_('COM_DATSOGALLERY_RESET');?></button>
              <button type="button" class="act" onclick="javascript:history.go(-1);"><?php echo JText::_('COM_DATSOGALLERY_CANCEL');?></button>
            </p>
          </td>
        </tr>
      </table>
      <input type="hidden" name="option" value="com_datsogallery" />
      <input type="hidden" name="task" value="reset" />
    </form>
  </div>
  <?php
  }

  function resetVotes() {
    $app = JFactory::getApplication('administrator');
    $db = JFactory::getDBO();
    $db->setQuery("delete from #__datsogallery_votes");
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
    $db->setQuery("update #__datsogallery set imgvotes='0', imgvotesum='0'");
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
    $app->redirect("index.php?option=com_datsogallery&task=pictures", JText::_('COM_DATSOGALLERY_RESET_FINISHED'));
  }

  function CategoryListSettings($cat, $cname, $extras = "", $levellimit = 999) {
    $app = JFactory::getApplication('administrator');
    $db =& JFactory::getDBO();
    $db->setQuery("SELECT *, cid as id, name AS title, parent AS parent_id FROM #__datsogallery_catg WHERE parent = 0 ORDER BY name");
    $rows = $db->loadObjectList();
    $children = array();
    asort($children);
    foreach ($rows as $row) {
      $parent = $row->parent_id;
      $list = @$children[$parent] ? $children[$parent] : array();
      array_push($list, $row);
      $children[$parent] = $list;
    }
    $list = JHTML::_('menu.treerecurse', 0, '&nbsp;', array(), $children, $levellimit);
    $items = array();
    $items[] = JHTML::_('select.option', '', '- '.JText::_('COM_DATSOGALLERY_SELECT_CATEGORY').' -');
    foreach ($list as $item) {
      $items[] = JHTML::_('select.option', $item->id, $item->treename);
    }
    $parlist = selectList2($items, $cname, 'class="inputbox" '.$extras, 'value', 'text', $cat);
    return $parlist;
  }

  function CategoryList($cat, $cname, $extras = "", $levellimit = 999) {
    $app = JFactory::getApplication('administrator');
    $db =& JFactory::getDBO();
    $db->setQuery("SELECT *, cid as id, name AS title, parent AS parent_id FROM #__datsogallery_catg ORDER BY name");
    $rows = $db->loadObjectList();
    $children = array();
    asort($children);
    foreach ($rows as $row) {
      $parent = $row->parent_id;
      $list = @$children[$parent] ? $children[$parent] : array();
      array_push($list, $row);
      $children[$parent] = $list;
    }
    $list = JHTML::_('menu.treerecurse', 0, '&nbsp;', array(), $children, $levellimit);
    $items = array();
    $text = (JRequest::getCmd('task') == 'settings') ? JText::_('JNO') : JText::_('JALL');
    $items[] = JHTML::_('select.option', '', '- '.$text.' -');
    foreach ($list as $item) {
      $items[] = JHTML::_('select.option', $item->id, $item->treename);
    }
    $parlist = selectList2($items, $cname, 'class="inputbox" '.$extras, 'value', 'text', $cat);
    return $parlist;
  }

  function selectList2(&$arr, $tag_name, $tag_attribs, $key, $text, $selected) {
    reset($arr);
    $html = "\n<select name=\"$tag_name\" $tag_attribs>";
    for ($i = 0, $n = count($arr); $i < $n; $i++) {
      $k = $arr[$i]->$key;
      $t = $arr[$i]->$text;
      $id = @$arr[$i]->id;
      $extra = '';
      $extra .= $id ? " id=\"".$arr[$i]->id."\"" : '';
      if (is_array($selected)) {
        foreach ($selected as $obj) {
          $k2 = $obj;
          if ($k == $k2) {
            $extra .= " selected=\"selected\"";
            break;
          }
        }
      }
      else {
        $extra .= ($k == $selected ? " selected=\"selected\"" : '');
      }
      $html .= "\n\t<option value=\"".$k."\"$extra>".$t."</option>";
    }
    $html .= "\n</select>\n";
    return $html;
  }

  function GetNumberOfParents($cat) {
    $db =& JFactory::getDBO();
    $db->setQuery('SELECT COUNT(cid) FROM #__datsogallery_catg WHERE parent = '.$cat);
    $total = $db->loadResult();
    return $total;
  }

  function GetNumberOfLinks($cat) {
    $db =& JFactory::getDBO();
    $db->setQuery('SELECT COUNT(id) FROM #__datsogallery WHERE catid = '.$cat);
    $total = $db->loadResult();
    return $total;
  }

  function getWords() {
    $lang = JFactory::getLanguage();
    $datsolang = strtolower($lang->getName());
    $datsolang = preg_replace("/\((.*?)\)/si", '', $datsolang);
    $filewords = JPATH_SITE.DS.'components'.DS.'com_datsogallery'.DS.'words2ignore-'.$datsolang.'.txt';
    if (!JFile::exists($filewords)) {
      $words = "a i if of on to and can lot has the you when with";
      JFile::write($filewords, $words);
    }
    echo showWords($filewords);
  }

  function showWords($filewords) {
    $app = JFactory::getApplication('administrator');
    $filesource = JFile::read($filewords);
  ?>
<table cellpadding='4' cellspacing='0' border='0' width='100%'>
  <tr>
    <td><textarea cols='42' rows='10' name='filesource'><?php echo $filesource;?></textarea></td>
  </tr>
</table>
    <?php
    }

    function saveWords() {
      $app = JFactory::getApplication('administrator');
      $lang = JFactory::getLanguage();
      $datsolang = strtolower($lang->getName());
      $datsolang = preg_replace("/\((.*?)\)/si", '', $datsolang);
      $filewords = JRequest::getVar('filewords', 'post');
      $filesource = JRequest::getVar('filesource', '', 'post', 'string', JREQUEST_ALLOWRAW);
      $filewords = JPATH_SITE.DS.'components'.DS.'com_datsogallery'.DS.'words2ignore-'.$datsolang.'.txt';
      JFile::write($filewords, $filesource);
    }

    function movePic($cid) {
      $app = JFactory::getApplication('administrator');
      JToolBarHelper::title(JText::_('COM_DATSOGALLERY_MASS_MOVING_TITLE'), 'tb-move');
      JToolBarHelper::custom('movepicres', 'dg-save.png', 'dg-save.png', JText::_('COM_DATSOGALLERY_SAVE'), false);
      JToolBarHelper::spacer();
      JToolBarHelper::custom('', 'dg-cancel.png', 'dg-cancel.png', JText::_('COM_DATSOGALLERY_CANCEL'), false);
      $db =& JFactory::getDBO();
      $catid = JRequest::getVar('catid', 0, '', 'int');
      $cid = JRequest::getVar('cid', array(0), '', 'array');
      if (count($cid) > 0)
        $cids = implode(',', $cid);
      $db->setQuery('SELECT * FROM #__datsogallery WHERE id IN ('.$cids.')');
      if ($db->query()) {
        $rows = $db->loadObjectList();
        $options = array(JHTML::_('select.option', '1', JText::_('COM_DATSOGALLERY_SELECT_CATEGORY')));
        $lists['catgs'] = ShowDropDownCategoryList($catid, 'catid', 'class="inputbox" size="1" ');
        datsogallery_html::movePic($rows, $lists);
      }
    }

    function movePicResult($id) {
      $app = JFactory::getApplication('administrator');
      $db =& JFactory::getDBO();
      $id = JRequest::getVar('id', array(0), '', 'array');
      $movepic = JRequest::getVar('catid', 'post');
      if (!$movepic || $movepic == 0) {
        echo "<script> alert('".JText::_('COM_DATSOGALLERY_MUST_SELECT_CATEGORY')."'); window.history.go(-1);</script>\n";
        exit;
      }
      else {
        $pic = new DatsoImages($db);
        $pic->id = $pic->getNextOrder('catid = ' . $movepic);
        $pic->dgMove($id, $movepic);
        $ids = implode(',', $id);
        $total = count($id);
        $cat = new DatsoCategories($db);
        $cat->load($movepic);
        $msg = $total.JText::_('COM_DATSOGALLERY_TOTAL_PICS_MOVED').$cat->name;
        $app->redirect('index.php?option=com_datsogallery&task=pictures', $msg);
      }
    }

    function writDir($folder) {
      jimport('joomla.filesystem.folder');
      $writeable = dgTip(JText::_('COM_DATSOGALLERY_DIR_IS_WRITEABLE'), 'dg-accept-icon.png');
      $unwriteable = dgTip(JText::_('COM_DATSOGALLERY_DIR_IS_UNWRITEABLE'), 'dg-exclamation-icon.png');
      echo JFolder::exists($folder) ? $writeable : $unwriteable;
    }

    function dgProtect($dirtoprotect) {
      jimport('joomla.filesystem.file');
      require_once (JPATH_COMPONENT.DS.'config.datsogallery.php');
      $htaccess = "Order Deny,Allow\nDeny from All";
      JFile::write(JPATH_SITE.DS.$dirtoprotect.'/.htaccess', $htaccess);
    }

    function dgUnprotect($dirtounprotect) {
      jimport('joomla.filesystem.file');
      require_once (JPATH_COMPONENT.DS.'config.datsogallery.php');
      if (JFile::exists(JPATH_SITE.DS.$dirtounprotect.DS.'.htaccess')) {
        JFile::delete(JPATH_SITE.DS.$dirtounprotect.DS.'.htaccess');
      }
    }

    function dgTip($dgtip, $image = 'dg-info-icon.png', $text = '', $href = '#', $class = '', $link = 0) {
      $app = JFactory::getApplication('administrator');
      $document = & JFactory::getDocument();
      if (!$text) {
        $image = JURI::root(true).'/components/com_datsogallery/images/customtheme/'.$image;
        $text = '<img src="'.$image.'" border="0" alt="dgtip" style="vertical-align:middle" />';
      }
      $style = 'style="cursor:help"';
      if ($href) {
        $style = '';
      }
      else {
        $href = '#';
      }
      $title = 'id="'.jsspecialchars($dgtip).'"';
      $tip = "";
      if ($link) {
        $tip .= '<a href="'.$href.'"'.$class.' class="dgtip" '.$title.' '.$style.'>'.$text.'</a>';
      }
      else {
        $tip .= '<span class="dgtip" '.$title.' '.$style.$class.'>'.$text.'</span>';
      }
      return $tip;
    }

    function removeFile($srcFilename, $srcFilePath) {
      $removeFilename = $srcFilePath.$srcFilename;
      if (JFile::delete($removeFilename)) {
        return true;
      }
      else {
        return false;
      }
    }

    function dgImgId($catid, $imgext) {
      return substr(strtoupper(md5(uniqid(time()))), 5, 12).'-'.$catid.'.'.strtolower($imgext);
    }

    function jsspecialchars($s) {
      $r = str_replace(array('\\', '"', "'"), array('\\\\', '&quot;', "&#039;"), $s);
      return htmlspecialchars($r, ENT_QUOTES);
    }

    function format_filesize($tfilesize) {
      global $dgfilesize;
      $format = array(JText::_('COM_DATSOGALLERY_FILESIZE_BYTES'), JText::_('COM_DATSOGALLERY_FILESIZE_KB'), JText::_('COM_DATSOGALLERY_FILESIZE_MB'), JText::_('COM_DATSOGALLERY_FILESIZE_GB'));
      $i = 0;
      while ($tfilesize >= 1024) {
        $i++;
        $tfilesize = $tfilesize / 1024;
      }
      return number_format($tfilesize, ($i ? 2 : 0), ",", ".")." ".$format[$i];
    }

    function getDatso($ext, $ver){
        $remote_url = 'http://www.datso.fr/latest/versions.xml';
        $search_for = '<'.$ext;
        $part = '';
        if ($handle = @fopen($remote_url, "r")) {
            while (!feof($handle)) {
                $part .= fread($handle, 100);
                $pos = strpos($part, $search_for);
                if ($pos === false)
                continue;
                else
                break;
            }
            $part .= fread($handle, 100);
            fclose($handle);
        }
        $str = explode($search_for, $part);
        $str = array_shift(explode('"/>', $str[1]));
        $str = explode($ver.'="', $str);

        return $str[1];
    }

    function getmicrotime() {
      list($usec, $sec) = explode(" ", microtime());
      return ((float) $usec + (float) $sec);
    }

    function showBlacklist() {
      $app = JFactory::getApplication('administrator');
      JToolBarHelper::title(JText::_('COM_DATSOGALLERY_BLACKLIST_MANAGER_TITLE'), 'tb-blacklist');
      JToolBarHelper::custom('publishbl', 'dg-show.png', 'dg-show.png', JText::_('COM_DATSOGALLERY_PUBLISH'));
      JToolBarHelper::spacer();
      JToolBarHelper::custom('unpublishbl', 'dg-hide.png', 'dg-hide.png', JText::_('COM_DATSOGALLERY_UNPUBLISH'));
      JToolBarHelper::spacer();
      JToolBarHelper::custom('removebl', 'dg-delete.png', 'dg-delete.png', JText::_('COM_DATSOGALLERY_DELETE'));
      $db =& JFactory::getDBO();
      $limit = $app->getUserStateFromRequest('com_datsogallery.limit', 'limit', 10);
      $limitstart = $app->getUserStateFromRequest('com_datsogallery.limitstart', 'limitstart', 0);
      $search = $app->getUserStateFromRequest('com_datsogallery.search', 'search', '', 'string');
      $where = array();
      if ($search) {
        $where[] = "ip LIKE '%".$search."%' ";
      }
      $db->setQuery("SELECT COUNT(*) FROM #__datsogallery_blacklist ".(count($where) ? "WHERE ".implode(' AND ', $where) : ""));
      $total = $db->loadResult();
      echo $db->getErrorMsg();
      if ($limit > $total) {
        $limitstart = 0;
      }
      $db->setQuery("SELECT * from #__datsogallery_blacklist ".(count($where) ? "WHERE ".implode(' AND ', $where) : "")." ORDER BY id "." LIMIT $limitstart, $limit");
      $rows = $db->loadObjectList();
      if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
      }
      jimport('joomla.html.pagination');
      $pageNav = new JPagination($total, $limitstart, $limit);
      datsogallery_html::showBlacklist($rows, $search, $pageNav);
    }

    function publishBlacklist($id = null, $publish = 1) {
      $app = JFactory::getApplication('administrator');
      $db = JFactory::getDBO();
      $id = JRequest::getVar('id', array(), 'post', 'array');
      if (!is_array($id) || count($id) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('".JText::_('COM_DATSOGALLERY_SELECT_AN_ITEM')." $action'); window.history.go(-1);</script>\n";
        exit;
      }
      $ids = implode(',', $id);
      $db->setQuery('UPDATE #__datsogallery_blacklist SET published = '.$publish.' WHERE id IN ('.$ids.')');
      if (!$db->query()) {
        echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit ();
      }
      $app->redirect('index.php?option=com_datsogallery&task=blacklist');
    }

    function removeBlacklist() {
      $app = JFactory::getApplication('administrator');
      $db =& JFactory::getDBO();
      $id = JRequest::getVar('id', array(), 'post', 'array');
      if (count($id) < 1) {
        $msg = JText::_('Select an item to delete');
        $app->redirect('index.php?option=com_datsogallery&task=blacklist', $msg, 'error');
      }
      $ids = implode(',', $id);
      $db->setQuery('DELETE FROM #__datsogallery_blacklist WHERE id IN ('.$ids.')');
      if (!$db->query()) {
        JError::raiseError(500, $db->getErrorMsg());
        return false;
      }
      $app->redirect('index.php?option=com_datsogallery&task=blacklist');
    }

    function getDirectorySize($path) {
      $totalsize = 0;
      $totalcount = 0;
      $dircount = 0;
      if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
          $nextpath = $path.'/'.$file;
          if ($file != '.' && $file != '..' && !is_link($nextpath)) {
            if (is_dir($nextpath)) {
              $dircount++;
              $result = getDirectorySize($nextpath);
              $totalsize += $result['size'];
              $totalcount += $result['count'];
              $dircount += $result['dircount'];
            }
            elseif (is_file($nextpath)) {
              $totalsize += filesize($nextpath);
              $totalcount++;
            }
          }
        }
      }
      closedir($handle);
      $total['size'] = $totalsize;
      $total['count'] = $totalcount;
      $total['dircount'] = $dircount;
      return $total;
    }

    function sizeFormat($size) {
      if ($size < 1024) {
        return $size." bytes";
      }
      else
        if ($size < (1024 * 1024)) {
          $size = round($size / 1024, 1);
          return $size." KB";
        }
        else
          if ($size < (1024 * 1024 * 1024)) {
            $size = round($size / (1024 * 1024), 1);
            return $size." MB";
          }
          else {
            $size = round($size / (1024 * 1024 * 1024), 1);
            return $size." GB";
      }
    }

    function checkMooPath() {
     $document = JFactory::getDocument();
     $moo_core = 'media/system/js/mootools-core.js';
     $moo_more = 'media/system/js/mootools-more.js';
     $core = JURI::root(true).'/'.$moo_core;
     $more = JURI::root(true).'/'.$moo_more;
     unset ($document->_scripts[$core]);
     unset ($document->_scripts[$more]);
     $document->addScript($core);
     $document->addScript($more);
    }

function dgUpload() {
  $app = JFactory::getApplication('administrator');
  jimport('joomla.filesystem.folder');
  jimport('joomla.filesystem.file');
  require (JPATH_COMPONENT.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT.DS.'class.datsogallery.php');
  require_once (JPATH_COMPONENT.DS.'images.datsogallery.php');
  $db = JFactory::getDBO();
  $user = JFactory::getUser();
  $catid = JRequest::getVar('catid', 0, 'post', 'int');
  jimport('joomla.access.access');
  $tmp_dir = JPATH_SITE.DS.'tmp'.DS.$user->id;
  dgChmod($tmp_dir, 0777);
  $dir = opendir($tmp_dir);
  while (false != ($file = readdir($dir))) {
    $files[] = $file;
  }
  sort($files);
  $i = 0;
  foreach ($files as $file) {
    if ($file != '.'
    && $file != '..'
    && (strcasecmp($file, 'index.html') != 0)
    && (strcasecmp($file, '__MACOSX') != 0)) {
      $i++;
      $count = ($i > 1) ? ' '.$i : '';
      $origfilename = $file;
      $imagetype = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
      $imginfo = getimagesize($tmp_dir.DS.$origfilename);
      $ext = strtolower($imagetype[$imginfo[2]]);
      if (is_dir($tmp_dir.DS.$origfilename)) {
        JFolder::delete($tmp_dir);
        $msg = JText::sprintf('COM_DATSOGALLERY_ZIP_PACKAGE_ERROR','<a href="http://www.datso.fr/en/video-guidelines.html" target="_blank">','</a>');
        $app->redirect('index.php?option=com_datsogallery&task=pictures',$msg);
      }
      if (!$ext) {
        JFile::delete($tmp_dir.DS.$origfilename);
      }
      else {
        $newfilename = ($ad_fname) ? $origfilename : dgImgId($catid, $ext);
        JFile::copy($tmp_dir.DS.$origfilename, JPath::clean(JPATH_SITE.$ad_pathoriginals.DS.$newfilename));
        JFile::delete($tmp_dir.DS.$origfilename);
        $row = new DatsoImages($db);
        if (!$row->bind(JRequest::get('post'))) {
          return JError::raiseWarning(500, $row->getError());
        }
        $row->ordering = $row->getNextOrder('catid = '.(int) $catid);
        $row->imgtitle = JRequest::getVar('gentitle').$count;
        $row->imgtext = JRequest::getVar('gendesc');
        $row->imgauthor = JRequest::getVar('genimgauthor');
        $row->imgauthorurl = JRequest::getVar('genimgauthorurl');
        $row->imgdate = mktime();
        $row->owner_id = $user->id;
        $row->published = 1;
        $row->approved = 1;
        $row->imgoriginalname = $newfilename;
        $row->useruploaded = 0;
        if (!$row->store()) {
          JError::raiseError(500, $row->getError());
        }
      }
    }
  }
  closedir($dir);
  if (JFolder::exists($tmp_dir)) {
    JFolder::delete($tmp_dir);
  }
  $app->redirect("index.php?option=com_datsogallery&task=pictures");
}

    function bytes($a) {
      $unim = array("b", "kb", "mb", "gb", "tb", "pb");
      $c = 0;
      while ($a >= 1024) {
        $c++;
        $a = $a / 1024;
      }
      return number_format($a).$unim[$c];
    }

    if (JFile::exists(JPATH_COMPONENT.DS.'update.php')) {
      require_once (JPATH_COMPONENT.DS.'update.php');
    }
    if (JFile::exists(JPATH_COMPONENT.DS.'update.php')) {
      JFile::delete(JPATH_COMPONENT.DS.'update.php');
    }

    function dgclean($ids = array()) {
      $conf = JFactory::getConfig();
      $options = array(
        'defaultgroup' => '',
        'storage' => $conf->get('cache_handler', ''),
        'caching' => true,
        'cachebase' => $conf->get('cache_path', JPATH_SITE.DS.'cache')
      );
      $cache = JCache::getInstance('', $options);
      $dg_caches = array_keys($cache->getAll());
      foreach ($dg_caches as $key => $group) {
        $cache->clean($group);
      }
    }

    function wmUpload() {
      $html = require_once (JPATH_COMPONENT.DS.'includes'.DS.'wmupload.php');
      return $html;
    }

    function addSubMenus($vName = '') {
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_PICTURES'),
      'index.php?option=com_datsogallery',
      $vName == ''
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_CATEGORIES'),
      'index.php?option=com_datsogallery&task=showcatg',
      $vName == 'showcatg'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_COMMENTS'),
      'index.php?option=com_datsogallery&task=comments',
      $vName == 'comments'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_BLACKLIST'),
      'index.php?option=com_datsogallery&task=blacklist',
      $vName == 'blacklist'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_UPLOAD'),
      'index.php?option=com_datsogallery&task=showupload',
      $vName == 'showupload'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_IMPORT'),
      'index.php?option=com_datsogallery&task=batchimport',
      $vName == 'batchimport'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_CONFIGURATION'),
      'index.php?option=com_datsogallery&task=settings',
      $vName == 'settings'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_VOTES'),
      'index.php?option=com_datsogallery&task=resetvotes',
      $vName == 'resetvotes'
      );
      JSubMenuHelper::addEntry(
      JText::_('COM_DATSOGALLERY_TRANSACTIONS'),
      'index.php?option=com_datsogallery&task=transactions',
      $vName == 'transactions'
      );
   }

   function isJ25(){
    jimport('joomla.version');
    $version = new JVersion();
    return $version->isCompatible('2.5.0');
   }
  ?>
<script type="text/javascript">jQuery(".dgtip").dgtooltip({gravity: 'w', fade: true, html: true});</script>