<?php
  defined('_JEXEC') or die;
  jimport('joomla.filesystem.folder');
  jimport('joomla.filesystem.file');
  jimport('joomla.environment.browser');

  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'class.datsogallery.php');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');
  require_once (JPATH_COMPONENT.DS.'datso.functions.php');
  $app = JFactory::getApplication('site');
  $db = JFactory::getDBO();
  $user	= JFactory::getUser();
  $groups = implode(',', $user->getAuthorisedViewLevels());
  $userGroups = JAccess::getGroupsByUser($user->id, true);
  $admins = array(7,8);
  $is_admin = array_intersect($admins, $userGroups);
  $document = JFactory::getDocument();
  $uri = JFactory::getURI();
  $menu = JSite::getMenu();
  $ids = $menu->getItems('link', 'index.php?option=com_datsogallery&view=datsogallery');
  $itemid = isset ($ids[0]) ? '&Itemid='.$ids[0]->id : '';
  $uid = JRequest::getVar('uid', 0, 'get', 'int');
  $op = JRequest::getVar('op', 0, 'get', 'int');
  $id = JRequest::getVar('id', 0, 'get', 'int');
  $catid = JRequest::getVar('catid', 0, 'get', 'int');
  $cmtid = JRequest::getVar('cmtid', 0, 'get', 'int');
  $sstring = JRequest::getVar('sstring', '', 'post');
  $sstring = JRequest::getVar('sstring');
  $task = JRequest::getCmd('task');
  $sstring = JString::strtolower($sstring);
  $return = $uri->toString();
  $url = 'index.php?option=com_users&view=login';
  $url .= '&return='.base64_encode($return);

  JResponse::setHeader('X-Powered-By', 'DatsoGallery');
  $document->setMetaData("imagetoolbar", "no", true);
  JHTML::stylesheet('datso.base.css.css', 'components/com_datsogallery/css/');
  $browser = &JBrowser::getInstance();
  $browserType = $browser->getBrowser();
  $browserVersion = $browser->getMajor();
  if(($browserType == 'msie') && ($browserVersion < 9)) {
    JHTML::stylesheet('datso.img.ie.css.css', 'components/com_datsogallery/css/');
  }
  else {
    JHTML::stylesheet('datso.img.css.css', 'components/com_datsogallery/css/');
  }
  if ($dg_theme == 'darktheme') {
    JHTML::stylesheet('datso.darktheme.css', 'components/com_datsogallery/css/');
  }
  elseif ($dg_theme == 'lighttheme') {
    JHTML::stylesheet('datso.lighttheme.css', 'components/com_datsogallery/css/');
  }
  else {
    JHTML::stylesheet('datso.customtheme.php', 'components/com_datsogallery/css/');
  }
  //checkMooPath();
  JHTML::script('jquery.min.js', 'components/com_datsogallery/libraries/');
  JHTML::script('datso.tooltip.js', 'components/com_datsogallery/libraries/');
  JHTML::script('datso.javascript.js', 'components/com_datsogallery/libraries/');
  $siteurl = 'var siteurl = "'.JURI::root(true).'/";';
  $document->addScriptDeclaration($siteurl);

  Breadcrumb($catid, $id, $task);

  if ($task == 'sbox') {
    require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.sbox.php');
    exit;
  }

  switch ($task) {

    case 'category' :
      viewCategory();
      break;

    case 'image' :
      require (JPATH_COMPONENT.DS.'includes'.DS.'datso.image.php');
      GalleryFooter();
      break;

    case 'search' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.search.php');
      break;

    case 'tag' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.tag.php');
      break;

    case 'favorites' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.favorites.php');
      break;

    case 'lastadded' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.lastadded.php');
      break;

    case 'popular' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.popular.php');
      break;

    case 'rating' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.rating.php');
      break;

    case 'lastcommented' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.lastcommented.php');
      break;

    case 'downloads' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.downloads.php');
      break;

    case 'owner' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.owner.php');
      break;

    case 'purchases' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.purchases.php');
      break;

    case 'datsopic' :
      require (JPATH_COMPONENT.DS.'includes'.DS.'datso.datsopic.php');
      break;

    case 'checkout' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.checkout.php');
      break;

    case "uploading" :
     require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.upload.php');
     break;

    case 'download' :
      DatsoDownload($id, $catid);
      break;

    case 'addtobasket' :
      addToBasket();
      break;

    case 'showbasket' :
      showBasket();
      break;

    case 'subtotal' :
      subTotal ();
      break;

    case 'total' :
      total ();
      break;

    case 'updateitems' :
      countItems();
      break;

    case 'complete' :
      echo $app->enqueueMessage(JText::_('COM_DATSOGALLERY_MSG_AFTER_SUCCESS_PURCHASE'));
      GalleryHeader();
      echo dgCategories($catid);
      GalleryFooter();
      break;

    case 'cancel' :
      echo $app->enqueueMessage(JText::_('COM_DATSOGALLERY_MSG_AFTER_CANCELED_ORDER'));
      GalleryHeader();
      echo dgCategories($catid);
      GalleryFooter();
      break;

    case 'notify' :
      ppIpn();
      break;

    case 'vote' :
      recordVote();
      break;

    case 'addtag' :
      addTag();
      break;

    case 'removetag' :
      removeTag();
      break;

    case 'showtags' :
      showTags($id);
      break;

    case 'addtofavorites' :
      addToFavorites();
      break;

    case 'editpic' :
      GalleryHeader();
      editPic($uid);
      GalleryFooter();
      break;

    case 'edittitle' :
      editImageTitle();
      break;

    case 'editdesc' :
      editImageDesc();
      break;

    case 'savepic' :
      savePic();
      break;

    case 'deletepic' :
      deletePic($uid);
      break;

    case 'commentadd' :
      commentAdd();
      break;

    case 'deletecomment' :
      delComment();
      break;

    case 'approvecomment' :
      approveComment();
      break;

    case 'unapprovecomment' :
      unapproveComment();
      break;

    case 'spamcomment' :
      spamComment();
      break;

    case 'editcomment' :
      editComment();
      break;

    case "showupload" :
      if (!$user->id) {
        $app->redirect($url, JText::_('You must login first') );
      }
      showUpload();
      break;

    case "upload" :
      batchUpload ();
      break;

    case "userpanel" :
      userPanel();
      break;

    case "send2friend" :
      send2friend();
      break;

    case 'captcha' :
      captcha();
      break;

    case 'checkcatname' :
      require_once (JPATH_COMPONENT.DS.'includes'.DS.'datso.usercategory.php');
      break;

    default :
      GalleryHeader();
      echo dgCategories($catid);
      GalleryFooter();
      break;
  }
  $tasks = array('showupload','editpic');
  $gravity = (in_array($task,$tasks))?'w':'s';
  echo "<script type=\"text/javascript\">datso(\".dgtip\").dgtooltip({gravity: '".$gravity."', fade: true, html: true});</script> ";
?>
