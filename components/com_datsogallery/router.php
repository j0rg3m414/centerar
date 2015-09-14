<?php
  defined('_JEXEC') or die();

  function DatsoGalleryBuildRoute(&$query) {
    $segments = array();

    if (!empty ($query['task'])) {
      switch ($query['task']) {

        case 'category' :
          $segments[] = 'view-album';
          $segments[] = $query['catid'];
          /*$category_alias = categoryTitle($query['catid']);
          $segments[] = $category_alias;*/
          unset ($query['catid']);
          break;

        case 'image' :
          $segments[] = 'view-photo';
          if (isset ($query['catid'])) {
          $segments[] = $query['catid'];
          /*$category_alias = categoryTitle($query['catid']);
          $segments[] = $category_alias;*/
          unset ($query['catid']);
          }
          if (isset ($query['id'])) {
          $segments[] = $query['id'];
          /*$photo_alias = photoTitle($query['id']);
          $segments[] = $photo_alias;*/
          unset ($query['id']);
          }
          break;

        case 'userpanel' :
          $segments[] = 'my-photos';
          break;

        case 'showupload' :
          $segments[] = 'add-photo';
          break;

        case 'editpic' :
          $segments[] = 'edit-photo';
          if (isset ($query['uid']))
            $segments[] = $query['uid'];
          unset ($query['uid']);
          break;

        case 'savepic' :
          $segments[] = 'save-photo';
          break;

        case 'deletepic' :
          $segments[] = 'remove-photo';
          if (isset ($query['uid']))
            $segments[] = $query['uid'];
          unset ($query['uid']);
          break;

        case 'search' :
          $segments[] = 'search-results';
          break;

        case 'tag' :
          $segments[] = 'tag';
          if (isset ($query['tagval']))
            $segments[] = $query['tagval'];
          unset ($query['tagval']);
          break;

        case 'favorites' :
          $segments[] = 'my-favorites';
          break;

        case 'lastadded' :
          $segments[] = 'last-added';
          break;

        case 'popular' :
          $segments[] = 'popular-photos';
          break;

        case 'rating' :
          $segments[] = 'best-rated';
          break;

        case 'lastcommented' :
          $segments[] = 'last-commented';
          break;

        case 'downloads' :
          $segments[] = 'most-downloaded';
          break;

        case 'owner' :
          $segments[] = 'photos-by-owner';
          if (isset ($query['op']))
            $segments[] = $query['op'];
          unset ($query['op']);
          break;

        case 'checkout' :
          $segments[] = 'checkout';
          break;

        case 'purchases' :
          $segments[] = 'my-purchases';
          break;

        case 'download' :
          $segments[] = 'download-photo';
          if (isset ($query['id']))
            $segments[] = $query['id'];
          unset ($query['id']);
          break;

        case 'notify' :
          $segments[] = 'notify';
          break;

        case 'complete' :
          $segments[] = 'complete';
          break;

        case 'cancel' :
          $segments[] = 'cancel';
          break;
      }
    }
    unset ($query['view']);
    unset ($query['task']);
    return $segments;
  }

  function DatsoGalleryParseRoute($segments) {
    $vars = array();
    $segments[0] = str_replace(':', '-', $segments[0]);
    switch ($segments[0]) {

      case 'view-album' :
        $vars['task'] = 'category';
        $vars['catid'] = $segments[1];
        break;

      case 'view-photo' :
      $vars['task'] = 'image';
        if (isset ($segments[1])) {
        $vars['catid'] = $segments[1];
      }
      if (isset ($segments[2])) {
        $vars['id'] = $segments[2];
      }
        break;

      case 'my-photos' :
        $vars['task'] = 'userpanel';
        break;

      case 'add-photo' :
        $vars['task'] = 'showupload';
        break;

      case 'edit-photo' :
        $vars['task'] = 'editpic';
        $vars['uid'] = $segments[1];
        break;

      case 'save-photo' :
        $vars['task'] = 'savepic';
        break;

      case 'remove-photo' :
        $vars['task'] = 'deletepic';
        $vars['uid'] = $segments[1];
        break;

      case 'download-photo' :
        $vars['task'] = 'download';
        $vars['id'] = $segments[1];
        break;

      case 'search-results' :
        $vars['task'] = 'search';
        break;

      case 'tag' :
        $vars['task'] = 'tag';
        $vars['tagval'] = $segments[1];
        break;

      case 'my-favorites' :
        $vars['task'] = 'favorites';
        break;

      case 'last-added' :
        $vars['task'] = 'lastadded';
        break;

      case 'popular-photos' :
        $vars['task'] = 'popular';
        break;

      case 'best-rated' :
        $vars['task'] = 'rating';
        break;

      case 'last-commented' :
        $vars['task'] = 'lastcommented';
        break;

      case 'most-downloaded' :
        $vars['task'] = 'downloads';
        break;

      case 'photos-by-owner' :
        $vars['task'] = 'owner';
        $vars['op'] = $segments[1];
        break;

      case 'checkout' :
        $vars['task'] = 'checkout';
        break;

      case 'my-purchases' :
        $vars['task'] = 'purchases';
        break;

      case 'notify' :
        $vars['task'] = 'notify';
        break;

      case 'complete' :
        $vars['task'] = 'complete';
        break;

      case 'cancel' :
        $vars['task'] = 'cancel';
        break;
    }
    return $vars;
  }

  /*function categoryTitle($catid) {
    jimport('joomla.filter.output');
    $db = JFactory::getDBO();
    $query = "SELECT `name` FROM #__datsogallery_catg WHERE `cid` = ".(int) $catid;
    $db->setQuery($query);
    $category_name = $db->loadResult();
    if ($category_name == "")
      return;
    $category_alias = JFilterOutput::stringURLSafe($category_name);
    return $category_alias;
  }

  function photoTitle($id) {
    jimport('joomla.filter.output');
    $db = JFactory::getDBO();
    $query = "SELECT `imgtitle` FROM #__datsogallery WHERE `id` = ".(int) $id;
    $db->setQuery($query);
    $imgtitle = $db->loadResult();
    if ($imgtitle == "")
      return;
    $photo_alias = JFilterOutput::stringURLSafe($imgtitle);
    return $photo_alias;
  }*/
