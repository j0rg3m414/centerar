<?php
define( '_JEXEC', 1 );
define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', '../../../');
require (PATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_datsogallery'.DS.'config.datsogallery.php');
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');
header('Content-type: text/css; charset: UTF-8');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
?>

.dg_head_background {
  background-color: #<?php echo $dg_head_background; ?>;
  border: 1px solid #<?php echo $dg_border; ?>;
  color: #<?php echo $dg_head_color; ?>;
}

.dg_head_background_tags {
  background-color: #<?php echo $dg_head_background; ?>;
  border-left: 1px solid #<?php echo $dg_border; ?>;
  border-right: 1px solid #<?php echo $dg_border; ?>;
  color: #<?php echo $dg_head_color; ?>;
}

.addtag_loading {
  display: inline-block;
  background: url(../images/darktheme/hloading.gif) no-repeat center;
  width: 10px;
  height: 10px;
}

.dg_head_background_tags input {
  border-color:#<?php echo $dg_border; ?> !important;
  padding: 2px 0 3px 2px
}

.dg_head_background span {
  color: #<?php echo $dg_link_color; ?> !important;
}

.dg_head_background a,
.dg_head_background a:visited,
.dg_head_background a span,
.dg_head_background a:visited span {
  color: #<?php echo $dg_link_color; ?> !important;
}

.dg_head_background a:hover,
.dg_head_background a:hover span {
  color: #<?php echo $dg_head_color; ?> !important;
}

.grid_border {
  border-left:1px solid #<?php echo $dg_border; ?> !important;
}

#dg_body_background_basket,
.dg_body_background_comment,
.dg_body_background_details {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
  <?php if (!$ad_showcomment || !$ad_showsend2friend) : ?>
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  <?php endif; ?>
  color: #<?php echo $dg_body_color; ?> !important;
}

.dg_body_background_upload {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?> !important;
}

.dg_body_background_edit_image {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?> !important;
}

.dg_body_background_message {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?> !important;
}

ul#list li.dg_body_error_message,
.dg_tag_error_message {
  background-color: #FFFFCC !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_border; ?> !important;
}

.dg_tag_error_message {
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
}

.dg_header select,
.dg_header input,
.dg_body_background_upload select,
.dg_body_background_upload input,
.dg_body_background_upload textarea,
.dg_head_background_tags input,
.dg_body_background_edit_image select,
.dg_body_background_edit_image input,
.dg_body_background_edit_image textarea,
.dg_head_background select,
.dg_body_background_comment input,
.dg_body_background_recomend input,
.dg_body_background_comment textarea,
.dg_header select:hover,
.dg_header input:hover,
.dg_body_background_edit_image select:hover,
.dg_body_background_edit_image input:hover,
.dg_body_background_edit_image textarea:hover,
.dg_head_background select:hover,
.dg_body_background_comment input:hover,
.dg_body_background_recomend input:hover,
.dg_body_background_comment textarea:hover {
  background-color: #<?php echo $dg_input_background; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_head_background; ?> !important;
}

.dg_header select:focus,
.dg_header input:focus,
.dg_body_background_upload select:focus,
.dg_body_background_upload input:focus,
.dg_body_background_upload textarea:focus,
.dg_head_background_tags input:focus,
.dg_body_background_edit_image select:focus,
.dg_body_background_edit_image input:focus,
.dg_body_background_edit_image textarea:focus,
.dg_head_background input:focus,
.dg_head_background select:focus,
.dg_body_background_recomend input:focus,
.dg_body_background_comment input:focus,
.dg_body_background_comment textarea:focus,
.dg_body_background_description textarea:focus,
ul#list li textarea:focus {
  background-color: #<?php echo $dg_input_hover; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
  color: #000000;
}

.dg_body_background_description,
.dg_body_background,
.dg_body_background_recomend,
.dg_body_background_tags {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?> !important;
}

#dg_body_background_basket,
#dg_body_background_basket_checkout {
  color: #<?php echo $dg_link_color; ?> !important;
}

td .dg_body_background_td {
  background-color: #<?php echo $dg_body_background_td; ?> !important;
}

td .dg_body_background_td:hover {
  background-color: #<?php echo $dg_body_background_td_hover; ?> !important;
  color: #<?php echo $dg_head_background; ?> !important;
}

.dg_body_background_td a span {
  font-size: 11px;
}

.dg_body_background_td a:hover span {
  color: #<?php echo $dg_head_background; ?> !important;
}

#dg_body_background_basket_checkout {
  background-color: #<?php echo $dg_body_background; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?>;
}

.dg_body_background_basket_subtotal {
  background: #<?php echo $dg_body_background; ?> !important;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
}

div.grippie {
  background: #<?php echo $dg_border; ?> url(../images/grippie.png) no-repeat scroll center 2px;
  border: 0pt 1px 1px solid #<?php echo $dg_border; ?> !important;
}

td .details {
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
}

td .bookmarker {
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
}

ul#list li{
  background-color: #<?php echo $dg_body_background; ?> !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?>;
}

ul#list li .date {
  color: #<?php echo $dg_body_color; ?> !important;
}

ul#list li .control {
  color: #<?php echo $dg_border; ?> !important;
}

ul#list,
div#nocom {
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important;
}

.dg-avatar,
.dgimg {
  background: #<?php echo $dg_input_hover; ?> !important url(../images/customtheme/loading.gif) no-repeat 50% 50%;
  border: 1px solid #<?php echo $dg_border; ?> !important;
}

table.dguserpanel {
  background-color: #<?php echo $dg_body_background; ?> !important;
  color: #<?php echo $dg_head_color; ?> !important
}

table.dguserpanel thead th{
  background-color: #<?php echo $dg_head_background; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
}

table.dguserpanel thead a {
  font-size: 11px;
  color: #<?php echo $dg_link_color; ?> !important;
}

table.dguserpanel tbody tr{
  background-color: #<?php echo $dg_body_background; ?> !important;
}

table.dguserpanel tbody tr.row1, table.dguserpanel tbody tr.row1 td{
  background-color: #<?php echo $dg_body_background; ?> !important;
}

table.dguserpanel tbody tr.row0:hover td, table.dguserpanel tbody tr.row1:hover td{
  background-color: #<?php echo $dg_input_hover; ?> !important;
}

table.dguserpanel tbody tr td{
  background-color: #<?php echo $dg_body_background_td_hover; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
  color: #<?php echo $dg_body_color; ?> !important;
}

table.dguserpanel thead a {
  color: #<?php echo $dg_link_color; ?> !important;
}

table.dguserpanel thead a:hover {
  color: #<?php echo $dg_head_color; ?> !important;
}

table.dguserpanel tbody a {}

table.dguserpanel tbody a:hover {}

.dg_basket {
  background: #<?php echo $dg_body_background_td_hover; ?> !important;
  border: 1px solid #<?php echo $dg_border; ?> !important;
}

.dg_basket li#item {
  color: #<?php echo $dg_link_color; ?> !important;
}

.dg_basket li#cost {
  color: #<?php echo $dg_body_color; ?> !important;
}

.plupload_button {
    color: #000 !important;
    border: 1px solid #<?php echo $dg_border; ?> !important;
    background: #ddd url(../images/button.png) repeat-x 0 0;
}

.plupload_button:hover {
	color: #000;
}

.plupload_disabled,
a.plupload_disabled:hover {
    color: #000 !important;
    border-color: #c5c5c5;
    background-color: #ededed;
}

.plupload_container input {
	border: 1px solid #DDD;
}

.plupload_header_content {
    color: #<?php echo $dg_border; ?> !important;
}
.plupload_header_title {
	color: #<?php echo $dg_link_color; ?> !important;
}
.plupload_header_text {
	color: #<?php echo $dg_body_color; ?> !important;
}

.plupload_scroll .plupload_filelist {
	background-color: #F5F5F5;
    border-left: 1px solid #<?php echo $dg_border; ?> !important;
    color: #<?php echo $dg_border; ?> !important;
}

.plupload_filelist li {}

.plupload_filelist_header,
.plupload_filelist_footer {
	background-color: #<?php echo $dg_head_background; ?> !important;
    border: 1px solid #<?php echo $dg_border; ?> !important;
}

.plupload_filelist_footer {
  border: none;
}

.plupload_file_status {
  color: #<?php echo $dg_border; ?> !important;
}

.plupload_file_status span {
  color: #<?php echo $dg_body_color; ?> !important;
}

.plupload_progress_container {
	border: 1px solid #000;
	background: #F5F5F5;
}
.plupload_progress_bar {
	background: #<?php echo $dg_link_color; ?> !important;
}

.dg_body_background_message {
    background: url(../images/customtheme/dg-info-icon.png) no-repeat scroll 2% center #F9F9F9;
}

.dg_btn {
  color: #333;
}

.dg_btn span {
  background: #ddd url(../images/button.png) repeat-x 0 0;
  border-left: 1px solid #<?php echo $dg_border; ?> !important;
  border-right: 1px solid #<?php echo $dg_border; ?> !important
}

.dg_btn span span {
  color: #<?php echo $dg_border; ?> !important;
  text-shadow: 0 1px 0 #FFFFFF;
  border-top: 1px solid #<?php echo $dg_border; ?> !important;
  border-bottom: 1px solid #<?php echo $dg_border; ?> !important
}

dg_btn.pill-l span span,
dg_btn.pill-c span span {
  border-right: 1px solid #<?php echo $dg_border; ?> !important
}

dg_btn.pill-c span {
  border-left-color: #fff
}

dg_btn.pill-r span {
  border-left-color: #fff
}

.dg_btn:hover span,
.dg_btn:hover span span,
.dg_btn:focus span,
.dg_btn:focus span span {
  border-color:#<?php echo $dg_link_color; ?> !important;
  color: #<?php echo $dg_head_background; ?> !important;
  text-shadow: 0 1px 0 #FFFFFF
}

.dg_btn:active span {
  color: #000
}

.dg_btn:disabled span {
  color: #A9A9A9
}

.dg_btn:focus,
.dg_btn:active {
  color: #000
}

ul.vote-stars-small, ul.vote-stars-small li a:hover, ul.vote-stars-small li.current-rating {
    background-image: url(../images/customtheme/star_small.png) !important;
}

.add_favorite {
    background: url(../images/customtheme/add-favorite.png) no-repeat scroll 0 0 transparent;
}

.add_favorite_hover {
    background: url(../images/customtheme/favorite.png) no-repeat scroll 0 0 transparent;
}

.remove_favorite {
    background: url(../images/customtheme/remove-favorite.png) no-repeat scroll 0 0 transparent;
}

.gmap_icon {
    background: url(../images/customtheme/gmap.png) no-repeat scroll 0 0 transparent;
}

.add_to_basket {
    background: url(../images/customtheme/basket_add.png) no-repeat scroll 0 0 transparent;
}

.add_to_basket_hover {
    background: url(../images/customtheme/basket.png) no-repeat scroll 0 0 transparent;
}
.remove_from_basket {
    background: url(../images/customtheme/basket_delete.png) no-repeat scroll center center transparent;
}