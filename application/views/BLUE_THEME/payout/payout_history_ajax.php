  <?php if(!empty($payout_histories)){
    foreach ($payout_histories as $payout_history) {
      $payout_month = date("F Y", strtotime($payout_history[0]->payout_date_from));
   ?>
  <h4><?php echo $payout_month;?></h4>
  <table class="table">
     <thead class="thead-light">
       <tr>
         <th scope="col">Payment ID</th>
         <th scope="col">Date</th>
         <th scope="col">Proceeds</th>
         <th scope="col">Charges</th>
         <th scope="col">Credit</th>
         <th scope="col">Total</th>
         <th scope="col">&nbsp;</th>
       </tr>
     </thead>
     <tbody>
      <?php if(!empty($payout_history)){ 
        $total_payable = array();
        $total_charge = array();
        $total_credit = array();
        $total = array();

        foreach ($payout_history as $payout) {
          $total_payable[] = format_price($payout->total_payable);
          $total_charge[]  = 0.00;
          $total_credit[]  = format_price($payout->total_payable);
          $total[]         = format_price($payout->total_payable);
        ?>
       <tr>
         <td>#<?php echo $payout->payout_no;?></td>
         <td><?php echo $payout->paid_date_time;?></td>
         <td><?php echo $payout->currency;?> <?php echo format_price($payout->total_payable);?></td>
         <td><?php echo $payout->currency;?> 0.00</td>
         <td><?php echo $payout->currency;?> <?php echo format_price($payout->total_payable);?></td>
         <td><?php echo $payout->currency;?> <?php echo format_price($payout->total_payable);?></td>
         <td><a href="<?php echo base_url();?>payout/payout_details/<?php echo $payout->payout_no;?>">Details</a></td>
       </tr>
     <?php } ?>
       <tr class="thead-light">
         <td>Total</td>
         <td>&nbsp;</td>
         <td><?php echo $payout_history[0]->currency;?> <?php echo array_sum($total_payable);?></td>
         <td><?php echo $payout_history[0]->currency;?> <?php echo array_sum($total_charge);?></td>
         <td><?php echo $payout_history[0]->currency;?> <?php echo array_sum($total_credit);?></td>
         <td><?php echo $payout_history[0]->currency;?> <?php echo array_sum($total);?></td>
         <td>&nbsp;</td>
       </tr>
     <?php } ?>
     </tbody>
  </table>
  <?php }  }else{ echo "No Payout History.";} ?>