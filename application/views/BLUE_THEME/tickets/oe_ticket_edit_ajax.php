
<style type="text/css">
  .clone-btn-listing {
    color: #0037D5;
    border: 1px solid #0037D5;
    border-radius: 0px;
    padding: 7px 40px;
    font-weight: 600;
    padding: 7px 40px;
}
</style>
<?php
 $listing_notes = explode(',', $list_ticket->listing_note);
?>
 
                <form id="save_ticket_details_<?php echo $list_ticket->s_no; ?>" action="<?php echo base_url(); ?>tickets/index/ticket_update" class="save_ticket_details form-horizontal ticket_edit_form" method="post" novalidate="novalidate">
                  <input type="hidden" name="ticketid" value="<?php echo $list_ticket->s_no; ?>">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="section_left" id="content_1">

                        <div class="team_name">
                           <h3><?php echo $list_ticket->match_name; ?> - <?php echo $list_ticket->tournament_name; ?></h3>
                           <p><?php echo date('D d M Y', strtotime($list_ticket->match_date));?> <?php echo $list_ticket->match_time; ?></p>
                           <p><span><?php echo $list_ticket->stadium_name . ', ' .$list_ticket->country_name . ', ' . $list_ticket->city_name; ?></span></p>
                        </div>

                        <div class="">
                            <div class="row">
                              <div class="col-md-6"> 
                                <label>Available Tickets <span>*</span></label>
                                <div class="input-group">
                                  <input type="text" name="ticket_quantity" class="ticket_price form-control" placeholder="" aria-label="Available Tickets" aria-describedby="basic-addon2" value="<?php echo $list_ticket->quantity;?>">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <label>Quantity Sold</label>
                                <div class="input-group">
                                  <input type="text" readonly class="form-control" placeholder="0" aria-label="Available Tickets" aria-describedby="basic-addon2" value="<?php echo $list_ticket->sold;?>">
                                </div>
                              </div>

                            </div>
                        </div>


                        <div class="section">
                           <div class="row">
                             <div class="col-md-6">
                               <label>Section <span>*</span></label>
                                  <select name="ticket_category" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-category-<?php echo $list_ticket->ticketid; ?>" data-match="<?php echo $list_ticket->match_id; ?>" class="ticket_category custom-select">
                                  <?php foreach ($tkt_categories as $tktkey => $tkt_category) {
                                     ?>
                                  <option value="<?php echo $tkt_category->category; ?>" <?php if ($tkt_category->category == $list_ticket->ticket_category) { ?> selected="selected" <?php } ?>><?php echo $tkt_category->seat_category; ?></option>
                                  <?php } ?>
                               </select>
                             </div>
                              <div class="col-md-6">
                               <label>Ticket Block<span>*</span></label>
                                   <select name="ticket_block" data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-block-<?php echo $ticket->ticketid; ?>" class="ticket_block custom-select">
                                   <option value="0" <?php if ($list_ticket->ticket_block=='') { ?> selected="selected" <?php } ?>>Any</option>
                                  <?php foreach ($blocks_data as $blkkey => $block_data) {
                                     $block = explode('-',$block_data->block_id);
                                     ?>
                                  <option value="<?php echo $block_data->id; ?>" <?php if ($block_data->id == $list_ticket->ticket_block) { ?> selected="selected" <?php } ?>><?php echo end($block); ?></option>
                                  <?php } ?>
                               </select>
                             </div>
                           </div>
                        </div>



                        <div class="section">
                           <div class="row">

                             <div class="col-md-4">
                               <label>Row</label>
                              <div class="input-group">
                                <input type="text" class="form-control" placeholder="" aria-label="Available Tickets" aria-describedby="basic-addon2" name="ticket_row" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-row-<?php echo $list_ticket->ticketid; ?>" value="<?php echo $list_ticket->row; ?>">
                              </div>
                             </div>
                             <div class="col-md-4">
                               <label>Home or Away ? <span>*</span></label>
                                 <select name="home_down" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-home-down-<?php echo $list_ticket->ticketid; ?>" class="ticket_home_down custom-select">
                                  <option value="0" <?php if ($list_ticket->home_town == 0) { ?> selected="selected" <?php } ?>>Any</option>
                                  <option value="1" <?php if ($list_ticket->home_town == 1) { ?> selected="selected" <?php } ?>>Home</option>
                                  <option value="2" <?php if ($list_ticket->home_town == 2) { ?> selected="selected" <?php } ?>>Away</option>
                               </select>

                             </div>
                              <div class="col-md-4">
                               <label>Split type <span>*</span></label>
                                <select name="ticket_split" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-split-<?php echo $list_ticket->ticketid; ?>" class="ticket_split custom-select">
                                  <?php foreach ($split_types as $split_type) { ?>
                                  <option value="<?php echo $split_type->id; ?>" <?php if ($list_ticket->split == $split_type->id) { ?> selected="selected" <?php } ?>><?php echo $split_type->name; ?></option>
                                  <?php } ?>
                               </select>

                             </div>
                          </div>
                        </div>

                        <div class="web_price">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Price <span>*</span></label>

                              <div class="currency_symbol">
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                   <?php if (strtoupper($list_ticket->price_type) == "GBP") { ?>
             <img src="<?php echo base_url().THEME_NAME;?>/images/pound.svg"></span>
               <?php } ?>
               <?php if (strtoupper($list_ticket->price_type) == "EUR") { ?>
               <img src="<?php echo base_url().THEME_NAME;?>/images/euro.svg"></span>
               <?php } ?>
                <?php if (strtoupper($list_ticket->price_type) == "USD") { ?>
               <img src="<?php echo base_url().THEME_NAME;?>/images/usd.png"></span>
               <?php } ?></span>
                              </div>
                              <input type="text" name="ticket_price" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-price-<?php echo $list_ticket->ticketid; ?>" class="ticket_price form-control" value="<?php echo $list_ticket->price; ?>"  placeholder="900" aria-label="" aria-describedby="basic-addon1">
                            </div>
                          </div>



                            </div>
                            <div class="col-md-4">
                              <label>Currency</label>
                              <select class="custom-select">
                                  <option selected value="<?php echo strtoupper($list_ticket->price_type);?>"><?php echo strtoupper($list_ticket->price_type);?></option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="select_restrictions">
                          <div class="row">
                            
                            <div class="col-md-12">
                              <p>If any of the following conditions apply to your tickets, please select them from the list below. If there is a restriction on the use of your ticket not shown here, please stop listing and contact us</p>
                              </div>
                              <div class="col-md-12">
                                <div class="select_option">
                                  <div class="sel_detail_lft">
                                    <ul class="select_detail_chk">
                                     <!--  <?php 
                                    $ticket_key = 0;
                                    foreach ($restriction_left as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>> <?php echo $ticket_detail->ticket_det_name; ?></li>
                                  <?php $ticket_key++;} ?> -->
                                    </ul>
                                  </div>

                                  <div class="sel_detail_rig">
                                    <ul class="select_detail_chk">
                                   <!--    
                                      <?php 
                                    $ticket_key = 0;
                                    foreach ($restriction_right as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>> <?php echo $ticket_detail->ticket_det_name; ?></li>
                                  <?php $ticket_key++;} ?> -->
                                    </ul>
                                  </div>
                                </div>


                               
                              </div>

                              <div class="col-md-12">

                                <div class="select_option">
                                  <h6>Listing Notes</h6>

                                  <div class="sel_detail_lft">
                                    <ul class="select_detail_chk">
                                     <?php 
                                    $ticket_key = 0;
                                    foreach ($notes_left as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>> <?php echo $ticket_detail->ticket_det_name; ?></li>
                                  <?php $ticket_key++;} ?>

                                    </ul>
                                  </div>

                                  <div class="sel_detail_rig">
                                    <ul class="select_detail_chk">
                                      <?php 
                                    $ticket_key = 0;
                                    foreach ($notes_right as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>> <?php echo $ticket_detail->ticket_det_name; ?></li>
                                  <?php $ticket_key++;} ?>
                                    </ul>
                                  </div>


                                </div>

                                
                              </div>

                              <div class="col-md-12">

                                <div class="select_option select_option_seating">
                                  <h6>Seating</h6>

                                  <div class="sel_detail_lft">
                                    <ul class="select_detail_chk">
                                     <?php 
                                    $ticket_key = 0;
                                    foreach ($split_details_left as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox seat_type" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>> 
                                      <span><?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php $ticket_key++;} ?>

                                    </ul>
                                  </div>

                                  <div class="sel_detail_rig">
                                    <ul class="select_detail_chk">
                                      <?php 
                                    $ticket_key = 0;
                                    foreach ($split_details_right as $ticket_detail) { 
                                    ?>
                                    <li><input class="ticket_label tdcheckbox seat_type" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" <?php if (in_array($ticket_detail->id, $listing_notes)) { ?> checked <?php } ?>>
                                    <span> <?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php $ticket_key++;} ?>
                                    </ul>
                                  </div>


                                </div>
                                 <span><label class="seat_error error"></label></span>   
                              </div>

                              <div class="col-md-12">
                                <div class="clone_btn">
                                  <button id="ticket_clone" data-ticket-id="<?php echo $list_ticket->s_no; ?>" type="button" class="btn clone-btn-listing ticket_clone">Clone</button>

                                  <button id="main_submit"  type="submit" class="btn btn-primary save_ticket_form" form-id="save_ticket_details_<?php echo $list_ticket->s_no; ?>">Save</button>

                                  <button type="button" data-match="<?php echo $list_ticket->match_id; ?>" data-s_no="<?php echo $list_ticket->s_no; ?>" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-delete-<?php echo $list_ticket->ticketid; ?>" class="btn btn-outline-danger ticket_delete">Delete</button>
                                </div>
                              </div>
                          </div>
                        </div>

                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="section_right">
                        <div class="publish_sec">
                          <div class="content"><span class="align">Publish</span>
                              <label class="switch">
                              <input type="checkbox" ticket_id="<?php echo $list_ticket->s_no; ?>" class="" name="ticket_status" <?php if($list_ticket->ticket_status == 1){ ?> checked="checked" <?php } ?> value="1"> 
                              <span class="slider round newslider" style=""></span>
                              </label>
                          </div>
                        </div>

                       <!--  <div class="active_listing">
                          <input class="tdcheckbox" type="checkbox" name=""> <span class="align">Active listing</span>
                        </div> -->

                        <div class="ticket_type">
                          <h6>Ticket Type</h6>
                          <p><?php foreach ($ticket_types as $ticket_type) { ?>
                          <?php if ($list_ticket->ticket_type == $ticket_type->id) { ?> 
                            <?php echo $ticket_type->tickettype; ?> <?php } ?>
                          <?php } ?>
                          </p> 
                          <a href="javascript:void(0);" class="expander">Change</a>
                        </div>

                        <div id="ticket_type_div" class="active_listing" style="display: none;">
                          <div class="row">
                          <div class="col-md-12">
                           <select data-ticket="<?php echo $ticket->ticketid; ?>" id="ticket-type-<?php echo $ticket->ticketid; ?>" name="ticket_type" id="ticket" class="custom-select">
                          <?php foreach ($ticket_types as $ticket_type) { ?>
                          <option value="<?php echo $ticket_type->id; ?>" <?php if ($list_ticket->ticket_type == $ticket_type->id) { ?> selected="selected" <?php } ?>><?php echo $ticket_type->tickettype; ?></option>
                          <?php } ?>
                          </select>
                        </div>
                      </div>
                        </div>
                      
                        <div class="listing_id">
                          <h6>Listing ID</h6>
                          <p><?php echo $list_ticket->ticket_group_id; ?></p>
                        </div>
                        <div class="contact_link">
                          <a href="javascript:void(0);">Contact Us</a> | <a class="report" href="javascript:void(0);" data-toggle="modal" data-target="#myModal_seller_report_issue" data-order="" data-match="<?php echo $list_ticket->match_id; ?>" data-name="<?php echo $list_ticket->match_name; ?> - <?php echo $list_ticket->tournament_name; ?>" data-date="<?php echo date('l, d F Y', strtotime($list_ticket->match_date));?> <?php echo $list_ticket->match_time; ?>" data-venue="<?php echo $list_ticket->stadium_name . ', ' .$list_ticket->country_name . ', ' . $list_ticket->city_name; ?>">Report Event Issue</a>
                        </div>

                        <div class="btn_save">

                          <button id="sub_submit" type="submit" form-id="save_ticket_details_<?php echo $ticket->ticketid; ?>" class="btn btn-primary save_ticket_form">Save</button>
                          <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-cancel">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
              </form>
              
              <script type="text/javascript">
                 $(document).ready(function() {

              $('.report').on('click',function(){
  var match_name  = $(this).attr('data-name');
  var match_date  = $(this).attr('data-date');
  var match_venue = $(this).attr('data-venue');
  var match_id    = $(this).attr('data-match');
  var order_id    = $(this).attr('data-order');
  $("#report_match_name").text(match_name);
  $("#report_match_date").text(match_date);
  $("#report_match_venue").text(match_venue);
  $("#report_match_id").val(match_id);
  $("#report_order_id").val(order_id);
  $('.edit_ticket_close').trigger('click');

})


              $('body').on("click",".ticket_label",function() { 

              var that = $(this);
              var ischecked= $(this).is(':checked');


              if(!ischecked){
              //alert('not');
              $(this).prop('checked', true);
              }else{ 
              $(this).prop('checked', false);

              }

              });

                 /*  $('body').on("click",".select_detail_chk li",function() { 

        var that = $(this);
        var ischecked= $(this).find('[type=checkbox]').is(':checked');

       
         if(!ischecked){
         $(this).find('[type=checkbox]').prop('checked', true);
         }else{ 
          $(this).find('[type=checkbox]').prop('checked', false);
           
         }

    });*/

     $('body').on("click",".select_detail_chk li",function() {

                   if($(this).find(".seat_type").length){


                    $(".select_option_seating").find("li").removeClass("yellowBackground_1");


                    $(".seat_type").each(function() {
                    $(this).removeAttr('checked');
                    });
                    $(this).attr('checked','checked');

                     /* var ischecked= $(this).find('[type=checkbox]').is(':checked');
                 
                      if(!ischecked ){

                          $(".seat_type").attr("disabled", true);
                          $(".seat_type").parents("li").css("pointer-events","none") ;
                      }
                      else{
                          $(".seat_type").attr("disabled", false);
                          $(".seat_type").parents("li").css("pointer-events","auto") ;
                      }

                       $(this).find(".seat_type").parents("li").css("pointer-events","auto") ;
                          $(this).find(".seat_type").attr("disabled", false);*/

                    }


        var that = $(this);
        var ischecked= $(this).find('[type=checkbox]').is(':checked');

       
         if(!ischecked){
         $(this).find('[type=checkbox]').prop('checked', true);
         }else{ 
          $(this).find('[type=checkbox]').prop('checked', false);
           
         }

    });



$(".ticket_price").on("keyup", function(evt) {
            var self = $(this);
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

$('.save_ticket_details').validate({
  submitHandler: function(form) {
  

  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);
  
  var submit = $('#'+$(form).attr('id')).find(':submit');
  console.log($(this));
  var btnid = submit.attr('id');
  var btn_text = $('#'+btnid).text();
  $('#'+btnid).attr("disabled", true);
  $('#'+btnid).html('<i class="fa fa-spinner fa-spin" style="color:#fff;"></i>&nbsp;Please Wait ...');
  
   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");
        $('#'+btnid).html(btn_text);
        $('.close').trigger('click');

      setTimeout(function(){

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          oe_load_tickets_details(data.match_id,0);
        }else if(data.status == 0) {
          $('#'+btnid).attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
        
      }, 500);

        

      }
    })
    return false;
  }
});

                $('.expander').on('click', function () {
                $('#ticket_type_div').slideToggle();
                });

                  $("#content_1").mCustomScrollbar({
                  scrollButtons:{
                  enable:true
                  }
                  });
                  });
              </script>