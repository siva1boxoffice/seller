  <?php $this->load->view(THEME_NAME.'/common/header');?>
    <div class="seller">
      <div class="container seller_container">
         <form id="create_ticket" novalidate action="<?php echo base_url(); ?>tickets/create_ticket" class="validate_form_v3" method="post">
        <div class="row">
          <div class="col-lg-12" >
            <?php if(empty($bulk_ticket_matches)){?>
            <div class="search_btn" style="margin:auto;max-width:600px">
              <h3>Search for the events you want to sell tickets for 
              </h3>
               
              
                    <select required name="add_eventname_addlist[]" id="add_event_name" class="js-example-basic-single form-control" data-error="#errNm0">
                      <option value="">-Choose Match Event-</option>
                        
                     </select>
                   <input type="hidden" id="event_hidden" name="event" value="E">
                
                    <span id="errNm0"></span>

                    <div class="request_events_btn">
                      <a class="btn" data-toggle="modal" data-target="#myModal_event">Request Event</a>
                    </div>
              
          </div>
        <?php }else{ ?>
          <div class="search_btn" style="margin:auto;max-width:600px">
              <h3>Bulk Ticket Listing</h3>
          </div>
        <?php } ?>
          </div>
        </div>
        <div class="row column_mobile">
          <div class="col-lg-9">
            <div class="create_ticket_page">
            <div class="seller_page sell_ticket_new">
              <div class="choose_ticket list_ticket_type">
                <h2>Choose ticket type </h2>
                <div class="row">
                      <?php
                      $ticket_key = 0;
                      foreach ($ticket_types as $ticket_type) { ?>
                    <div class="col-md-3 <?php if(($ticket_key) == 0){ ?>pr-1<?php } else if(($ticket_key+1) == count($ticket_types)){ ?>pl-1<?php } else {?>px-1<?php } ?> full_widd_50">
                      <div class="radio">
                        <a class="project-grid-item">
                          <div class="radio-toolbar">
                            <label for="radio_ticket_<?php echo $ticket_key; ?><?php echo $ticket_key; ?>" <?php if($ticket_type->id == 2){?>class="yellowBackground" <?php } ?>><input required type="radio" class="tdcheckbox" id="radio_ticket_<?php echo $ticket_key; ?><?php echo $ticket_key; ?>" <?php if($ticket_type->id == 2){?>checked <?php } ?> name="ticket_types[]" value="<?php echo $ticket_type->id; ?>" data-error="#errNm1" style="display: none;">
                                <h3 class="fs-17"><?php echo $ticket_type->tickettype; ?></h3>
                                <p> <?php echo $ticket_type->t_description; ?></p>
                                <?php if($ticket_type->id == 1 || $ticket_type->id == 3){
                                 ?>
                                <select name="ticket_type_category[<?php echo $ticket_type->id; ?>]" class="custom-select rt-select delivery_options">
                                  <option value="">-Select Delivery type-</option>
                                  <?php foreach($ticket_deliveries as $categories){ ?>
                                  <option value="<?php echo $categories->ticket_cat_id;?>"><?php echo $categories->category;?></option>
                                    <?php } ?>
                                </select>
                                <?php } ?>
                             </label>
                          </div>
                        </a>
                        <?php if(($ticket_key+1) == count($ticket_types)){ ?>
                        <!-- <div class="tooltip">
                          <a href="#" data-toggle="tooltip" title="Tooltip"><img src="<?php echo base_url().THEME_NAME;?>/images/tooltip.svg"></a>
                          </div> -->
                          <?php }?>
                      </div>
                    </div>
                    <?php $ticket_key++;} ?>
                   
                  </div>
                  <span id="errNm1"></span>
              </div>

              <div class="number_ticket list_ticket_type">
                  <h2>Number of Tickets</h2>
                  <!-- <div class="tooltip">
                          <a href="#" data-toggle="tooltip" title="Tooltip"><img src="<?php echo base_url().THEME_NAME;?>/images/tooltip.svg"></a>
                          </div> -->
                  <div class="number_ticket_list">
                    <div class="row">
                      
                       <?php for ($i = 1; $i <= $ticket_max- 1; $i++) { ?>

                      <div class="col-md-2">
                        <a href="javascript:void(0);" onclick="getticketQty(<?php echo $i; ?>);">
                          <div class="radio-toolbar1">
                             <input required="" type="radio" id="getticketQty_<?php echo $i; ?>" name="add_qty_addlist[]" value="<?php echo $i; ?>" data-error="#errNm2" class="getticketQty" <?php if($i == 1){?> checked <?php } ?>>
                              <label for="radioOne" <?php if($i == 1){?>class="yellowBackground" <?php } ?>><?php echo $i; ?></label>
                          </div>
                      </a>
                      </div>
                      <?php }?>

                     
                      
                         <div class="col-md-2">
                                 <a href="javascript:void(0);" onclick="getticketQty_v1(10);">
                                    <div class="radio-toolbar1">
                                       <!-- <input required="" type="radio" id="getticketQty_1" name="add_qty_addlist[]" value="1" data-error="#errNm2"> -->
                                       <!-- <label for="radioOne" style="cursor: pointer;">10+</label> -->
                                       <input id="showmenu" name="add_qty_addlist[]" required="" type="radio" value="10" data-error="#errNm2" class="">
                                       <label for="showmenu">10+</label>

                                        <div class="form-group menu" style="display: none;">
                                             <select class="form-control custom-select" id="slct" onchange="getticketQty(this.value);">
                                                 <option>-Quantity-</option>
                                                 <?php for ($i = 10; $i <= 100; $i++) { ?>
                                                 <option value="<?php echo $i;?>" <?php if($i == 10){?> selected <?php } ?>><?php echo $i;?></option>
                                              <?php } ?>
                                             </select>
                                       </div>
                                    </div>
                                    
                                 </a>
                                 
                              </div>
                      
                    </div>
                  </div>
                   <span id="errNm2"></span>
              </div>

              <div class="split_type list_ticket_type">
                <h2>Split Type</h2>
                <div class="row">

                  <?php
                                 $split_key = 0;
                                 foreach ($split_types as $split_type) { ?>
                  <div class="col-md-3 full_widd_50">
                      <div class="">
                        <a class="project-grid-item">
                            <div class="radio-toolbar">
                               <label for="radio_split_<?php echo $split_key; ?>" <?php if($split_type->id == '5'){?>class="yellowBackground" <?php } ?>>
                                          <input required type="radio" id="radio_split_<?php echo $split_key; ?>" name="split_type[]" value="<?php echo $split_type->id; ?>" data-error="#errNm3" class="tdcheckbox" <?php if($split_type->id == '5'){?> checked <?php } ?> style="display: none;">
                                          <h3 class="fs-16"><?php echo $split_type->splittype; ?></h3>
                                         <!--  <p><?php echo $split_type->s_description; ?></p> -->
                                       </label>
                            </div>
                         </a>
                      </div>
                  </div>
                  <?php $split_key++;} ?>
                </div>
                <span id="errNm3"></span>
              </div>

              <div class="price">
                <h2>Price</h2>
                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-12 full_widd_50">
                    <div class="price_type">
                      <label>My Price</label>
                      <div class="input-group">
                               <input class="form-control one" placeholder="Set price" type="text" name="add_price_addlist[]" id="add_price_addlist" placeholder="0.00" required aria-label="Available Tickets" aria-describedby="basic-addon2" data-error="#errNm4">
                      </div>
                    </div>
                    <span id="errNm4"></span>
                  </div>

                  <div class="col-md-4">
                    <div class="web_price_bk">
                      <label>Web Price</label>
                      <div class="input-group">
                              <input type="text" id="web_price" readonly name="add_web_price_addlist[]" class="form-control" placeholder="Set price" aria-label="Available Tickets" aria-describedby="basic-addon2">
                      </div>
                    </div>
                  </div>
                   <?php //if(empty($bulk_ticket_matches)){?>
                  <div class="col-md-4 col-sm-6 col-xs-12 full_widd_50">
                    <div class="price_type">
                      <div class="select_details">
                         <label>Currency</label>
                          <select class="custom-select default_currency" id="add_pricetype_addlist" name="add_pricetype_addlist[]" data-error="#errNm41">
                                    </select>
                         <!--  <select class="custom-select">
                              <option selected="">€ Eur</option>
                              <option value="1">€ Eur</option>
                              <option value="2">€ Eur</option>
                              <option value="3">€ Eur</option>
                          </select>    -->     
                      </div>
                    </div>
                    <span id="errNm41"></span>
                  </div>
                <?php //} ?>
                </div>
                
              </div>


              <div class="seat_detail">
                <h2>Seat details</h2>
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12 full_widd_50">
                    <div class="seat_detail_list">
                      <label>Category</label>
                      <select required class="custom-select" id="ticket_category" name="ticket_category[]"  data-error="#errNm5">
                      <option value="">Please select</option>
                      </select>

                    </div>
                    <span id="errNm5"></span>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12 full_widd_50">
                    <div class="seat_detail_list">
                      <label>Block</label>
                      <select class="custom-select" id="ticket_block" name="ticket_block">
                                       <option value="">Please select</option>
                                    </select>
                      <!-- <div class="input-group">
                              <input type="text" class="form-control" placeholder="Type here" aria-label="Available Tickets" aria-describedby="basic-addon2">
                      </div> -->
                    </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12 full_widd_50">
                    <div class="seat_detail_list">
                      <label>Row</label>
                      <div class="input-group">
                              <input type="text" name="row" class="form-control one" placeholder="Type here..." aria-label="Available Tickets" aria-describedby="basic-addon2">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12 full_widd_50">
                    <div class="seat_detail_list">
                      <div class="select_details">
                         <label>Ticket Type</label>
                         <select class="custom-select" id="home_town" name="home_town" data-error="#errNm6">
                                       <!-- <option value="0">Any</option> -->
                                       <option value="1" selected="selected">Home</option>
                                       <option value="2">Away</option>
                                    </select>

                                  
                      </div>
                    </div>
                    <span id="errNm6"></span>
                  </div>
                </div>
              </div>

             <div class="ticket_details ticket_details_list_all">
                <h2>Select Ticket Details</h2>
                <div class="restrictions">
                  <div class="row">
                    <div class="col-md-12">
                        <label>Select Restrictions on Use <span>*</span></label>
                        <p>
                          <a href="#" data-toggle="tooltip" title="Tooltip"><img src="<?php echo base_url().THEME_NAME;?>/images/tooltip.svg" class="mCS_img_loaded"></a>
                        If any of the following conditions apply to your tickets, please select them from the list below. If there is a restriction on the use of your ticket not shown here, please stop listing and contact us</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                              <!-- <div class="select_option select_detail_chk">
                                <ul>
                                  <?php 
                                  $ticket_key = 0;
                                  foreach ($restriction_left as $ticket_detail) { 
                                  ?>
                                   <li><img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"><?php echo $ticket_detail->ticket_det_name; ?>
                                   <input required class="tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" data-error="#errNm6">
                                  </li>
                                  <?php
                                  } ?>

                                   <?php 
                                  $ticket_key = 0;
                                  foreach ($restriction_right as $ticket_detail) { 
                                  ?>
                               <li><img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"><?php echo $ticket_detail->ticket_det_name; ?>
                                   <input required class="tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" data-error="#errNm6">
                                  </li>
                                  <?php
                                  } ?>

                                </ul>
                              </div> -->

                              <div class="select_option ticket_chk_box">
                                <div class="sel_detail_lft">
                                  <ul class="select_detail_chk">
                                   <?php 
                                  $ticket_key = 0;
                                  foreach ($restriction_left as $ticket_detail) { 
                                  ?>
                                   <li class="">
                                    <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>"><span><?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
                                  </ul>
                                </div>

                                <div class="sel_detail_rig">
                                  <ul class="select_detail_chk">
                                   <?php 
                                  $ticket_key = 0;
                                  foreach ($restriction_right as $ticket_detail) { 
                                  ?>
                               <li class="ticket_label">
                                <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" ><span><?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
                                  </ul>
                                </div>
                              </div>
                               
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="select_option_notes">
                        <h6>Listing Notes</h6>
                      </div>
                    </div>
                    <div class="col-md-12">
                              <!-- <div class="select_option select_detail_chk">

                                <ul>
                                  <?php 
                                  $ticket_key = 0;
                                  foreach ($notes_left as $ticket_detail) { 
                                  ?>
                                 <li><img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"><?php echo $ticket_detail->ticket_det_name; ?>
                                   <input required class="tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" data-error="#errNm8">
                                  </li>
                                  <?php
                                  } ?>

                                  <?php 
                                  $ticket_key = 0;
                                  foreach ($notes_right as $ticket_detail) { 
                                  ?>
                                 <li><img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"><?php echo $ticket_detail->ticket_det_name; ?>
                                   <input required class="tdcheckbox" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" data-error="#errNm8">
                                  </li>
                                  <?php
                                  } ?>
                                </ul>
                                 <span id="errNm8"></span>
                              </div> -->


                              <div class="select_option">
                                <div class="sel_detail_lft">
                                  <ul class="select_detail_chk">
                                     <?php 
                                  $ticket_key = 0;
                                  foreach ($notes_left as $ticket_detail) { 
                                  ?>
                                 <li class="ticket_label">
                                  <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>"><span>
                                  <?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
  
                                  </ul>
                                </div>
                                <div class="sel_detail_rig">
                                  <ul class="select_detail_chk">
                                     <?php 
                                  $ticket_key = 0;
                                  foreach ($notes_right as $ticket_detail) { 
                                  ?>
                                 <li class="ticket_label">
                                  <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" ><span>
                                  <?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
                                  </ul>
                                </div>
                              </div>



                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="select_option_notes">
                        <h6>Seating <span class="one_opt"><label class="seat_error error"></label></span></h6>
                      </div>
                    </div>
                    <div class="col-md-12">
                          


                              <div class="select_option select_option_seating">
                                <div class="sel_detail_lft">
                                  <ul class="select_detail_chk">
                                     <?php 
                                  $ticket_key = 0;
                                  foreach ($split_details_left as $ticket_detail) { 
                                  ?>
                                 <li class="ticket_label">
                                  <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label seat_type seating" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>"><span>
                                  <?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
  
                                  </ul>
                                </div>
                                <div class="sel_detail_rig">
                                  <ul class="select_detail_chk">
                                     <?php 
                                  $ticket_key = 0;
                                  foreach ($split_details_right as $ticket_detail) { 
                                  ?>
                                 <li class="ticket_label">
                                  <!-- <img src="<?php echo ADMIN_UPLOAD.'/uploads/ticket_details/'.$ticket_detail->timage;?>" width="24px" height="24px"> -->
                                   <input class="tdcheckbox ticket_label seat_type" type="checkbox" name="ticket_details[]" value="<?php echo $ticket_detail->id; ?>" ><span>
                                  <?php echo $ticket_detail->ticket_det_name; ?></span>
                                  </li>
                                  <?php
                                  } ?>
                                  </ul>
                                </div>
                              </div>

                        
                              
                    </div>

                  </div>


                </div>
                
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="upcoming-match-btn-view-all">
                    <button type="submit" style="cursor: pointer;" class="onebox-btn">List Now</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <?php if(empty($bulk_ticket_matches)){?>
          <div class="col-lg-3">
              <div class="select_resti_event header" id="myHeader">
                  <div class="selec_head">
                  <h4>Event Information</h4>
                  </div>
                      <div class="selec_img" id="selec_img" style="display:none;">
                  <img class="img1" id="team1_image" src="">
                  <img class="img2" id="team2_image" src="">
                  </div>

                  <div class="details">
                  <h5 id="res-match-name">Please select an event to start listing tickets</h5>
                  <p><span id="res-match-place"></span></p>

                  <div class="event_img ">

                    <div class="main_html">
                       <span class="ddd-none close close_map_html">&times;</span>
                      <h5 class="ddd-none map_stadium_name db-none text-center"></h5>
                      <div id="map_html" class="map_html"></div>
                   
                      <div class="ddd-none" id="ticket_category_block"></div>
                    </div>

                           <!--  <img id="res-stadium-image" src=""> -->
                  </div>
                    <p class="text-center zoom_map" >
                      <a href="javascript:void(0)" class="zoom_map_btn"><i class="fas fa-search-plus"></i></i> Zoom</a></p>

                  <p><span class="tr_date"><b id="res-match-date"></b></span><span class="tr_time"><b id="res-match-time"></b></span></p>
                  </div>
              </div>
          </div>
        <?php }else{ ?>
          <div class="col-lg-3">
              <div class="select_bulk_event header" id="myHeader">
                  <div class="bulk_scroller">
                    <div class="selec_head">
                    <h4>Bulk Event information</h4>
                    </div>
                    <div class="event_info" id="content_1">
                      <input type="hidden" id="event_hidden" name="event" value="E">
                      <ul>
                        <?php foreach($bulk_ticket_matches as $key => $bulk_ticket_match){
                          //echo "<pre>";print_r($bulk_ticket_match);
                          ?>
                        <li>
                          <input type="hidden" name="add_eventname_addlist[]" value="<?php echo $bulk_ticket_match->m_id;?>">
                          <div class="number">
                            <?php echo ($key+1);?>.
                          </div>
                          <div class="event_info_lis">
                            <h6><?php echo $bulk_ticket_match->match_name;?></h6>
                            <p><?php echo date('l', strtotime($bulk_ticket_match->match_date));?> <?php echo date('d F Y', strtotime($bulk_ticket_match->match_date));?> - <?php echo date('H:i', strtotime($bulk_ticket_match->match_date));?></p>
                            <p><span><?php echo $bulk_ticket_match->tournament_name;?></span></p>
                          </div>
                        </li>
                      <?php } ?>
                      </ul>
                    </div>
                  </div>
              </div>
          </div>
        <?php } ?>
        </div>
      </form>
      </div>
    </div>

 <div class="my_modal modal_widd">
      <div class="modal fade" id="myModal_event" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <!-- <h4 class="modal-title">Modal Header</h4> -->
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="upload_docs">
                    <h3>Request an Event</h3>
                  </div>
                  <form id="request_event" novalidate action="<?php echo base_url(); ?>game/request_event"  method="post">
                  <div class="section_event">
                        <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                <label>Event Name</label>
                                <div class="input-group">
                                  <input type="text" id="event_name" name="event_name" class="form-control valid" placeholder="Please enter event name" required data-error="#errNms1">
                                </div>
                                <span id="errNms1"></span>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                                <label>Event Location </label>
                                <div class="input-group">
                                  <input type="text" id="event_location" name="event_location" class="form-control valid" placeholder="Please enter event location " required data-error="#errNms2">
                                </div>
                                <span id="errNms2"></span>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                                <label>Event Date</label>
                                <div class="input-group calander">
                                  <input type="text" id="event_date" name="event_date" class="form-control valid" placeholder="Please select event date" required data-error="#errNms3">
                                  <div class="month">
                                    <i class="fas fa-calendar-alt"></i>
                                  </div>
                                </div>
                                <span id="errNms3"></span>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="btn_save_bttns">
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-cancel">Cancel</button>
                      <button id="sub_submit" type="submit" form-id="" class="btn btn-primary save_ticket_form ml-3">Confirm</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </div>
          
        </div>
      </div>
    </div>


<?php $this->load->view(THEME_NAME.'/common/footer');?>

<script type="text/javascript">
    
   var index = 0;
   function getticketQty_v1(direction){
      $('.getticketQty').each(function(i, obj) {
      $(this).prop('checked', false); 
      });
       $('#showmenu').prop("checked", true);
       $('#showmenu').val($('#slct').val());
   }
   getticketQty = function(direction) { 
      $('.getticketQty').each(function(i, obj) {
      $(this).prop('checked', false); 
      });
      if(direction >= 10){ 
         $('#showmenu').prop("checked", true);
         $('#showmenu').val(direction);
         $('#errNm2').text("");
      }
      else{

         $('#getticketQty_' + direction).prop("checked", true);
         $('#errNm2').text("");
      }
      
   
   };
   

   $(document).ready(function() {
   
      $("#event_date").datepicker(
         {  dateFormat: 'dd-mm-yy',
            changeYear: true ,changeMonth: true, }
      )

     $(document).ready(function() {
        $('#showmenu').click(function() {
                $('.menu').slideToggle("fast");
        });
    });

   $('#add_price_addlist').keyup(function(e)
   {
   var val = $(this).val();
   var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
   var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
   if (re.test(val)) {
   //do something here
   $('#web_price').val(val);
   } else {
   val = re1.exec(val);
   if (val) {
   $(this).val(val[0]);
   } else {
   $(this).val("");
   }
   
   }
   });
   
      // if ($('#add_event_name').length) new Choices('#add_event_name', {
      //    removeItemButton: !0
      // });
   
  <?php if(!empty($bulk_ticket_matches)){?>
    events("<?php echo $bulk_ticket_matches[0]->match_id;?>");
  <?php } ?>

   
     // $(document).on('click', "#searchmatch", function() {
       $(document).on('change', "#add_event_name", function() { 
         events( $("#add_event_name").val());
      });

        $(document).ready(function() {
          //   $('.js-example-basic-single').select2().on('change', function(e){

              
          // });;
            var  URL  = "<?php echo base_url(); ?>tickets/index/get_match_names";



            $('.js-example-basic-single').select2({
                ajax: {
                  url: URL,
                  placeholder: "Choose Match Event",
                   dataType: 'json',
                  data: function (params) {
                    var query = {
                      search: params.term,
                      type: 'public'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                  },
                  processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    return {
                      results: data
                    };
                  }
                }
              });


          /*  $('.js-example-basic-single').on('select2:selecting', function(e) {
              events(e.params.args.data.id);
          });
*/

        });

        
      $(document).on('click', ".delivery_options", function() { 
           $(this).parents(".yellowBackground").find('[type=radio]').prop('checked', true);
      });


       $(document).on('click', ".zoom_map_btn", function() { 
          $(".event_img").addClass("full_map");
          $(".zoom_map_btn").hide();    
      });

        $(document).on('click', ".close_map_html", function() { 
          $(".event_img").removeClass("full_map");
           $(".zoom_map_btn").show();
      });


      function events(id){ 
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>tickets/get_tktcat_by_stadium_id',
            data: {
               'match_id':id
            },
            dataType: "json",
            success: function(data) {
                
                console.log(data);
               $("#ticket_category").empty().html('<option value="" selected>Please select</option>');
               if (data.block_data) {
   
                  $.each(data.block_data, function(index, item) {
   
                     //$("#ticket_category").append('<option value="' + index + '">' + item + '</option>');
                     
                     $("#ticket_category").append('<option value="' + item.category + '">' + item.seat_category + '</option>');
   
                  })
                  var bdc = "<ul>";
                  $.each(data.block_data_color, function(index, item) {
   
                  bdc += "<li><span style='background:" +index +"'></span>"  + item +"</li>";
   
                  });
                  bdc +="</ul>";
                   $("#ticket_category_block").html(bdc);
                  //$("#left_event").show();
   
               }
               if (data.match_data) {
   
                  $('#res-match-name').html(data.match_data.match_name);
                  $('#res-match-date').html(data.match_data.match_date_format);
                  $('#res-match-time').html(data.match_data.match_time);
                  //$('#right_event').show();
   
               }
            }
         });
   
    var full_block_data = {};
    var stadium_block_details = {};
    var stadium_cat_details = {} ;
    var stadium_with_cat_name = {} ;
    var ticket_price_info_with_cat = {} ;
    var current_category = 0;
        $("#map_html").html("<p class='text-center'>Loading...</p>");
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>tickets/getMatchDetails',
            data: {
               'match_id': id
            },
            dataType: "json",
            success: function(data) { 
               let html = '';
               
               if (data.stadium_name != null) {
                  html += data.stadium_name + ', ';
               }
               if (data.city_name != null) {
                  html += data.city_name + ', ';
               }
               if (data.state_name != null) {
                  html += data.state_name + ', ';
               }
               if (data.country_name != null) {
                  html += data.country_name;
               }
               var ticket_type_option = '<option value="0">Any</option>'+
                                       '<option value="1">Home</option>'+
                                       '<option value="2">Away</option>';
               if (data.team1_name != null && data.team2_name != null) {
                  ticket_type_option += '<option value="'+data.team1_name+'">'+data.team1_name+'</option>';
                  ticket_type_option += '<option value="'+data.team2_name+'">'+data.team2_name+'</option>';
               }
               $('#home_town').html(ticket_type_option);
   
               $('#res-match-place').html(html);
               $('#matchticket').html(data.matchticket);
               $('#res-stadium-image').attr('src', data.stadium_image);
                if(data.event_type == 'other'){
                $('#event_hidden').val('OE');
                if(data.team2_image != ""){
                  $('#team1_image').attr('src', data.team1_image);
                $('#team2_image').attr('src', data.team2_image);
                }
                else{
                 $('#team1_image').attr('src', data.event_image); 
                }
                
                }
                else{
                $('#event_hidden').val('E');
                $('#team1_image').attr('src', data.team1_image);
                $('#team2_image').attr('src', data.team2_image);
                }
              /* $('#team1_image').attr('src', data.team1_image);
               $('#team2_image').attr('src', data.team2_image);*/
               $("#selec_img").css("display", "block");
               $("#map_html").html(data.stadium_html);
               $(".zoom_map").show();
               $(".map_stadium_name").html(data.stadium_name);
                


            }
         });
      

        // function a(stadiumValue){

        //    jQuery(".mapsvg").mapSvg(stadiumValue);
        // }
   
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>tickets/getCurrency_event',
            data: {
               'match_id': id
            },
            dataType: "json",
            success: function(data) {
   
               $("#add_pricetype_addlist").empty();
               if (data) {
                  $.each(data, function(index, item) {
   
                     $("#add_pricetype_addlist").append("<option value='" + item.currency_code + "'>" + item.symbol + ' ' + item.name + "</option>");
                  })
               }
   
            }
         });
       }
   
   
      $(document).on('change', "#ticket_category", function() {
   
   
         var match_id = $("#add_event_name").val();
         if(match_id == undefined){
          var match_id = "<?php echo $bulk_ticket_matches[0]->match_id;?>";
         }

         $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>' + 'tickets/get_block_by_stadium_id',
            data: {
               'match_id': match_id,
               'category_id': $('#ticket_category').val()
            },
            dataType: "json",
            success: function(data) {
   
               $("#ticket_block").empty().html('<option value="" selected>Please select</option>');
               if (data) {
                  $.each(data, function(index, item) {
   
                     $("#ticket_block").append('<option value="' + item + '">' + index + '</option>');
   
                  })
   
               }
            }
         });
   
      });
   
      $("#add_price_addlist").on("change", function(evt) {
         var self = $(this);
         //$("#add_price_addlist").attr("minlength", "2");
      /* if (self.val().length == 1 || parseInt(self.val()) < 10) {
            self.val('');
            $(this).focus();
            evt.preventDefault();
         }*/
   
         self.val(self.val().replace(/[^0-9\.]/g, ''));
         if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
         }
      });
   
   });
</script>

      <script>
  $(document).ready(function() {

    $('body').on("click",".list_ticket_type label",function() {
      //alert();
        // $('list_ticket_type').removeClass('yellowBackground');
         $(this).parents(".list_ticket_type").find("label").removeClass("yellowBackground");
        $(this).addClass('yellowBackground');

    });


    $('body').on("click",".rt-select",function() {
       $(".rt-radio").prop('checked', false);
       $(this).parents(".project-grid-item").find(".rt-radio").prop('checked', true);
    });


  $('body').on("click",".ticket_label",function() {
     // alert();
         //$(this).parents(".ticket_details_list_all").find(".yellowBackground");

          //$(document).on('click', ".ticket_label", function() {
       //$(this).closest('.tdcheckbox').prop("checked", true);
        //$(this).closest('[type=checkbox]').attr('checked', true);
         // jQuery(this).closest('input').find('[type=checkbox]').prop('checked', true);
     //  });

       var that = $(this);
          $(this).parent('li').addClass('yellowBackground_1');
         var ischecked= $(this).is(':checked');

       
         if(!ischecked){
      //alert('not');
         $(this).prop('checked', true);
            that.parent('li').addClass('yellowBackground_1');
         }else{ 
          $(this).prop('checked', false);
          that.parent('li').removeClass("yellowBackground_1");
           
         }

    });


   $('body').on("click",".select_detail_chk li",function() { 
     // alert();
         //$(this).parents(".ticket_details_list_all").find(".yellowBackground");

          //$(document).on('click', ".ticket_label", function() {
       //$(this).closest('.tdcheckbox').prop("checked", true);
        //$(this).closest('[type=checkbox]').attr('checked', true);
         // jQuery(this).closest('input').find('[type=checkbox]').prop('checked', true);
     //  });

      if($(this).find(".seat_type").length){

         $(".select_option_seating").find("li").removeClass("yellowBackground_1");

        $(".seat_type").each(function() {
           $(this).removeAttr('checked');
        });
        $(this).attr('checked','checked');
      /*  var ischecked= $(this).find('[type=checkbox]').is(':checked');
   
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
          $(this).addClass('yellowBackground_1');
         var ischecked= $(this).find('[type=checkbox]').is(':checked');

       
         if(!ischecked){
      //alert('not');
         $(this).find('[type=checkbox]').prop('checked', true);
            that.addClass('yellowBackground_1');
         }else{ 
          $(this).find('[type=checkbox]').prop('checked', false);
          that.removeClass("yellowBackground_1");
           
         }

    });


});
</script>

<script>
   window.onscroll = function() {myFunction()};
   
   var header = document.getElementById("myHeader");
   var sticky = header.offsetTop;
   
   function myFunction() {
     if (window.pageYOffset > sticky) {
       header.classList.add("sticky");
     } else {
       header.classList.remove("sticky");
     }
   }

  $(document).ready(function(){

  $("select").change(function(){
    if ($(this).val()=="") $(this).css({color: "#aaa"});
    else $(this).css({color: "#000"});
  });
  
}); 
</script>
