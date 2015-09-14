<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.string');

check_catname();

function check_catname() {
  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  $user = JFactory::getUser();
  if ($user_categories && $user->id){
  $post = JRequest::get('post');
  $catname = JRequest::getVar('name', '', 'post', 'string');
  $catname = JString::strtolower(trim($catname));
  $db = JFactory::getDBO();
  $db->setQuery('SELECT COUNT(cid)'
  .' FROM #__datsogallery_catg'
  .' WHERE user_id = '.(int) $user->id
  );
  $count = $db->loadResult();
  $db->setQuery('SELECT name'
  . ' FROM #__datsogallery_catg'
  . ' WHERE name = ' . $db->Quote($catname)
  . ' AND user_id = ' . $user->id
  );
  $name = $db->loadResult();
  if ($count >= $ad_max_categories) {
    echo 'maxcat';
  }
  elseif (JString::strtolower($name) == $catname) {
    echo 'exist';
  }
  else {
    saveCatg();
    refrCats($catname);
  }
  }
  else {
    exit;
  }
}

function saveCatg() {
  require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
  $is_admin = array(7,8);
  $db = JFactory::getDBO();
  $user = JFactory::getUser();
  $userGroups = JAccess::getGroupsByUser($user->id, true);
  $post = JRequest::get('post');
  //$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
  $row = new DatsoCategories($db);
  if (!array_intersect($is_admin, $userGroups)) {
    $category = explode(',', $ad_category);
    $getfirst = array_shift($category);
    $db->setQuery('SELECT cid'
    .' FROM #__datsogallery_catg'
    .' WHERE user_id = '.(int) $user->id
    .' AND parent = '.$ad_category
    );
    $result = $db->loadResult();
    $row->parent = ($result) ? $result : $ad_category;
    $row->ordering = $row->getNextOrder('parent = ' . $row->parent);
  }
  else {
    $row->ordering = $row->getNextOrder('parent = 0');
  }
  $row->user_id = $user->id;
  $row->approved = (!$user_categories_approval || array_intersect($is_admin, $userGroups)) ? 1 : 0;
  $row->published = 1;
  jimport('joomla.utilities.date');
  $dtz  = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
  $date = new JDate($row->date);
  $date->setTimezone($dtz);
  $row->date = $date->toMySQL(true);
  if (!$row->bind($post)) {
    JError::raiseError(500, $row->getError());
  }
  if (!$row->check()) {
    JError::raiseError(500, $row->getError());
  }
  if (!$row->store()) {
    JError::raiseError(500, $row->getError());
  }
  if ($ad_category_notify && !array_intersect($is_admin, $userGroups)):
    categoryNotify($user->username, $post['name']);
  endif;
}

function refrCats($catname) {
  $db = JFactory::getDBO();
  $user = JFactory::getUser();
  $db->setQuery('SELECT cid'
  . ' FROM #__datsogallery_catg'
  . ' WHERE name = ' . $db->Quote($catname)
  . ' AND user_id = ' . $user->id
  );
  $catid = $db->loadResult();
  $clist = ShowDropDownCategoryList($catid, 'catid', 'id="catname" style="width:340px" onchange="userCat(this.value);"');
  echo $clist;
}
?>