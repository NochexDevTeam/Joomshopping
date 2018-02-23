<div class="col100">
<fieldset class="adminform">
<table class="admintable" width = "100%" >
 
 <tr>
   <td  class="key">
     <?php echo _JSHOP_NOCHEX_EMAIL; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[email_received]" size="45" value = "<?php echo $params['email_received']?>" />
     <?php echo JHTML::tooltip(_JSHOP_NOCHEX_EMAIL_DESCRIPTION);?>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _JSHOP_NOCHEX_TESTMODE;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', $params['testmode']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_TESTMODE_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _JSHOP_NOCHEX_XML;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[xmlCollection]', 'class = "inputbox" size = "1"', $params['xmlCollection']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_XML_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _JSHOP_NOCHEX_POSTAGE;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[postage]', 'class = "inputbox" size = "1"', $params['postage']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_POSTAGE_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _JSHOP_NOCHEX_HIDE;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[hidemode]', 'class = "inputbox" size = "1"', $params['hidemode']);     
     ?>
	    Hide Billing Details Option, for hiding the billing address details on your payment page. <br/> <span style="font-weight:bold;">We advise you to place a note on your checkout page to inform customers to check the billing address details match the card details they are going to use.</span>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _JSHOP_NOCHEX_DEBUG;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[debug]', 'class = "inputbox" size = "1"', $params['debug']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_DEBUG_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _JSHOP_NOCHEX_TRANSACTION_END;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_TRANSACTION_END_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _JSHOP_NOCHEX_TRANSACTION_PENDING;?>
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_TRANSACTION_PENDING_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _JSHOP_NOCHEX_TRANSACTION_FAILED;?>
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);
     echo " ".JHTML::tooltip(_JSHOP_NOCHEX_TRANSACTION_FAILED_DESCRIPTION);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _JSHOP_CHECK_DATA_RETURNS;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[checkdatareturns]', 'class = "inputbox" size = "1"', $params['checkdatareturns']);     
     ?>
   </td>
 </tr>
</table>
</fieldset>
</div>
<div class="clr"></div>