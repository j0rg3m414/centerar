<?php
/**
* @package   Widgetkit
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// remove obsolete files
foreach(array(
    'image/image.js',
    'image/image.php',
    'image/image.xml',
    'link/link.php',
    'link/link.xml') as $file) {
    if (JFile::exists($path = dirname(__FILE__).'/elements/'.$file)) {
        JFile::delete($path);
    }
}