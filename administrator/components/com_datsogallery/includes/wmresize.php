<?php
defined('_JEXEC') or die();

class waterMark {

  function resize($file, $width = 0, $height = 0, $imagetype = false, $proportional = true, $output = 'file', $delete_original = true, $use_linux_commands = false) {
    if ($height <= 0 && $width <= 0) {
      return false;
    }
    $info = getimagesize($file);
    $image = '';
    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;
    if ($proportional) {
      if ($width == 0)
        $factor = $height / $height_old;
      elseif ($height == 0)
        $factor = $width / $width_old;
      else
        $factor = min($width / $width_old, $height / $height_old);
      $final_width = round($width_old * $factor);
      $final_height = round($height_old * $factor);
    }
    else {
      $final_width = ($width <= 0) ? $width_old : $width;
      $final_height = ($height <= 0) ? $height_old : $height;
    }
    switch ($info[2]) {

      case IMAGETYPE_PNG :
        $image = imagecreatefrompng($file);
        break;

      default :
        return false;
    }
    $image_resized = imagecreatetruecolor($final_width, $final_height);
    if ($info[2] == IMAGETYPE_PNG) {
      $trnprt_indx = imagecolortransparent($image);
      if ($trnprt_indx >= 0) {
        $trnprt_color = imagecolorsforindex($image, $trnprt_indx);
        $trnprt_indx = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($image_resized, 0, 0, $trnprt_indx);
        imagecolortransparent($image_resized, $trnprt_indx);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    if ($delete_original) {
      if ($use_linux_commands)
        exec('rm '.$file);
      else
        @unlink($file);
    }
    switch (strtolower($output)) {

      case 'browser' :
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
        break;

      case 'file' :
        $output = $file;
        break;

      case 'return' :
        return $image_resized;
        break;

      default :
        break;
    }
    if ($imagetype)
      $saveas = $imagetype;
    else
      $saveas = $info[2];
    switch ($saveas) {

      case IMAGETYPE_PNG :
        imagepng($image_resized, $output);
        break;

      default :
        return false;
    }
    return true;
  }

}