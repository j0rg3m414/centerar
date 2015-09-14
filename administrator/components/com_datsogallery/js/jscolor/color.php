<?php
  defined('_JEXEC') or die('Restricted access');
  class JElementColor extends JElement {

    function fetchElement($name, $value, &$node, $control) {
      ob_start();
      $colorpicker = JURI::root().'modules/mod_datsogallery_ultimate/jscolor/jscolor.js';
      echo '<script src="'.$colorpicker.'"></script>';
      echo '<input name="'.$control.'['.$name.']" type="text" class="color" id="'.$control.$name.'" value="'.$value.'" size="8" />';
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    }

  }

?>