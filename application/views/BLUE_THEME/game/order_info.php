<style type="text/css">
  /*.container {
  padding: 50px 10%;
}
*/
.toptable_new_details tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}

</style>
<style type="text/css">
  /*.container {
  padding: 50px 10%;
}
*/
.toptable_new_details tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}
span.align {
    text-align: left;
    font-weight: 700;
    margin-left: 0px;
   /* display: flex;
    align-items: center;
    justify-content: flex-start;*/
    font-size: 14px;
}
.fa-times-circle:before {
    content: "\f057";
    background: #fff;
    color: #D80027;
    font-size: 26px;
    margin-right: 10px;
    border-radius: 50%;
}
/*.listing_input{display: flex;align-items: center;}*/

.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: block;
    margin: 0px 10px;
}

.listing_input .btn {
    border: 2px solid gray;
    color: #4CB076;
    background-color: #f3f6fe;
    padding: 2px 20px;
    /* border-radius: 8px; */
    font-size: 14px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}
.listing_input {
     position: relative;
    display: flex;
    align-items: center;
}



.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.upload_inst ul {
    margin: 0px;
    padding: 0px;
    list-style-type: none;
}
.upload_inst ul li {
    display: inline-block;
}
.remove_ico, .view_ico {
    background: #00A1D5;
    color: #fff;
    font-size: 10px;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    text-align: center;
    vertical-align: middle;
    align-items: center;
    justify-content: center;
    z-index: 1;
    cursor: pointer;
}
.upload_inst {
    position: absolute;
    top: 40px;
    right: 10px;
}
.upload_pdf_tick {
    margin: 3px 30px;
    font-size: 12px;
    font-weight: 400;
    color: #4C5271;
}
.bottom_page{margin: 5px 0;float: left;width: 100%;}



.tdradio {
    width: 25px;
    height: 25px;
    vertical-align: middle;
}
.listing_input .radio input[type=radio] {
            accent-color: #2A9C24;
         }
.remove_ico a {color: #fff;}
.view_ico a{color:#fff;}
</style>
<form class="form-horizontal save_nominee_details" action="<?php echo base_url(); ?>game/orders/save_nominee" method="post" id="save_nominee_details" novalidate="novalidate">
<div class="row">
  <?php //echo "<pre>";print_r($nominees);?>
                  <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $orderData->booking_id;?>">

                  <div class="col-md-8">
                    <div class="section_left" id="content_1">

                      <div class="team_name">
                         <h3><?php echo $orderData->match_name;?> - <?php echo $orderData->tournament_name;?></h3>

                         <div class="show_ticket">
                            <div class="list_ticket">
                              <img src="<?php echo base_url().THEME_NAME;?>/images/tickets.svg"> <span class="tick_sts"><?php echo $orderData->quantity;?> Tickets</span>
                            </div>
                            <div class="tick_transfer">
                              <?php echo $orderData->ticket_type_name;?>
                            </div>
                         </div>


                         <p><?php echo date('l d F Y', strtotime($orderData->match_date));?> <?php echo $orderData->match_time; ?> </p>
                         <p><span><span><?php echo $orderData->stadium_name . ', ' .$orderData->stadium_city_name . ', ' . $orderData->stadium_country_name; ?></span></p>

                         <div class="order_category">
                            <div class="order_cat_section">
                             <span class="od_bold">Section:</span>  <?php echo $orderData->seat_category;?>
                            </div>
                             <div class="order_row">
                              <span class="od_bold">Block:</span><?php echo $orderData->ticket_block;?>
                            </div>
                            <div class="order_row">
                              <span class="od_bold">Row:</span><?php echo $orderData->row;?>
                            </div>
                         </div>
                       </div>

                       <div class="notes_all">
                        <div class="order_notes">
                         <h4>Seller Notes</h4>
                         <ul>
                          <?php if(isset($seller_notes)){ 
                            foreach($seller_notes as $seller_note){
                            ?>
                           <li><?php echo $seller_note->ticket_name;?></li>
                          <?php }} ?>
                         </ul>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="delivery_method">
                              <h4>Delivery Method:</h4>
                              <p>Online Transfer</p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="delivery_method">
                              <h4>Current Status</h4>
                              <p><?php if ($orderData->delivery_status == 0 || $orderData->delivery_status == '') { ?>
                        Tickets Not Uploaded
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 1) { ?>
                        Tickets In-Review
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 2) { ?>
                        Tickets Approved
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 3) { ?>
                        Tickets Rejected
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 4) { ?>
                        <i data-feather="download"></i> Tickets Downloaded
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 5) { ?>
                        Tickets Shipped
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 6) { ?>
                        Tickets Delivered
                        <?php } ?></p>
                            </div>
                          </div>
                        </div>
                       </div>

                     <!--   <div class="buyer_name">
                         <div class="row">
                           <div class="col-md-6">
                            <div class="delivery_method">
                              <h4>Buyer Name</h4>
                              <p><?php echo $orderData->customer_first_name ; ?> <?php echo $orderData->customer_last_name ; ?></p>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="delivery_method">
                              <h4>Buyer Email</h4>
                              <p><?php echo $orderData->email ; ?></p>
                            </div>
                          </div>
                         </div>
                       </div> -->


                      <div class="attendee_details">
                        <h4>Attendee Details</h4>
                        <?php if($orderData->tournament_id != 19){ ?>
                        <table class="table toptable_new_details">
                          <thead>
                            <tr>
                              <th scope="col" class="wid_10">Name</th>
                              <th scope="col" class="wid_10">Nationality</th>
                              <th scope="col" class="wid_5">Date of Birth</th>
                              <?php if($orderData->ticket_type == 2){ ?>
                             <!--  <th scope="col" class="">Upload E-Ticket</th> -->
                             <!--  <th scope="col">View</th> -->
                              <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 

                            //echo "<pre>";print_r($eticketData);
                            foreach($nominees as $nominee){

                            ?>
                            <input type="hidden" name="ticket_id[]" value="<?php echo $nominee->id;?>">
                            <tr>
                              <td data-label="Name"><input type="hidden" name="nominee[]" class="form-control" value="<?php echo $nominee->first_name;?> <?php echo $nominee->last_name;?>"><?php echo $nominee->first_name;?> <?php echo $nominee->last_name;?></td>
                              <td data-label="Nationality">
                                <input type="hidden" name="nationality[]" class="form-control" value="<?php echo $nominee->nationality;?>"><?php echo $nominee->nationality;?></td>
                                <td class="date_birth1" data-label="Date of Birth">
                                <input type="hidden"  id="datepicker-<?php echo $nominee->id;?>" name="dob[]" class="form-control" value="<?php echo date('d-m-Y',strtotime($nominee->dob));?>"><?php echo date('d F Y',strtotime($nominee->dob));?></td>
                               <?php if($orderData->ticket_type == 2){ ?>
                                <!-- <td data-label="Upload E-Ticket">
                                  <a class="ticket_upload" id="ticket_file_<?php echo $nominee->id;?>" data-id="<?php echo $nominee->id;?>" href="javascript:void(0);"><img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/upload-icon.svg"></a>
                                  <input type="file" accept="application/pdf,image/gif, image/jpeg, image/png" id="<?php echo $nominee->id;?>" name="eticket[]" style="display:none;" onchange="loadFile(event)">
                                </td> -->
                                <!-- <td data-label="View">
                                  <?php if($nominee->ticket_file != ""){ ?>
                                  <a target="_blank"  onClick="return popitup('<?php echo base_url();?>uploads/e_tickets/<?php echo $nominee->ticket_file;?>')" href="javascript:void(0)"><i class="fa fa-eye" aria-hidden="true"></i> <img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/download-icon.svg"></a>
                                <?php } else{ ?>Not Available<?php } ?>
                                </td> -->
                                 <?php } ?>
                            </tr>

                            


                            <?php  }?>
    
                          </tbody>
                        </table>
                      <?php } else{ ?>
                        <table class="table toptable_new_details">
                          <thead>
                            <tr>
                              <th scope="col" class="wid_10">Name</th>
                              <th scope="col" class="wid_10">Email</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 

                            //echo "<pre>";print_r($eticketData);
                            foreach($nominees as $nominee){

                            ?>
                        <input type="hidden" name="ticket_id[]" value="<?php echo $nominee->id;?>">
                        <input type="hidden"  id="datepicker-<?php echo $nominee->id;?>" name="dob[]"  value="<?php echo date('d-m-Y',strtotime($nominee->dob));?>">
                        <input type="hidden" name="nationality[]"  value="<?php echo $nominee->nationality;?>">
                            <tr>
                              <td data-label="Name"><input type="hidden" name="nominee[]"  value="<?php echo $nominee->first_name;?> <?php echo $nominee->last_name;?>">
                                <?php echo $nominee->first_name;?> <?php echo $nominee->last_name;?>
                              </td>
                              <td data-label="Email"><input type="hidden" name="email[]"  value="<?php echo $nominee->email;?>">
                                <?php echo $nominee->email;?>
                              </td>
                             
                               
                             
                            </tr>

                            


                            <?php  }?>
    
                          </tbody>
                        </table>
                      <?php } ?>
                        <?php if($orderData->ticket_type == 2){ ?>

                        <table class="table">
                          
                            <tr>
                              <td colspan="2" width="50%">
                                  
                                 
                                    <div class="dropzone-wrapper">
                                        <div class="dropzone-desc">
                                          <i class="glyphicon glyphicon-download-alt"></i>
                                          <p>Choose an ticket image / pdf  or drag it here.</p>
                                        </div>

                                       <input type="file" accept="application/pdf,image/gif, image/jpeg, image/png" id="<?php echo $nominee->id;?>" name="eticket[]" id="<?php echo $nominee->id;?>" class="dropzone" multiple >

                                      
                                      </div>
                              </td>
                              <td>  


                                  <div class="preview-zone ">
                                    <div class="box box-solid">
                                       <div class="box-header"><span>Uploaded Ticket</span></div>
                                        <div class="box-body">
                                          <p class='text-center box-loading' style="display:none">Please wait..</p>
                                          <ul id="sortable">
                                            
                                            <?php 
                                          //echo "<pre>";print_r($eticketData);
                                          foreach($tempFiles as $files){
                                          
                                          ?>

                                          <li class="ui-state-default" ><span target="_blank" class="open_path" data-name ="<?php echo $files->ticket_filename;?>" data-type="2" data-id="<?php echo $files->id;?>" > <i  class="fa fa-file"></i> <?php echo substr($files->ticket_filename,0,1500);?></span> 
                                            <?php if ($orderData->delivery_status == 2 || $orderData->delivery_status == 4 || $orderData->delivery_status == 5 || $orderData->delivery_status == 6) { ?>
                                              <span style="opacity: 0.3;" class="" data-type="2" data-id="<?php echo $files->id;?>"><i class="fa fa-trash"></i></span>
                                            <?php } else{?>
                                            <span class="delete_file_new" data-type="2" data-id="<?php echo $files->id;?>" data-ticket-id="<?php echo $files->ticket_id;?>" data-sort="<?php echo ($files->ticket_sort+1);?>"><i class="fa fa-trash"></i></span>
                                          <?php } ?>
                                          </li>
                                      <?php   }  ?>
                                       
                                   
                                          </ul>
                                      </div>
                                    </div>
                                  </div>
                                </td>
                            </tr>
    
                          
                        </table>
                        <?php } ?>
                      </div>
                                            <?php if($orderData->ticket_type != 2){ ?>
                      <div class="attendee_details">
                        <h4>Ticket Delivery Details</h4>
                        <table class="table toptable_new_details">
                          <thead>
                            <tr>
                              <th scope="col" class="wid_10">Delivery Provider</th>
                              <th scope="col" class="wid_10">Tracking Number</th>
                              <th scope="col" class="wid_5">Tracking Link</th>
                              <th scope="col" class="">Upload POD</th>
                              <th scope="col">View</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td data-label="Delivery Provider"><input type="text" name="delivery_provider" class="form-control" value="<?php echo $delivery->delivery_provider;?>"></td>
                              <td data-label="Tracking Number">
                                <input type="text" name="tracking_number" class="form-control" value="<?php echo $delivery->tracking_number;?>"></td>
                               <td data-label="Tracking Link">
                                <input type="text" name="tracking_link" class="form-control" value="<?php echo $delivery->tracking_link;?>"></td>
                                <td data-label="Upload POD">
                                  <a class="pod_upload"  data-id="<?php echo $delivery->id;?>" href="javascript:void(0);"><img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/upload-icon.svg"></a>
                                  <input type="file" accept="application/pdf,image/gif, image/jpeg, image/png" id="pod_file" name="pod_file" style="display:none;" onchange="loadPod(event)">
                                 
                                </td>
                                <td data-label="View">
                                  <?php if($delivery->pod != ""){ ?>
                                  <a download href="<?php echo base_url();?>/uploads/pod/<?php echo $delivery->pod;?>"><img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/download-icon.svg"></a>
                                <?php } else{ ?>Not Available<?php } ?>
                                </td>
                            </tr>

                      
                          </tbody>
                        </table>
                      </div>
                      <?php } ?>

                       <div class="attendee_details">
                        <h4>Upload Ticket QR</h4>
                        <?php 
                        //echo "<pre>";print_r($qr_links);
                        foreach($nominees as $tkey => $nominee){ ?>
                       <div class="row">
                              <!-- <div class="col-md-12"> 
                                <label>Ticket #<?php echo ($tkey+1);?> QR Link <span>*</span></label>
                                <div class="input-group">
                                  <input type="text" name="qr_link[]" class="form-control valid" placeholder="Please Enter QR Link" aria-label="QR Link" aria-describedby="basic-addon2" value="<?php echo $qr_links[$tkey]->qr_link;?>" aria-invalid="false">
                                </div>
                              </div> -->
                              <div class="col-md-6"> 
                                <label>Ticket #<?php echo ($tkey+1);?> QR Link For Android <span>*</span></label>
                                <div class="input-group">
                                  <input type="url" id="url_<?php echo ($tkey+1);?>" name="qr_link[]" class="form-control valid" placeholder="Please Enter QR Link" aria-label="QR Link" aria-describedby="basic-addon2" value="<?php echo $qr_links[$tkey]->qr_link;?>" aria-invalid="false" onblur="checkURL(this.value,'url_<?php echo ($tkey+1);?>')">
                                </div>
                                <span class="error qr_error" id="error_url_<?php echo ($tkey+1);?>"></span>
                              </div>

                              <div class="col-md-6"> 
                                <label>Ticket #<?php echo ($tkey+1);?> QR Link For IOS <span>*</span></label>
                                <div class="input-group">
                                  <input type="url" id="url_ios_<?php echo ($tkey+1);?>" name="qr_link_ios[]" class="form-control valid" placeholder="Please Enter QR Link" aria-label="QR Link" aria-describedby="basic-addon2" value="<?php echo $qr_links[$tkey]->qr_link_ios;?>" aria-invalid="false" onblur="checkURL(this.value,'url_ios_<?php echo ($tkey+1);?>')">
                                </div>
                                <span class="error qr_error" id="error_url_ios_<?php echo ($tkey+1);?>"></span>
                              </div>
                            </div>
                          <?php } ?>
                      </div>

                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="section_right">
                      <div class="order_id">
                        <h6>Order Placed</h6>
                        <p><?php echo date('D d, M Y', strtotime($orderData->created_at));?> </p>
                      </div>
                      <div class="order_id">
                        <h6>Order id</h6>
                        <p><?php echo $orderData->booking_no ; ?></p>
                      </div>
                      <div class="order_id">
                        <h6>listing id</h6>
                        <p><?php echo $orderData->ticket_group_id ; ?></p>
                      </div>
                      <div class="order_id">
                        <h6>total proceeds</h6>
                        <p>

                          <?php 
                                if (strtoupper($orderData->currency_type) == "GBP") { ?>
                                  £
                                <?php } ?>
                                <?php if (strtoupper($orderData->currency_type) == "EUR") { ?>
                                €
                                <?php } ?>
                                <?php if (strtoupper($orderData->currency_type) == "USD") { ?>
                               $
                                <?php } ?>
                                <?php if (strtoupper($orderData->currency_type) != "GBP" && strtoupper($orderData->currency_type) != "EUR" && strtoupper($orderData->currency_type) != "USD"){
                                echo strtoupper($orderData->currency_type); 
                                }
                                 ?>
                          <?php echo number_format($orderData->price*$orderData->quantity,2);?></p>
                      </div>
                       <div class="active_listing listing_input">
                          <div class="field">
          <div class="control">
              <label class="radio">
                  <input class="tdradio" type="radio" name="ticket_instruction_uploaded" id="ticket_instruction_uploaded" value="1"  <?php if($ticket_instruction_files->ticket_filename != ""){ ?> checked <?php } ?>>
                  &nbsp;<span class="align" id="ticket_instruction_append">Ticket Instructions</span>
                  <?php if($ticket_instruction_files->ticket_filename != ""){ ?>
                  <div class="upload_pdf_tick" id="ticket_instruction_preview"><?php echo $ticket_instruction_files->ticket_filename;?></div>
                        <div class="upload_inst">
                            <ul>
                               <li><div class="remove_ico"><a href="javascript:void(0);" class="delete_file_inc" data-type="2" data-id="<?php echo $ticket_instruction_files->ticket_id;?>"><i class=" far fa-trash-alt"></i></a></div></li>
                               <li><div class="view_ico"><a href="javascript:void(0);" target="_blank" class="open_path" data-name ="<?php echo $ticket_instruction_files->ticket_filename;?>" data-type="2" data-id="<?php echo $ticket_instruction_files->ticket_id;?>"><i class=" far fa-eye"></i></a></div></li>
                            </ul>
                         </div>
                  <?php } ?>
                          <div class="upload-btn-wrapper">
                            <button class="btn">Upload</button>
                            <input type="file" id="ticket_instruction" name="ticket_instruction" />
                          </div>
              </label>
            </div>
          </div>
                         
                        </div>
                    <!--   <div class="active_listing">
                         <input class="tdcheckbox"  id="ticket_delivered" type="checkbox" name="ticket_delivered"  <?php if ($orderData->booking_status == 5 || $orderData->delivery_status == '4' || $orderData->delivery_status == '6') { ?>
                        checked
                        <?php } ?> value="1">&nbsp;Check for Ticket Delivered
                        </div> -->
                       <div class="active_listing">
                         <!--  <input class="tdcheckbox" disabled id="ticket_delivered" type="checkbox" name="ticket_delivered"  <?php if ($orderData->delivery_status == 2 || $orderData->delivery_status == 4 || $orderData->delivery_status == 5 || $orderData->delivery_status == 6) { ?>
                        checked
                        <?php } ?> value="1"> --> <span class="align">
                          <?php if ($orderData->delivery_status == 0 || $orderData->delivery_status == '') { ?>
                        Tickets Not Uploaded
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 1) { ?>
                        Tickets In-Review
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 2) { ?>
                        Tickets Approved
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 3) { ?>
                        Tickets Rejected
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 4) { ?>
                        Tickets Downloaded
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 5) { ?>
                        Tickets Shipped
                        <?php } ?>
                        <?php if ($orderData->delivery_status == 6) { ?>
                        Tickets Delivered
                        <?php } ?>
                        </span>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="upload_opt">
                  <div class="row">
                      <div class="col-md-6">
                        <div class="btn_save">
                          <button type="submit" class="btn btn-primary">Upload</button>
                          <button type="button" class="btn btn-default change_ticket" data-match="<?php echo $orderData->match_name;?> - <?php echo $orderData->tournament_name;?>" data-date="<?php echo date('l d F Y', strtotime($orderData->match_date));?> <?php echo $orderData->match_time; ?>" data-stadium="<?php echo $orderData->stadium_name . ', ' .$orderData->stadium_city_name . ', ' . $orderData->stadium_country_name; ?>" data-tickets="<?php echo $orderData->quantity;?> Tickets" data-ticketstype="<?php echo $orderData->ticket_type_name;?>" data-section="<?php echo $orderData->seat_category;?>" data-block="<?php echo $orderData->ticket_block;?>" data-row="<?php echo $orderData->row;?>" data-ticket_type="<?php echo $orderData->ticket_type;?>" data-bgid="<?php echo $orderData->bg_id;?>" data-toggle="modal" data-target="#myModal_tick_type">Change Ticket Type</button>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="contact_us_link">
                          <a href="javascript:void(0);">Contact Us</a> | <a href="javascript:void(0);" class="report" data-toggle="modal" data-target="#myModal_seller_report_issue" data-order="<?php echo $orderData->bg_id;?>" data-match="<?php echo $orderData->match_id;?>" data-name="<?php echo $orderData->match_name;?> - <?php echo $orderData->tournament_name;?>" data-date="<?php echo date('l, d F Y', strtotime($orderData->match_date));?> <?php echo $orderData->match_time; ?>" data-venue="<?php echo $orderData->stadium_name . ', ' .$orderData->stadium_city_name . ', ' . $orderData->stadium_country_name; ?>">Report Event Issue</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
                 </form>







                <script type="text/javascript">

                   function checkURL(userInput,id) {
              
              $(".qr_error").each(function() {
              $(this).text("");
              });
              var res = userInput.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
              if(res == null){
              $("#"+id).val("");
              $("#error_"+id).text("Please Enter the valid URL.");
              return false;
              }
              else{
              return true;
              }
        
}

<?php 
foreach($nominees as $nominee){ ?>
 document.getElementById("datepicker-<?php echo $nominee->id;?>").defaultValue = "<?php echo date('Y-m-d',strtotime($nominee->dob));?>";

 $("#datepicker-<?php echo $nominee->id;?>").datepicker({ dateFormat: 'yy-mm-dd',  changeMonth: true,
    changeYear: true,
    yearRange: "c-100:c+100"
           });
<?php } ?>

                  var loadFile = function(event) { 
    //var output = document.getElementById();
    if(event.target.files[0]){ 
      $('#ticket_file_'+event.target.id).html('<img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/ticks.svg">');
    }
   /* output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.name) // free memory
    }*/
  };

    var loadPod = function(event) { 
    //var output = document.getElementById();
    if(event.target.files[0]){ 
      $('#pod_file').html('<img style="width: 15px;height: 15px;" src="<?php echo base_url().THEME_NAME;?>/images/ticks.svg">');
    }
   /* output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.name) // free memory
    }*/
  };

                   $( document ).ready(function() {
 
 

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
  $('#order_info_close').trigger('click');

})


$('.change_ticket').on('click',function(){
  var match_name  = $(this).attr('data-match');
  var match_date  = $(this).attr('data-date');
  var match_venue = $(this).attr('data-stadium');
  var tickets    = $(this).attr('data-tickets');
  var ticketstype    = $(this).attr('data-ticketstype');
  var section    = $(this).attr('data-section');
  var block    = $(this).attr('data-block');
  var row    = $(this).attr('data-row');
  var ticket_type    = $(this).attr('data-ticket_type');
  var bgid    = $(this).attr('data-bgid');
  $("#bg_id").val('');
  $("#ticket_match_name").text(match_name);
  $("#ticket_match_date").text(match_date);
  $("#ticket_match_stadium").text(match_venue);
  $("#ticket_match_tickets").text(tickets);
  $("#ticket_match_ticketstype").text(ticketstype);
  $("#ticket_match_section").text(section);
  $("#ticket_match_block").text(block);
  $("#ticket_match_row").text(row);
  $("#ticket_type").val(ticket_type);
  $("#bg_id").val(bgid);
  $('#order_info_close').trigger('click');

})


 $('.pod_upload').on('click',function(){
$('#pod_file').trigger('click');

})
 

$('.ticket_upload').on('click',function(){

var id = $(this).attr('data-id');
$('#'+id).trigger('click');

})

        // $("#content_1").mCustomScrollbar({
        //   scrollButtons:{
        //     enable:true
        //   }
        // });

       $( document ).ready(function() {
        $(window).resize(function(){
            if($(this).width()>768){
                $("#content_1").mCustomScrollbar(); //apply scrollbar with your options 
            }else{
                $("#content_1").mCustomScrollbar("destroy"); //destroy scrollbar 
            }
        }).trigger("resize");
    });


        $('.save_nominee_details').validate({
  submitHandler: function(form) { 
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
  
var submit = $('#'+$(form).attr('id')).find(':submit');
submit.attr("disabled", true);
submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...');


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
        submit.html("Submit");
     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
          submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});


      });

 function removeupload(){
       
        var boxZone = $(this).parents('.preview-zone').find('.box-body');
        var previewZone = $(this).parents('.preview-zone');
        var dropzone = $(this).parents('.form-group').find('.dropzone');
        boxZone.empty();
        //previewZone.addClass('hidden');
        reset(dropzone);
      }

$("#ticket_instruction").change(function() { 

$("#ticket_instruction_preview").remove();
$(".upload_inst").remove();
  readInsFile(this);
});

function readInsFile(input) {

 if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {

      var formData = new FormData();
      var files = input.files;
      for (var i = 0; i < files.length; i++) {
        formData.append('ticket_instruction[]', files[i]);
      }


      uploadTicketIncData(formData);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function readFile(input) {
  var total_file = $(".box-body li").length;
  if (parseInt(input.files.length  + total_file ) > <?php echo count($nominees);?>){

    swal({
      title: "You are only allowed to upload a maximum of <?php echo count($nominees);?> files",
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonColor: '#0CC27E',
      cancelButtonColor: '#FF586B',
      confirmButtonText: 'Close',
      cancelButtonText: 'No, cancel!',
      confirmButtonClass: 'button h-button is-primary',
      cancelButtonClass: 'button h-button is-danger',
      buttonsStyling: false
      }).then(function (res) {
      console.log('dismiss',dismiss);

      }, function (dismiss) {
        console.log('dismiss',dismiss);
    });

      $(".dropzone").val("");
      return false;
   }
   console.log(input.files.length)

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {

      var formData = new FormData();
      var files = input.files;
      for (var i = 0; i < files.length; i++) {
        formData.append('eticket[]', files[i]);
      }


      uploadFormData(formData);

     //var htmlPreview =  "<p>" + input.files[0].name +"</p> "

    //  var htmlPreview  ="<ul id='sortable'>";
    //   $.each(input.files, function (key, val) {
    //     console.log(key);
    //     console.log(val.name);

    //      htmlPreview +=  "<li>" +val.name +"</li> ";
    // });
    // htmlPreview +=  "</ul>";
      
   //  + '<a href="javascript:void(0)" class="remove-preview" onClick="removeupload()"><i class="fa fa-trash"></i></a></p>';
        // '<img width="200" src="' + e.target.result + '" />' +
       // '<p>' + input.files[0].name + '</p>';
      var wrapperZone = $(input).parent();
      // var previewZone = $(input).parents("tr").find('.preview-zone');
      // var boxZone = $(input).parents("tr").find('.preview-zone').find('.box').find('.box-body');
     
      // wrapperZone.removeClass('dragover');
      // previewZone.removeClass('hidden');
      // boxZone.empty() ;      
      // boxZone.append(htmlPreview);
     
    };

    reader.readAsDataURL(input.files[0]);
  }
} 


$(document).on('click','.delete_file_inc',function(){

     var id = $(this).data("id");

   
      var type = $(this).data("type");
      var that = $(this).parents("li");
      console.log(id);
    //console.log("delete_file_new");
    //  if (confirm('Are you sure ?')) {
   swal({
    title: 'Are you sure want to remove this ticket instruction file?',
    text: "Remove Ticket instruction !",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, Delete it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {

   
    if (res.value == true) {

    
      that.remove();
     $.ajax({
        url: "<?php echo base_url(); ?>game/orders/delete_instruction_temp_file/"+ type,
        method: "POST",
        data: {  'id'  : id },
        dataType: "json",
        success: function (data) {
          $("#ticket_instruction_preview").remove();
          $(".upload_inst").remove();
          
        }
      });
  // }
  }

    }, function (dismiss) {
      console.log('dismiss',dismiss);
  });

});

$(document).on('click','.delete_file_new',function(){

      var id = $(this).data("id");
      var ticket_id = $(this).data("ticket-id");
      var sort      = $(this).data("sort");
   
      var type = $(this).data("type");
      var that = $(this).parents("li");
      console.log(id);
    //console.log("delete_file_new");
    //  if (confirm('Are you sure ?')) {
   swal({
    title: 'Are you sure want to remove this ticket ?',
    text: "Remove E-Ticket !",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, Delete it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {

    console.log('delete_temp_file',res);
    if (res.value == true) {
console.log(ticket_id+'='+sort);
    
      that.remove();
     $.ajax({
        url: "<?php echo base_url(); ?>game/orders/delete_temp_file/"+ type,
        method: "POST",
        data: {  'id'  : id,'ticket_id' : ticket_id,'sort' : sort },
        dataType: "json",
        success: function (data) {
          sort_files();
        }
      });
  // }
  }

    }, function (dismiss) {
      console.log('dismiss',dismiss);
  });

});

 $( "#sortable" ).sortable();


  $( "#sortable" ).sortable({
        stop: function(evt, ui) {
          sort_files();
        }
    });

function sort_files(){

    var myArray = {};
    $("#sortable").find('li').each(function(key,index){
        console.log(key);
        var id = $(this).find("span.open_path").data('id');

        myArray[key] = id;
        console.log(myArray);
    });

       $.ajax({
        url: "<?php echo base_url(); ?>game/orders/update_tempfile_status/",
        method: "POST",
        data: { 'data'  :  JSON.stringify(myArray)},
        success: function (data) {

     }
   });
}

function uploadTicketIncData(form_data) {

  var booking_id = $("#booking_id").val();
      $.ajax({
        url: "<?php echo base_url(); ?>game/orders/save_ticket_instruction_temp_file/" + booking_id,
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (data) {
          var htmlPreview_ajax = "";
          $.each(data, function (key, val) { 

            htmlPreview_ajax +=   '<div class="upload_pdf_tick" id="ticket_instruction_preview">'+val.name+'</div>'+
                         '<div class="upload_inst">'+
                            '<ul>'+
                               '<li><div class="remove_ico"><a href="javascript:void(0);" class="delete_file_inc" data-type="2" data-id="'+val.id+'"><i class=" far fa-trash-alt"></i></a></div></li>'+
                               '<li><div class="view_ico"><a href="javascript:void(0);" target="_blank" class="open_path" data-name ="'+val.name+'" data-type="2" data-id="'+val.id+'"><i class=" far fa-eye"></i></a></div></li>'+
                            '</ul>'+
                         '</div>';

           /*htmlPreview_ajax +=   '<li class="ui-state-default" ><span target="_blank" class="open_path" data-name ="'+val.name+'" data-type="2" data-id="'+val.id+'" > <i  class="fa fa-file"></i> '+val.name+'</span> <span class="delete_file_new" data-type="2" data-id="'+val.id+'"><i class="fa fa-trash"></i></span></li>';*/
          });
          $("#ticket_instruction_append").append(htmlPreview_ajax);


        }
      });
    }


function uploadFormData(form_data) {
  $(".box-loading").show();
  $(".dropzone").prop('disabled', true);;

  var booking_id = $("#booking_id").val();
      $.ajax({
        url: "<?php echo base_url(); ?>game/orders/save_temp_file/" + booking_id,
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (data) {
          $(".box-loading").hide();
          $(".dropzone").prop('disabled', false);
          var htmlPreview_ajax = "";
          $.each(data, function (key, val) { 
           htmlPreview_ajax +=   '<li class="ui-state-default" ><span target="_blank" class="open_path" data-name ="'+val.name+'" data-type="2" data-id="'+val.id+'" > <i  class="fa fa-file"></i> '+val.name+'</span> <span class="delete_file_new" data-type="2" data-id="'+val.id+'"><i class="fa fa-trash"></i></span></li>';
          });
          $(".box-body ul").append(htmlPreview_ajax);


        }
      });
    }

 $('body').on('click','.open_path',function(){
      var file_name = $(this).data("name");
      return popitup("<?php echo base_url();?>uploads/e_tickets/" + file_name);
});




function reset(e) {
  e.wrap('<form>').closest('form').get(0).reset();
  e.unwrap();
}

$(".dropzone").change(function() {
  readFile(this);
});

$('.dropzone-wrapper').on('dragover', function(e) {
  e.preventDefault();
  e.stopPropagation();
  $(this).addClass('dragover');
});

$('.dropzone-wrapper').on('dragleave', function(e) {
  e.preventDefault();
  e.stopPropagation();
  $(this).removeClass('dragover');
});



function popitup(url) {
newwindow=window.open(url,'name','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,,height=500,width=700');
if (window.focus) {newwindow.focus()}
return false;
}

jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();


</script>
