<?php
  defined('_JEXEC') or die('Direct Access to this location is not allowed.');

  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'images.datsogallery.php');

  $db       = JFactory::getDBO();
  $user     = JFactory::getUser();
  $groups   = implode(',', $user->getAuthorisedViewLevels());
  $id       = JRequest::getVar('id', 0, 'get', 'int');
  $catid    = JRequest::getVar('catid', 0, 'get', 'int');

  if ($id) {
    $db->setQuery('SELECT c.access'
    .' FROM #__datsogallery_catg AS c'
    .' LEFT JOIN #__datsogallery AS a'
    .' ON a.catid = c.cid'
    .' WHERE a.id = '.$id
    .' AND c.approved = 1'
    .' AND c.access IN ('.$groups.')'
    );
    $access = $db->loadObject();

    if (!$access) {
      die(JText::_('COM_DATSOGALLERY_NOT_ACCESS_THIS_DIRECTORY'));
    }
    else {
      $db->setQuery('SELECT a.imgoriginalname'
      .' FROM #__datsogallery AS a'
      .' WHERE a.id = '.$id
      .' AND a.catid = '.$catid
      );
      $image = $db->loadResult();
    }

    resize($image, $ad_orgwidth, $ad_orgheight, 0, 0, $ad_showwatermark, $catid);
    $cacheimage = getCacheFile($image, $ad_orgwidth, $ad_orgheight, $catid);
    $imagesize = getimagesize($cacheimage);
    $expires = 60*60*24*14;
    header('Pragma: public');
    header('Cache-Control: maxage='.$expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
    header('Last-Modified: '.date('r'));
    header('Accept-Ranges: bytes');
    header('Content-Length: '.(filesize($cacheimage)));
    header('Content-Type: '.$imagesize['mime']);
    ob_clean();
    readfile($cacheimage);
    exit;
  }
