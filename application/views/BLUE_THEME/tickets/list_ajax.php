  
  <table class="table">
             
               <?php 
               if(!empty($listings)){

               foreach ($listings as $list_ticket) {
            $tickets = $list_ticket->tickets;
            //echo "<pre>";print_r($list_ticket);
             $min_price = min(array_column($tickets, 'price'));
             $max_price = max(array_column($tickets, 'price'));
             $total_qty = array_sum(array_column($tickets,'quantity'));
             $total_sold = array_sum(array_column($tickets,'sold'));
             $teams = explode('vs',$list_ticket->match_name);
             if($teams[1] == ''){
               $teams = explode('Vs',$list_ticket->match_name);
             }
             $final_status = array_sum(array_column($tickets,'status'));
             $future = strtotime($list_ticket->match_date);
                $now = time();

                $d1 = new DateTime(date('Y-m-d h:i:s'));
                $d2 = new DateTime($list_ticket->match_date);
                $interval = $d1->diff($d2);
                $diffInSeconds = $interval->s; //45
                $diffInMinutes = $interval->i; //23
                $diffInHours   = $interval->h; //8
                $diffInDays    = $interval->d; //21
                $diffInMonths  = $interval->m; //4
                $diffInYears   = $interval->y; //1
                //echo $diffInYears.'-'.$diffInMonths.'-'.$diffInDays.'-'.$diffInHours.'-'.$diffInMinutes.'-'.$diffInSeconds;

                if ($diffInYears >= 1) {
                $event_left =  $diffInYears . ' Year Left';
                }
                else if ($diffInYears < 1 && $diffInMonths >= 1) {
                $event_left =  $diffInMonths . ' Months Left';
                }
                else if ($diffInMonths < 1 && $diffInDays >= 1) {
                $event_left =  $diffInDays . ' Days Left';
                }
                else if ($diffInDays < 1 && $diffInHours >= 1) {
                $event_left =  $diffInHours . ' Hours Left';
                }
                else if ($diffInHours < 1 && $diffInMinutes >= 1) {
                $event_left =  $diffInMinutes . ' minutes Left';
                }
                else if ($diffInMinutes >= 1 && $diffInSeconds >= 1) {
                $event_left =  $diffInMinutes . ' minutes Left';
                }
                else if ($diffInSeconds >= 1) {
                $event_left =  $diffInMinutes . ' Seconds Left';
                }
                else{
                $event_left = 'Expired';
                }

                 $time_now = time(); // or your date as well
                $match_date_ = strtotime($list_ticket->match_date);
                $fillment_daysc = $match_date_ - $time_now;
                $fillment_days = round($fillment_daysc / (60 * 60 * 24));
                $full_fillment_day = $fillment_days - FULLFILLMENT_DAY;
                if($full_fillment_day < 0){
                  $event_left = 'Overdue';
                }
                if($total_sold <= 0){
                  $full_fillment_day = 0;
                }
                
               /* echo date('Y-m-d', strtotime($date. ' + 10 days')); 
                $full_fillment_date = $list_ticket->match_date*/

            ?>
              <tr>
                <td></td>
                <!-- <td><input class="tdcheckbox singlecheck" data-ticket-id="<?php echo $list_ticket->s_no; ?>" type="checkbox" name="singlecheck[]" value="<?php echo $list_ticket->s_no; ?>"></td> -->
                <td><a href="<?php echo base_url();?>tickets/index/listing" class=""><img src="<?php echo base_url().THEME_NAME;?>/images/left-arrow.svg"></a></td>
                <td><?php echo $list_ticket->match_name; ?><!--  - <?php echo date('l', strtotime($list_ticket->match_date));?>  <span>[<?php echo $list_ticket->ticket_group_id; ?>]</span> --><br><span><?php echo $list_ticket->country_name . ', ' . $list_ticket->city_name; ?></span><br><?php echo date('D d M Y', strtotime($list_ticket->match_date));?> <?php echo $list_ticket->match_time; ?> </td>
                <td><span>Available Tickets:&nbsp;</span><?php echo (($list_ticket->available_tickets != '') ? $list_ticket->available_tickets : '0');?></td>
                <td><span>Tickets sold:&nbsp;</span><?php echo (($list_ticket->sold_qty != '') ? $list_ticket->sold_qty : '0');?></td>
                <td><span>Pending Fulfillment:&nbsp;</span> <?php echo (($list_ticket->pending_fullfillment != '') ? $list_ticket->pending_fullfillment : '0');?></td>
                <td><?php echo $event_left;?></td>
                <td></td>
              </tr>
            <?php } ?>
            </table>
            <table class="table toptable_new seller_list">
              <tr>
                <th>&nbsp;</th>
                <th>Ticket Details</th>
                <th>Block</th>
               <!--  <th>Home/Away</th> -->
                <th class="widd_150">Row</th>
                <th>Ticket type</th>
                <th>Notes</th>
                <th>Split</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Sold</th>
                <th>Publish</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
                <?php if (!empty($tickets)) { ?>
                <?php
                $CI = &get_instance();
                //echo "<pre>";print_r($tickets);
                $i = 0;
                foreach ($tickets as $ticket) {
                $condition['stadium_id'] = $list_ticket->venue;
                $condition['category'] = $ticket->ticket_category;
                $condition['source_type'] = '1boxoffice';
                $blocks_data = $CI->General_Model->getAllItemTable('stadium_details', $condition)->result();
                $listing_notes = array_slice(explode(',', $ticket->listing_note), 0, 3);
                $comaring_tickets = $CI->General_Model->get_tickets_v1($ticket->s_no,$ticket->match_id,$ticket->ticket_category)->result();
                //echo "<pre>";print_r($blocks_data);
                ?>
              <tr>
                <td data-label="Choose"><input class="tdcheckbox singlecheck" data-ticket-id="<?php echo $ticket->s_no; ?>" type="checkbox" name="singlecheck[]" value="<?php echo $ticket->s_no; ?>"></td>
                <td data-label="Ticket Details">
                  <select data-column="ticket_category" data-sno="<?php echo $ticket->s_no; ?>" name="ticket_category" data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-category-<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" class="ticket_category custom-select autosave">
                     <option value="">-Choose Category-</option>
                              <?php foreach ($list_ticket->tkt_category as $tktkey => $tkt_category) {/*
                        if(($list_ticket->tournament != 41 && $list_ticket->tournament != 19 && $list_ticket->tournament != 8) && ($tktkey == 13 || $tktkey == 14 || $tktkey == 15 || $tktkey == 16)) {
                        //echo $get_mtch[0]->tournament.'='.$std->category;exit;
                        continue;
                        }*/
                                 ?>
                              <option value="<?php echo $tktkey; ?>" <?php if ($ticket->ticket_category == $tktkey) { ?> selected="selected" <?php } ?>><?php echo $tkt_category; ?></option>
                              <?php } ?>
                           </select>

                </td>
                <td data-label="Block">
                  <select data-column="ticket_block" data-sno="<?php echo $ticket->s_no; ?>" name="ticket_block"  data-ticket="<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" id="ticket-block-<?php echo $ticket->ticketid; ?>" class="ticket_block custom-select autosave">
                              <option value="0" <?php if ($ticket->ticket_block=='') { ?> selected="selected" <?php } ?>>Any</option>
                              <?php foreach ($blocks_data as $blkkey => $block_data) {
                                 $block = explode('-',$block_data->block_id);
                                 ?>
                              <option value="<?php echo $block_data->id; ?>" <?php if ($block_data->id == $ticket->ticket_block) { ?> selected="selected" <?php } ?>><?php echo strtoupper(end($block)); ?></option>
                              <?php } ?>
                           </select>
                </td>
               <!--  <td>
                 <select name="home_town" data-ticket="<?php echo $ticket->s_no; ?>" id="ticket-home-down-<?php echo $ticket->s_no; ?>" class="ticket_home_down custom-select">
                              <option value="0" <?php if ($ticket->home_town == 0) { ?> selected="selected" <?php } ?>>Any</option>
                              <option value="1" <?php if ($ticket->home_town == 1) { ?> selected="selected" <?php } ?>>Home</option>
                              <option value="2" <?php if ($ticket->home_town == 2) { ?> selected="selected" <?php } ?>>Away</option>
                              <option value="<?php echo $teams[0];?>" <?php if ($ticket->home_town == $teams[0]) { ?> selected="selected" <?php } ?>><?php echo $teams[0];?></option>
                              <option value="<?php echo $teams[1];?>" <?php if ($ticket->home_town == $teams[1]) { ?> selected="selected" <?php } ?>><?php echo $teams[1];?></option>
                           </select>
                </td> -->
                <td data-label="Row">
                 <div class="input-group input_row_group">
                    <input type="text" data-column="row" data-sno="<?php echo $ticket->s_no; ?>" class="form-control autosave autosave_input input_row" name="row"  data-ticket="<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" id="ticket-row-<?php echo $ticket->ticketid; ?>" aria-label="Default" aria-describedby="inputGroup-sizing-default" value="<?php echo $ticket->row; ?>">
                  </div>
                </td>
                <td ata-label="Ticket type">
                  <select data-column="ticket_type" data-sno="<?php echo $ticket->s_no; ?>" data-ticket="<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" name="ticket_type" id="ticket-type-<?php echo $ticket->ticketid; ?>" class="custom-select autosave">
                     <?php foreach ($ticket_types as $ticket_type) { ?>
                              <option value="<?php echo $ticket_type->id; ?>" <?php if ($ticket->ticket_type == $ticket_type->id) { ?> selected="selected" <?php } ?>><?php echo $ticket_type->tickettype; ?></option>
                              <?php } ?>
                  </select>
                </td>
                <td class="txt_center" data-label="Notes">

                  <div class="tooltip_text"><i class="far fa-file-alt"></i>
  <span class="tooltiptext"><ul>
                    <?php
                    $ticket_key = 0;
                    foreach ($ticket_details as $ticket_detail) { ?>
                    <?php if (in_array($ticket_detail->id, $listing_notes)) { ?>
                    <li><?php echo $ticket_detail->ticket_det_name; ?></li>
                  <?php } } ?>
                  </ul></span>
</div>

                </td>
                <td data-label="Split">
                   <select data-column="split" data-sno="<?php echo $ticket->s_no; ?>" name="split" data-ticket="<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" id="ticket-split-<?php echo $ticket->ticketid; ?>" class="ticket_split custom-select autosave">
                              <?php foreach ($split_types as $split_type) { ?>
                              <option value="<?php echo $split_type->id; ?>" <?php if ($ticket->split == $split_type->id) { ?> selected="selected" <?php } ?>><?php echo $split_type->splittype; ?></option>
                              <?php } ?>
                           </select>
                </td>
                <!--<td>  <?php echo $ticket->price; ?> -->
               <!-- <input type="text" class="ticket_price form-control" name="price"  data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-price-<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" aria-label="Default" aria-describedby="inputGroup-sizing-default" value="<?php echo $ticket->price; ?>">
               <?php if (strtoupper($ticket->price_type) == "GBP") { ?>
             <i class="curr_symbol"> £</i>
               <?php } ?>
               <?php if (strtoupper($ticket->price_type) == "EUR") { ?>
              <i class="curr_symbol"> €</i>
               <?php } ?>
             </td> -->
             <td class="widd_20" data-label="Price">
              <div class="currency_symbol">
                 <div class="input-group input-group-overlay" style="width:100px" >
                    <div class="input-group-prepend " >
                      <span class="input-group-text">

                       

                          <?php if (strtoupper($ticket->price_type) == "GBP") { ?>
             <img src="<?php echo base_url().THEME_NAME;?>/images/pound.svg"></span>
               <?php } ?>
               <?php if (strtoupper($ticket->price_type) == "EUR") { ?>
               <img src="<?php echo base_url().THEME_NAME;?>/images/euro.svg"></span>
               <?php } ?>
                <?php if (strtoupper($ticket->price_type) == "USD") { ?>
               <img src="<?php echo base_url().THEME_NAME;?>/images/usd.png"></span>
               <?php } ?>
                <?php if (strtoupper($ticket->price_type) == "AED") { ?>
               <img src="<?php echo base_url().THEME_NAME;?>/images/aed.png"></span>
               <?php } ?>

                    </div>
                    <!-- <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value> -->
                    <input data-column="price" data-sno="<?php echo $ticket->s_no; ?>" type="text" class="ticket_price form-control autosave autosave_input" name="price"  data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-price-<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" aria-label="Default" aria-describedby="inputGroup-sizing-default" value="<?php echo $ticket->price; ?>">

                  <div class="input-group-append hidden" id="spin-ticket-price-<?php echo $ticket->ticketid; ?>">
                  <span class="input-group-text" style="padding:10px;">
                  <i class="fa fa-spinner fa-spin" style="font-size:14px;color: green;"></i>
                  </span>
                  </div>
                  <div class="input-group-append hidden" id="check-ticket-price-<?php echo $ticket->ticketid; ?>">
                  <span class="input-group-text" style="padding:10px;;">
                  <i class="fa fa-check" style="font-size:14px;color: green;"></i>
                  </span>
                  </div>
                   <div class="input-group-append hidden" id="close-ticket-price-<?php echo $ticket->ticketid; ?>">
                  <span class="input-group-text " style="padding:10px;;">
                  <i class="fa fa-close" style="font-size:14px;color: red;"></i>
                  </span>
                  </div>

                  </div>
              </div>
             </td>
                <td data-label="Available"><?php echo $ticket->quantity; ?></td>
                <td data-label="Sold"><?php echo $ticket->sold; ?></td>
                <td data-label="Publish" class="text-center"><div class="content">
                              <label class="switch">
                              <input type="checkbox" data-ticket="<?php echo $ticket->ticketid; ?>" data-match="<?php echo $ticket->match_id; ?>" data-sno="<?php echo $ticket->s_no; ?>" class="ticket_status" name="status" <?php if($ticket->status == 1){ ?> checked="checked" <?php } ?> value="1" id="ticket-status-<?php echo $ticket->ticketid; ?>">
                              <span class="slider round newslider" style=""></span>
                              </label>


                           </div>
                 </td>
                 <!-- <td><a class="ticket_save" data-match="<?php echo $ticket->match_id; ?>" data-s_no="<?php echo $ticket->s_no; ?>" data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-save-<?php echo $ticket->ticketid; ?>" href="javascript:void(0);" data-hint="Save Ticket"><img src="<?php echo base_url().THEME_NAME;?>/images/tick.svg"></a></td> -->
                 <td data-label="&nbsp;">

                  <div class="compare_btn">
                    <div class="dropdown is-up">
                      <!-- <a class="dropbtn is-solid h-modal-trigger">Compare</a> -->
                      <a class="dropbtn is-solid h-modal-trigger compare_ticket" data-ticket-id="" href="javascript:void(0);"><img src="<?php echo base_url().THEME_NAME;?>/images/compare-icon.svg" style="width: 16px;height: 16px;">
                        <div class="dropdown-content">
                          <p><span class="compare_price">Ticket Price :  <?php if (strtoupper($ticket->price_type) == "GBP") { ?>
                             £
                           <?php } ?>
                           <?php if (strtoupper($ticket->price_type) == "EUR") { ?>
                           €
                           <?php } ?>
                           <?php if (strtoupper($ticket->price_type) == "USD") { ?>
                           $
                           <?php } ?>
                           <?php if (strtoupper($ticket->price_type) == "AED") { ?>
                           AED
                           <?php } ?> <?php echo $ticket->price; ?></span></p>
<!-- 
                           <table>
                             <tbody>
                               <tr>
                                 <th>Category</th>
                                 <th>Block</th>
                                 <th>Qty</th>
                                 <th>Price</th>
                                 <th>Listing Notes</th>
                               </tr>
                               <tr>
                                 <td>Shortside Lower Tier</td>
                                 <td>STH 125</td>
                                 <td>2</td>
                                 <td>£ 900.00</td>
                                 <td><ul>
                                   <li>Tickets Seated in Pairs</li>
                                    <li>VIP Lounge Access</li>
                                    <li>Restricted View</li>
                                 </ul></td>
                               </tr>
                               <tr>
                                 <td>Shortside Lower Tier</td>
                                 <td>STH 125</td>
                                 <td>2</td>
                                 <td>£ 900.00</td>
                                 <td><ul>
                                   <li>Tickets Seated in Pairs</li>
                                    <li>VIP Lounge Access</li>
                                    <li>Restricted View</li>
                                 </ul></td>
                               </tr>
                             </tbody>
                           </table> -->
                          <table>
                            <tbody>
                              
                              <?php if(isset($comaring_tickets[0])){?>
                                    <tr>
                                 <th>Category</th>
                                 <th>Block</th>
                                 <th>Qty</th>
                                 <th>Price</th>
                                 <th>Listing Notes</th>
                               </tr>
                                   <?php $i = 0;
                                    foreach($comaring_tickets as $comaring_ticket){
                                $ticket_block = '';
                                if($comaring_ticket->ticket_block != ""){

                                $mcondition['id'] = $comaring_ticket->ticket_block;

                                $mblocks_data = $CI->General_Model->getAllItemTable('stadium_details', $mcondition)->row();

                                $mblock = explode('-',$mblocks_data->block_id);
                                $ticket_block =  strtoupper(end($mblock)); 
                                }
                                $mlisting_notes = array_slice(explode(',', $comaring_ticket->listing_note), 0, 3);
                                 ?>
                              <tr>
                              <td><?php echo $comaring_ticket->seat_category;?></td>
                              <td><?php echo $ticket_block;?></td>
                              <td><?php echo $comaring_ticket->quantity;?></td>
                                <td><?php if (strtoupper($comaring_ticket->price_type) == "GBP") { ?>
                                    <i class="fas fa-pound-sign"></i>
                                    <?php } ?>
                                    <?php if (strtoupper($comaring_ticket->price_type) == "EUR") { ?>
                                    <i class="fas fa-euro-sign"></i>
                                    <?php } ?>
                                    <?php if (strtoupper($comaring_ticket->price_type) == "USD") { ?>
                                    <i class="fas fa-usd-sign"></i>
                                    <?php } ?>
                                    <?php if (strtoupper($comaring_ticket->price_type) == "AED") { ?>
                                    AED
                                    <?php } ?><?php echo $comaring_ticket->price;?></td>
                                    <td><ul>
                                  <?php
                                  $ticket_key = 0;//print_r($ticket_details);
                                  foreach ($ticket_details as $ticket_detail) { ?>
                                  <?php if (in_array($ticket_detail->id, $mlisting_notes)) { ?>
                                  <li><?php echo $ticket_detail->ticket_det_name; ?></li>
                                  <?php } } ?>
                                 </ul></td>
                              </tr>
                             <?php $i++;} } else{ ?>
                                    <tr>
                                   <th> <span class="list_head">No tickets to Compare.</span></th>
                                    </tr>
                                   <?php } ?>
                            </tbody>
                          </table>
                        </div>
                        </a>
                    </div>
                  </div>





                 </td>
                <td data-label="&nbsp;"><a class="edit_ticket" data-ticket-id="<?php echo $ticket->s_no; ?>" href="javascript:void(0);"><img src="<?php echo base_url().THEME_NAME;?>/images/edit.svg" style="width: 16px;height: 16px;"></a></td>
                <td data-label="&nbsp;"><a class="ticket_clone" data-ticket-id="<?php echo $ticket->s_no; ?>" href="javascript:void(0);"><img src="<?php echo base_url().THEME_NAME;?>/images/copy-icon.svg" style="width: 16px;height: 16px;"></a></td>
              </tr>
              <?php } ?>
              <?php } ?>
            </table>
            <?php } ?>

  <div class="modal fade" id="clone-listing-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="">
        
        <p class="text-right"><button type="button" class="modal-close close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><img src="<?php echo base_url().THEME_NAME;?>/images/close.svg" ></span></span>
        </button></p>

        <div class="modal-body clone-listing" id="ticket_clone_body">

        </div>
         
        </div>
      </div>
    </div>


      <div class="my_modal">
      <div class="modal fade bd-example-modal-lg" id="edit_ticket" tabindex="-1" role="dialog" aria-labelledby="edit_ticket" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close edit_ticket_close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="ticket_edit_body">
              <div class="row">
                <div class="team_name">
                <h3 style="text-align: center;"><i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...</h3>
              </div>
            </div>
             
            </div>
          </div>
        </div>
      </div>
    </div>
            <script type="text/javascript">


    
  $(document).ready(function() {

$(".autosave_input").on("focus", function(evt) { 

   $(this).parents(".input-group").css("border","1px solid #0037D5");

});

    
$(".autosave_input").on("blur", function(evt) { 

$(this).parents(".input-group").css("border","1px solid #ccc");

    var id = $(this).attr("id");
    $("#check-"+id).removeClass("showen");
    $("#check-"+id).addClass("hidden");
    console.log("#check-"+id);
});

      $(".ticket_price").on("keyup", function(evt) {
            var self = $(this);

              /*   var price_id = self.attr("id");
                    $("#spin-"+price_id).append('<div class="input-group-append" >'+
'<span class="input-group-text" style="padding:10px;;">'+
'<i class="fa fa-spinner fa-spin" style="font-size:14px;color: green;"></i>'+
'</span></div>');*/

            if (/*self.val().length == 1 || */parseInt(self.val()) <= 0) {
                 self.val('');
                $(this).focus();
                evt.preventDefault();
            }
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
            {
             evt.preventDefault();
            }
            });

      $('.autosave').on('change',function(){
        var column_name  = $(this).attr("data-column");
        var column_value = $(this).val();
        var sno = $(this).attr("data-sno");
        auto_save(column_name,column_value,sno,$(this).attr("id"));
      });
      $('.autosave_input').on('keyup',function(){
        var column_name  = $(this).attr("data-column");
        var column_value = $(this).val();
        var sno = $(this).attr("data-sno");
        
       // auto_save(column_name,column_value,sno,$(this).attr("id"));
      });
      
    //$('.ticket_clone').on('click',function(){
       $(document).on('click', '.ticket_clone', function(){
      var ticket_id = $(this).attr('data-ticket-id');
      //$('#clone-listing-modal').modal();  

      $('.edit_ticket_close').trigger('click');  
      
      setTimeout(
      function() 
      {
      $('#clone-listing-modal').modal({
      backdrop: 'static',
      keyboard: false
      })

      $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>tickets/index/get_ticket',
            data: {
               'ticket_id': ticket_id,
               'type': 'clone'
            },
            dataType: "json",
            success: function(data) {

                if(data.status == 1){
                  $('#ticket_clone_body').html(data.html);
                }

            }
         });
      }, 500);

    })

    
      $(document).on('click', '.edit_ticket', function(){

      var ticket_id = $(this).attr('data-ticket-id');
      $('#edit_ticket').modal();  
       $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>tickets/index/get_ticket',
            data: {
               'ticket_id': ticket_id,
               'type': 'edit'
            },
            dataType: "json",
            success: function(data) {

                if(data.status == 1){
                  $('#ticket_edit_body').html(data.html);
                }

            }
         });


    })
    
  })
</script>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>