              <form id="save_mass_duplicate_<?php echo $list_ticket->ticketid; ?>" action="<?php echo base_url(); ?>tickets/index/save_mass_duplicate" class="save_mass_duplicate form-horizontal validate_form_v2" method="post" novalidate="novalidate">
              <input type="hidden" name="s_no" value="<?php echo $list_ticket->s_no; ?>">
              <input type="hidden" name="match_id" value="<?php echo $list_ticket->match_id; ?>">
            <h4 class="clone-title fs-18">Clone Listing</h4>
            <p class="fs-16 mt-3"><i class="fa-solid fa-circle-info info-icon"></i> By cloning this listing you are confirming that all information provided about the listings you want to create, except the fields you explicitly change, are the same.</p>
            <h3 class="clone-match-title fs-18 mt-4"><?php echo $list_ticket->match_name; ?> - <?php echo $list_ticket->tournament_name; ?></h3>
            <p class="clone-match-date fs-16"><?php echo date('D d M Y', strtotime($list_ticket->match_date));?> <?php echo $list_ticket->match_time; ?></p>
            <p class="clone-match-stadium fs-15" ><?php echo $list_ticket->stadium_name . ', ' .$list_ticket->country_name . ', ' . $list_ticket->city_name; ?></p>
            <div class="clone-listing-table-div">
              <table class="table  table-borderless clone-listing-table">
                  <tr>

                    <td class="w-20">Ticket type</td>
                    <td class="w-20">Section</td>
                    <td class="w-10">Block</td>
                    <td class="w-10">Row</td>
                    <td class="w-20">Split type</td>
                    <td>Qty</td>
                    <td>Price</td>
                    <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                  </tr>
                  <tr id="clone-listing-table-tr" class="tbl_row">
                    <td>
                     <select data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-type-<?php echo $list_ticket->ticketid; ?>" name="ticket_type[]" id="ticket" class="form-control custom-select">
                      <?php foreach ($ticket_types as $ticket_type) { ?>
                      <option value="<?php echo $ticket_type->id; ?>" <?php if ($list_ticket->ticket_type == $ticket_type->id) { ?> selected="selected" <?php } ?>><?php echo $ticket_type->tickettype; ?></option>
                      <?php } ?>
                      </select>
                    </td>
                    <td>
                     <select name="ticket_category[]" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-category-<?php echo $list_ticket->ticketid; ?>" data-match="<?php echo $list_ticket->match_id; ?>" class="ticket_category form-control custom-select" data-flag="clone">
                              <?php foreach ($tkt_categories as $tktkey => $tkt_category) {
                                 ?>
                              <option value="<?php echo $tkt_category->category; ?>" <?php if ($tkt_category->category == $list_ticket->ticket_category) { ?> selected="selected" <?php } ?>><?php echo $tkt_category->seat_category; ?></option>
                              <?php } ?>
                           </select>
                    </td>
                    <td>
                     <select name="ticket_block[]" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="clone-block-<?php echo $list_ticket->ticketid; ?>" class="ticket_block form-control custom-select" data-flag="block">
                           <option value="0" <?php if ($list_ticket->ticket_block=='') { ?> selected="selected" <?php } ?>>Any</option>
                              <?php foreach ($blocks_data as $blkkey => $block_data) {
                                 $block = explode('-',$block_data->block_id);
                                 ?>
                              <option value="<?php echo $block_data->id; ?>" <?php if ($block_data->id == $list_ticket->ticket_block) { ?> selected="selected" <?php } ?>><?php echo strtoupper(end($block)); ?></option>
                              <?php } ?>
                           </select>
                    </td>
                    <td> <input type="text" class="form-control" placeholder="" aria-label="Available Tickets" aria-describedby="basic-addon2" name="row[]" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-row-<?php echo $list_ticket->ticketid; ?>" value="<?php echo $list_ticket->row; ?>"></td>
                    <td>
                      <select name="split[]" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-split-<?php echo $list_ticket->ticketid; ?>" class="ticket_split form-control custom-select">
                              <?php foreach ($split_types as $split_type) { ?>
                              <option value="<?php echo $split_type->id; ?>" <?php if ($list_ticket->split == $split_type->id) { ?> selected="selected" <?php } ?>><?php echo $split_type->name; ?></option>
                              <?php } ?>
                           </select>
                   </td>
                    <td><input type="text" name="quantity[]" class="form-control ticket_price" placeholder="" aria-label="Available Tickets" aria-describedby="basic-addon2" value="<?php echo $list_ticket->quantity;?>"></td>

                    <td> <input type="text" name="price[]" data-ticket="<?php echo $list_ticket->ticketid; ?>" id="ticket-price-<?php echo $list_ticket->ticketid; ?>" class="ticket_price form-control" value="<?php echo $list_ticket->price; ?>"  placeholder="900" aria-label="" aria-describedby="basic-addon1"></td>
                    <td><a href="javascript:void(0)" class="clone_ticket
"><i class="fas fa-plus"></i></a>
<a href="javascript:void(0)" class="td_close" style="display: none;"><i class="fa-solid fa-circle-xmark"></i></a></td>
                  </tr>

              </table>
            </div>
            <p><div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input terms_condtions" id="customCheck1" value="1">
                <label class="custom-control-label fs-15" for="customCheck1">I agree to List my Ticket’s <a href="">Terms and Conditions</a>. I confirm I own these tickets or have the right to be issued these tickets. If you are unable to deliver the correct tickets, List my Ticket reserves the right to charge you the cost of replacing the tickets for your buyer.</label>
              </div></p>
              <div class="row">
                <div class="col-lg-6">
                  <a href="javascript:void(0)" class="clone_ticket btn clone-listing-btn fs-16"><i class="fas fa-plus"></i> Add Another Clone</a>
                </div>
                <div class="col-lg-6">
                  <p class="text-right">
                 <!--    <button id="clone_submit" class="btn clone-listing-save-btn fs-16" disabled> Save</button> -->
                     <button id="clone_submit" data-url="save_mass_duplicate_<?php echo $list_ticket->ticketid; ?>" type="button" class="btn clone-listing-save-btn fs-16 save_mass_duplicates" disabled="true" >Save</button>
                    <button class="btn clone-listing-cancel-btn fs-16" data-dismiss="modal"> Cancel</button>
                  </p>
                </div>
              </div>
            </form>
         <script type="text/javascript">

            $(document).on('keyup', '.ticket_price', function(){
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

           $(document).on('click', '.terms_condtions', function(){
                if ($(this).is(":checked")) {
                $('#clone_submit').attr('disabled',false);
                } else {
                $('#clone_submit').attr('disabled',true);
                }
           });
          
           $(".clone_ticket").on("click",function(){

            
            var tbl_row_length   = $(".tbl_row").length;
            if(tbl_row_length <= 49){

            var tableBody = $('.clone-listing-table').find("tbody");
            var trLast    = tableBody.find("tr:last");
            var clone   = trLast.clone();
       // var clone   = $("#clone-listing-table-tr").clone();
    /*    clone.find('input').val('');
        clone.find('select').val('');*/
        clone.find(".td_close").show();
        clone.find(".clone_ticket").remove();
         //var selects = $("#clone-listing-table-tr").find("select");
         var selects = $(".clone-listing-table tr:last").find("select");
        $(selects).each(function(i) { 
        var datetime = new Date();
        var select = this;
        if($(select).attr('data-flag') == "clone"){ 
          $(clone).find("select").eq(i).attr('data-ticket',$(select).attr('data-ticket')+datetime.getTime());
        }
         if($(select).attr('data-flag') == "block"){ 
          $(clone).find("select").eq(i).attr('id',$(select).attr('data-ticket')+datetime.getTime());
        }
        $(clone).find("select").eq(i).val($(select).val());
        $(clone).find("select").eq(i).attr('id',$(select).attr('id')+datetime.getTime());
        });
        $(".clone-listing-table tr:last").after(clone);
        $(".td_close").on("click",function(){ 
        $(this).parent("td").parent("tr").remove();

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

      });
    }
    else{
        alert("Maximum cloning Limit Reached.");
    }
      });

       
         </script>