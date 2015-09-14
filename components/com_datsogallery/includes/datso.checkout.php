<?php
defined('_JEXEC') or die( 'Restricted access' );
require (JPATH_COMPONENT_ADMINISTRATOR.DS.'config.datsogallery.php');
$user = JFactory::getUser();
$juri = JURI::getInstance();
$root = $juri->toString( array('scheme', 'host', 'port') );
if (!$user->id) {
  $app->redirect($url, JText::_('You must login first') );
}
if (!$ad_shop) {
  $app->redirect(JRoute::_("index.php?option=com_datsogallery".$itemid, false), JText::_('COM_DATSOGALLERY_NOT_ACCESS_THIS_DIRECTORY'));
}
$tax = ($ad_pp_tax_type == '0')? $ad_pp_tax_value.'%' : currencySymbol($ad_pp_currency).$ad_pp_tax_value;
$showtax = ($ad_pp_tax_value == '0') ? '':'<span style="color:#000000">'.JText::_('COM_DATSOGALLERY_TAX').': '.$tax.'</span>&nbsp;';
$order_id = dgOrderId();
$ip = getIpAddress();
$url = ($ad_pp_mode) ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
?>

  <div class="dg_head_background">
    <?php echo JText::_('COM_DATSOGALLERY_BASKET_ITEMS');?> <span id="items"><?php countItems(); ?></span>
  </div>

  <div id="dg_body_background_basket" class="dg_slider">
    <?php echo showBasket(); ?>
  </div>

  <div id="dg_body_background_basket_checkout">
    <form action="<?php echo $url; ?>" method="post">
      <input type="hidden" name="business" value="<?php echo $ad_pp_email; ?>" />
      <input type="hidden" name="charset" value="utf-8" />
      <input type="hidden" name="amount" value="<?php echo total(); ?>" />
      <input type="hidden" name="item_number" value="<?php echo $order_id; ?>" />
      <input type="hidden" name="item_name" value="<?php echo $app->getCfg('sitename') . JText::_('COM_DATSOGALLERY_PAYPAL_ORDER') . $order_id; ?>" />
      <input type="hidden" name="cmd" value="_xclick">
      <input type="hidden" name="currency_code" value="<?php echo $ad_pp_currency; ?>" />
      <input type="hidden" name="notify_url" value="<?php echo $root.JRoute::_('index.php?option=com_datsogallery&task=notify'); ?>" />
      <input type="hidden" name="return" value="<?php echo $root.JRoute::_('index.php?option=com_datsogallery&task=complete'); ?>" />
      <input type="hidden" name="cancel_return" value="<?php echo $root.JRoute::_('index.php?option=com_datsogallery&task=cancel'); ?>" />
      <input type="hidden" name="custom" value="<?php echo $user->id; ?>|<?php echo $ip; ?>" /> 
      <input type="hidden" name="rm" value="2" />
      <input type="hidden" name="tax" value="0" />
      <input type="hidden" name="no_note" value="1" />
      <input type="hidden" name="no_shipping" value="1" />
      <div class="dg_body_background_basket_continue_shopping">
      <button class="dg_btn" type="button" onclick="javascript:location.href='<?php echo JRoute::_('index.php?option=com_datsogallery'.$itemid); ?>';">
      <span><span><?php echo JText::_('COM_DATSOGALLERY_CONTINUE_SHOPPING'); ?></span></span></button></div> <div class="dg_body_background_basket_subtotal"><?php echo $showtax; ?><?php echo JText::_('COM_DATSOGALLERY_BASKET_TOTAL'); ?>: <?php echo currencySymbol($ad_pp_currency); ?><span id="total"><?php echo total(); ?></span><button type="submit" class="dg_btn"><span><span><?php echo JText::_('COM_DATSOGALLERY_CHECKOUT'); ?></span></span></button></div>
    </form>
  </div>
  <br />
 <br />
<br />