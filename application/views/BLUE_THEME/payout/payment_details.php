  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <style type="text/css">
/*        .details_table td, .details_table th{padding: 0.4rem;}*/
/*        .table {border: 1px solid #E8EAEF;}*/.table .thead-light
        /*.table .thead-light td {color: #000;background-color: #f9f9f9;border-color: #e2e2e2;font-weight: bold;}
        .table .thead-light th {color: #1A1919;background-color: #F6F8FA;border-color: #E8EAEF;font-weight: 700;text-transform: uppercase;}
        .table tbody tr th {color: #000;background-color: #f2f2f2;border-color: #e2e2e2;font-weight: bold;text-transform: uppercase;}
        .table tbody tr td {font-size: 13px;background: #f9f9f9;color: #867c7d}
        .table tbody tr td a{color: #72cdf6;}
        .table tbody tr td a:hover{color: #337ab7;text-decoration: unset;}
        .table tbody tr th{font-size: 13px;}
        .wid_20{width: 30%;}
        .wid_20{width: 20%;}
        .wid_10{width: 10%;}*/
/*        .table_head{font-size: 16px;font-weight: bold;color: #463944;}*/
        .ordr_id{color: #72cdf6;}
        .nopad{padding: 0px;}
/*        span.clr_rd {color: deeppink;font-size: 20px;font-weight: bold;;}*/
        /*.page_pymt ul li {display: inline-block;margin: 0px;padding: 0px;}
        .page_pymt ul{padding: 0px;}
        .export_btn {float: right;}
        .page_pymt {float: left;}
        .export_btn a {color: #000;font-weight: 700;background: #dfdfdf;border-color: #dfdfdf;padding: 10px;
         border-width: 1px;cursor: pointer;}
         .page_pymt ul li:nth-of-type(1) {color: #ccc;text-decoration: underline;}*/
         .date_clr{color: #8f9089;}
.fa, .fas{font-size: 11px;}
.payment_option {margin: 50px auto 50px;display: table;max-width: 800px;width: 100%;border: 1px solid #E8EAEF;}
.payment_paid { float: left;width: 40%;text-align: center;height: 100%;vertical-align: middle;padding: 42px 0;color: #1A1919;font-size: 30px;font-weight: 700;background: #F6F8FA;}
i.fas.fa-check-circle {font-size: 40px;}
.payment_amt_price {margin-top: 25px;margin-left: 20px;}
.payment_amt_price p {margin-bottom: 0px;text-align: center;color: #1A1919;font-weight: 700;font-size: 14px;}
.payment_amt_price p span {font-size: 30px;font-weight: 400;color: #1A1919;text-align: center;}
.payment_data table:nth-of-type(1) {background: #fff;}
.payment_data {padding: 9px 20px;float: left;width: 50%;}
.payment_data table{width: 100%;color: #1A1919;}

.table_payment .table {border: 1px solid #E8EAEF;}
.table_payment .table .thead-light th{background-color: #F6F8FA;color: #1A1919;font-size: 14px;font-weight: 700;border-color: #E8EAEF;}
.table_payment .table thead th {border-bottom: 0px solid #E8EAEF;}
.table_payment .table td, .table_payment .table th {border-top: 1px solid #E8EAEF;}
.table_payment .table tbody tr td {font-size: 13px;background: #FFFFFF;color: #1A1919;font-weight: 400;}
.payment_data td b {
    font-size: 14px;
    font-weight: 700;
    color: #1A1919;
}
.payment_data td {
    font-size: 14px;
    font-weight: 400;
    color: #1A1919;
}
.payment_paid .fa-check {
    width: 54px;
    height: 54px;
    background: #4CB076;
    border-radius: 50px;
    justify-content: center;
    font-size: 40px;
    color: #fff;
    margin: 0 auto;
    padding: 7px;
}

</style>
    
   <div class="main page_full_widd">
        <div class="container mt-5">
           <?php if(!empty($payout_histories)){
        foreach ($payout_histories as $payout_history) {
          //echo "<pre>";print_r($payout_history);
        ?>
            <!-- <div class="page_pymt">
                <ul>
                    <li>Payments</li>
                    <li>/</li>
                    <li><b>Payment #<?php echo $payout_history[0]->payout_no;?></b></li>
                </ul>
            </div>
            <div class="export_btn">
                <a href="<?php echo base_url(); ?>payout/download_payout/<?php echo $payout_history[0]->payout_no; ?>">Export to Excel</a>
            </div> -->
            <div class="col-md-12 nopad">
              <div class="payment_option">
                <div class="payment_paid">
                  <i class="fas fa-check"></i>
                  <p class="mb-0">Paid</p>
                </div>
                <div class="payment_data">
                  <table>
                     <tbody>
                       <tr>
                         <td><b>Payment ID:</b></td>
                         <td>#<?php echo $payout_history[0]->payout_no;?></td>
                       </tr>
                       <tr>
                         <td><b>Payment Date:</b></td>
                         <td><?php echo date('D d M Y,H:i',strtotime($payout_history[0]->paid_date_time));?></td>
                       </tr>
                       <tr>
                         <td><b>Payment Method:</b></td>
                         <td>International IBAN</td>
                       </tr>
                     </tbody>
                  </table>

                  <div class="payment_amt_price">
                    <p>Payment Amount</p>
                    <p><span><?php if($payout_history[0]->payout_currency == "GBP"){?>
                        £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
                        €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
                        $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout_history[0]->total_payable);?></span></p>
                  </div>
                </div>
              </div>

              


             <!--    <table class="table">
                 <thead class="thead-light">
                   <tr>
                     <td scope="col" class=""><b>Payment Date:</b> <span class="date_clr"><?php echo date('d/m/Y',strtotime($payout_history[0]->paid_date_time));?></span> </td>
                     <td>&nbsp;</td>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <td><span class="clr_rd">Paid</span></td>
                     <td>&nbsp;</td>
                   </tr>
                   <tr>
                     <td style="font-size: 15px;font-weight: bold;">
                       Payment Method <br>
                       <span style="font-size: 13px;font-weight: bold;">International Iban</span>

                     </td>
                     <td style="font-size: 15px;font-weight: bold;text-align: right;">
                       Payment Amount<br>
                       <span style="font-size: 13px;font-weight: bold;color: #8f9089;text-shadow:none;">
                         <?php if($payout_history[0]->payout_currency == "GBP"){?>
                        £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
                        €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
                        $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout_history[0]->total_payable);?></span>
                     </td>
                   </tr>
                 </tbody>
                </table> -->
            </div>
          <?php } } ?>
        <?php if(!empty($payout_histories)){

          $this->CI =& get_instance();
          $this->CI->load->model('Accounts_Model');
        foreach ($payout_histories as $payout_history) {
        $payout_month = date("F Y", strtotime($payout_history[0]->payout_date_from));
        ?>
        <div class="table_payment">
          <table class="table details_table toptable">
             <thead class="thead-light">
               <tr>
                 <th scope="col" class="wid_20">Event Name</th>
                 <th scope="col" class="wid_20">Venue</th>
                 <th scope="col" class="wid_10">Event Date</th>
                 <th scope="col" class="wid_10">Order ID</th>
                 <th scope="col" class="wid_10">Description</th>
                 <th scope="col" class="wid_30">&nbsp;</th>
               </tr>
             </thead>
             <tbody>
               <?php if(!empty($payout_history)){ 
        $total_payable = array();
        $total_charge = array();
        $total_credit = array();
        $total = array();

        foreach ($payout_history as $payout) {
          $payout_orders = json_decode($payout->payout_orders);
           foreach ($payout_orders as $payout_order) {
          
             $booking_data    = $this->CI->Accounts_Model->booking_data($payout_order->bg_id);
        //echo "<pre>";print_r($booking_data);
          $total[]         = format_price($payout_order->ticket_amount);
        ?>

               <tr>
                 <td data-label="Event Name"><?php echo $booking_data->match_name;?></td>
                 <td data-label="Venue"><?php echo $booking_data->stadium_name;?></td>
                 <td data-label="Event Date"><?php echo date('d/m/Y',strtotime($booking_data->match_date));?></td>
                 <td data-label="Order ID" class="ordr_id"><a href="javascript:void(0);" data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($booking_data->booking_no); ?>" class="order_details">#<?php echo $booking_data->booking_no;?></a></td>
                 <td data-label="Description">Proceeds</td>
                 <td data-label="" style="text-align:right;">
                    <?php if($booking_data->currency_type == "GBP"){?>
                        £<?php } ?><?php if($booking_data->currency_type == "EUR"){?>
                        €<?php } ?><?php if($booking_data->currency_type == "USD"){?>
                        $<?php } ?><?php if($booking_data->currency_type == "AED"){?>
          د.إ <?php } ?><?php echo format_price($booking_data->ticket_amount);?>
                  </td>
               </tr>
          <?php } } ?>
           <tr>
                 <td scope="col"><b>Total</b></td>
                 <td scope="col">&nbsp;</td>
                 <td scope="col">&nbsp;</td>
                 <td scope="col">&nbsp;</td>
                 <td scope="col">&nbsp;</td>
                 <td scope="col" style="text-align:right;"><b><?php if($payout_history[0]->payout_currency == "GBP"){?>
                        £<?php } ?><?php if($payout_history[0]->payout_currency == "EUR"){?>
                        €<?php } ?><?php if($payout_history[0]->payout_currency == "USD"){?>
                        $<?php } ?><?php if($payout_history[0]->payout_currency == "AED"){?>
          د.إ <?php } ?><?php echo format_price($payout_history[0]->total_payable);?></b></td>
            </tr>
                  <?php } ?>
             </tbody>
          </table>
        </div>
          <?php }  }else{ echo "No Payout History.";} ?>

        </div>
   </div>
 <div class="my_modal">
        <div class="modal fade bd-example-modal-lg" id="myLargeModalLabel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="order_details">
                  <div class="row">
                      <div class="team_name">
                      <h3 style="text-align: center;"><i class="fa fa-spinner fa-spin" style="color: #325edd;"></i>&nbsp;Please Wait ...</h3>
                    </div>
                  </div>
               <!--  -->
            </div>
          </div>
        </div>
      </div>
    <!-- <div class="row">
    <div class="" style="height: 100px;"></div>
   </div> -->
      <input type="hidden" value="1" name="page" id="page_no">
      <input type="hidden" value="1" name="filter" id="filter" value="all">
   <?php $this->load->view(THEME_NAME.'/common/footer');?>

    <script type="text/javascript">
      
      $(document).on("click",".order_details",function() {
   $('#myLargeModalLabel').modal();
    var action = $(this).attr('data-url');
    

     $.ajax({
      type: "POST",
      dataType: "json",
       url: action,
      data: {"filter":''},
      success: function(list) {
        $('#order_details').html(list.orders);
    }
    });

});
      
    </script>