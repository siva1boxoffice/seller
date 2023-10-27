  <?php if(!empty($payout_histories)){
    foreach ($payout_histories as $payout_history) {
      $payout_month = date("F Y", strtotime($payout_history[0]->paid_date_time));
   ?>
  <!-- <h4 class="table_head"><?php echo $payout_month;?></h4> -->
  <table class="table toptable">
     <thead class="thead-light">
       <tr>
         <th scope="col" class="wid_20">Payment ID</th>
         <th scope="col" class="wid_20">Date</th>
         <th scope="col" class="wid_10">Proceeds</th>
         <th scope="col" class="wid_10">Charges</th>
         <th scope="col" class="wid_10">Credit</th>
         <th scope="col" class="wid_10">Total</th>
         <th scope="col" class="wid_30">&nbsp;</th>
       </tr>
     </thead>
     <tbody>
      <tr>
          <td colspan="12" class="month_color"><?php echo $payout_month;?></td>
        </tr>
      <?php if(!empty($payout_history)){ 
        $total_payable = array();
        $total_charge = array();
        $total_credit = array();
        $total = array();

        foreach ($payout_history as $payout) {
          $total_payable[] = ($payout->total_payable);
          $total_charge[]  = 0.00;
          $total_credit[]  = ($payout->total_payable);
          $total[]         = ($payout->total_payable);
        ?>
        
       <tr>
         <td data-label="Payment ID">#<?php echo $payout->payout_no;?></td>
         <td data-label="Date"><?php echo date("d/m/Y",strtotime($payout->paid_date_time));?></td>
          <td data-label="Proceeds"><?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout->total_payable);?>
          </td>
          <td data-label="Charges">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?>0.00
          </td>
          <td data-label="Credit">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout->total_payable);?>
          </td>
          <td data-label="Total">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout->total_payable);?>
          </td>
          <td style="text-align:right;"><a href="<?php echo base_url();?>payout/payment_details/<?php echo $payout->payout_id ;?>">Details</a></td>
       </tr>
     <?php } ?>
       <tr>
         <th scope="col">Total</th>
         <th scope="col">&nbsp;</th>
          <th scope="col">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price(array_sum($total_payable));?>
          </th>
          <th scope="col">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price(array_sum($total_charge));?>
          </th>
          <th scope="col">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price(array_sum($total_credit));?>
          </th>
          <th scope="col">
          <?php if($payout_history[0]->payout_currency == "GBP"){?>
          £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
          €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
          $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price(array_sum($total));?>
          </th>
         <th scope="col">&nbsp;</th>
       </tr>
     <?php } ?>
     </tbody>
  </table>
  <?php }  }else{ echo "No Payment History.";} ?>