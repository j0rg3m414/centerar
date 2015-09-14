<?php
/**
* @package   Widgetkit
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$rel	= !empty($rel) ? 'rel="' . $rel .'"' : '';

echo '<a href="'.JRoute::_($this->get('value', '')).'" title="'.$this->getTitle().'" '.$target.' '. $rel .'>'.$this->getText().'</a>';
