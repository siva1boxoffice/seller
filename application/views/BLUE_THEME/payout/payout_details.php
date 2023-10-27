  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <style type="text/css">
        .table {border: 1px solid #ccc;}
        .table .thead-light td {color: #000;background-color: #e9ecef;border-color: #dee2e6;font-weight: bold;}
        .table .thead-light th {color: #000;background-color: #e9ecef;border-color: #dee2e6;font-weight: bold;text-transform: uppercase;}
     </style>
    
   <div class="main page_full_widd">
      <div class="container mt-5">
      <?php if(!empty($payout_histories)){
    foreach ($payout_histories as $payout_history) {
      $payout_month = date("F Y", strtotime($payout_history[0]->payout_date_from));
   ?>
  <h4><?php echo $payout_month;?></h4>
  <table class="table">
     <thead class="thead-light">
       <tr>
         <th scope="col">Payment ID</th>
         <th scope="col">Order ID</th>
         <th scope="col">Order Date</th>
         <th scope="col">Total</th>
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
          $total[]         = format_price($payout_order->ticket_amount);
        ?>
       <tr>
         <td><?php echo $payout->payout_no;?></td>
         <td><a href="javascript:void(0);" data-url="<?php echo base_url(); ?>game/orders/details/<?php echo md5($payout_order->booking_no); ?>" class="order_details"><?php echo $payout_order->booking_no;?></a></td>
         <td><?php echo $payout_order->created_at;?></td>
         <td><?php echo $payout_order->currency_type;?> <?php echo format_price($payout_order->ticket_amount);?></td>
         
       </tr>
     <?php } } ?>
     <?php } ?>
     </tbody>
  </table>
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
    <div class="row">
    <div class="container" style="height: 100px;"></div>
   </div>
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