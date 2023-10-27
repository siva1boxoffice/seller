  <?php $this->load->view(THEME_NAME.'/common/header');?>
      <div class="main">
      <div class="container">


        <div class="row">
          <div class="col-lg-12">
            <div class="mt-5">
              <div class="search_options bulk_lists">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="head_topic">
                      <h2>Add Bulk Listings</h2>
                    </div>
                  </div>
                </div>
                <div class="row ml-5 mr-5">
                  <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                    <div class="search">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                           <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                          </div>
                          <input id="keyword" type="text" class="form-control one" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Tournament, Event, Venue" onkeyup="get_event_search();">
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 text-right">
                    <div class="row">
                      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad_five">

                        <div class="form-group">
                          <input type="text" class="datepick form-control one" id="fromDate" placeholder="Date From">
                        </div> 

                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad_five">
                        <div class="form-group">
                            <input type="text" class="datepick form-control one" id="toDate" placeholder="Date To">
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad_five">
                       <select id="tournament"  class="custom-select rt-select" onchange="get_event_search();">
                          <option value="">Tournament</option>
                          <?php foreach ($tournaments as $tkey => $tournament) { ?>
                          <option value="<?php echo $tournament->tournament_id;?>"><?php echo $tournament->tournament_name;?></option>   
                          <?php } ?>                       
                        </select>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad_five">
                      <!--   <a href="<?php echo base_url();?>tickets/index/create_ticket/bulk" class="btn theme-btn-1"><i class="fa fa-plus"></i> New Listing</a> -->
                      <button type="button" class="btn theme-btn-1 add_tickets"><i class="fa fa-plus"></i> New Listing</button>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
     
 <form id="save_mass_duplicate_<?php echo $list_ticket->ticketid; ?>" action="<?php echo base_url(); ?>tickets/index/save_mass_duplicate" class="save_mass_duplicate form-horizontal validate_form_v2" method="post" novalidate="novalidate">
        <section class="listing-orders list_odd_table bulk_listing_tab">
            <table class="table toptable_new_details">
              <tbody id="list_body">
                 <tr>
                  <th>Select</th>
                  <th>Event Name</th>
                  <th>Event Date Time (Local)</th>
                  <th>Tournament</th>
                  <th>Venue Name</th>
                  <th>Price Range</th>
                  <th>Tickets available</th>
                </tr>
               
              
              </tbody>
               <tfoot class="loading_img">
                <tr><td align="center" colspan="9"><span style="text-align:center !important; font-size: 18px; font-weight: bold;"><i class="fa fa-spinner fa-spin" style="color:rgb(0 55 213);"></i>&nbsp;Please Wait ...</span></td></tr>
              </tfoot>
            </table>
        </section>

        <div class="row">
          <div class="col-md-12 mt-2 mb-3">
              <div class="load_more_bttn">
                <button type="button" class="btn btn-primary load_more" style="display:none;">Load More</button>
              </div>
          </div>
          <div class="col-md-12 mt-2 mb-5">
            <div class="request_event">
              <button type="button" class="btn btn-primary req_tick">Request Event</button>
            </div>
            <div class="add_tick ml-3">
              <button type="button" class="btn btn-primary add_tickets">Add Tickets</button>          
            </div>
            <div class="add_or_cncl">
              <button type="button" class="btn btn-danger clear_selection">Cancel</button>            
            </div>
            
          </div>
        </div>
    </div>

   <input type="hidden" value="1" name="page" id="page_no">

<?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">

   
      $(window).load(function(){
        $("#content_1").mCustomScrollbar({
          scrollButtons:{
            enable:true
          }
        });
      });
        
</script>

 <script>

   
  $(document).ready(function() {


    bulmaCalendar.attach("#event-start", {
      startDate: new Date('<?php echo date('m/d/Y'); ?>'),
      color: themeColors.primary,
      lang: "en",
      showHeader: false,
      showButtons: false,
      showFooter: false
    });

    bulmaCalendar.attach("#event-end", {
      startDate: new Date('<?php echo date('m/d/Y', strtotime(date("m/d/Y") . ' +1 day')) ?>'),
      color: themeColors.primary,
      lang: "en",
      showHeader: false,
      showButtons: false,
      showFooter: false
    });


  });



 </script>

<script>
$(function() {
  if($('#fromDate, #toDate').length){
    // check if element is available to bind ITS ONLY ON HOMEPAGE
    var currentDate = moment().format("DD-MM-YYYY");

    $('#fromDate, #toDate').daterangepicker({
        locale: {
              format: 'DD-MM-YYYY'
        },
        "alwaysShowCalendars": true,
//"minDate": currentDate,
        // "maxDate": moment().add('months', 1),
        autoApply: true,
        autoUpdateInput: false
    }, function(start, end, label) {
      // console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
      // Lets update the fields manually this event fires on selection of range
      var selectedStartDate = start.format('DD-MM-YYYY'); // selected start
      var selectedEndDate = end.format('DD-MM-YYYY'); // selected end

      $checkinInput = $('#fromDate');
      $checkoutInput = $('#toDate');



      // Updating Fields with selected dates
      $checkinInput.val(selectedStartDate);
      $checkoutInput.val(selectedEndDate);

      // Setting the Selection of dates on calender on CHECKOUT FIELD (To get this it must be binded by Ids not Calss)
      var checkOutPicker = $checkoutInput.data('daterangepicker');
      checkOutPicker.setStartDate(selectedStartDate);
      checkOutPicker.setEndDate(selectedEndDate);

      // Setting the Selection of dates on calender on CHECKIN FIELD (To get this it must be binded by Ids not Calss)
      var checkInPicker = $checkinInput.data('daterangepicker');
      checkInPicker.setStartDate(selectedStartDate);
      checkInPicker.setEndDate(selectedEndDate);

      if ($('#fromDate').val() != "") {
        $('#fromDate').addClass('active-text');
    } else {
        $('#fromDate').removeClass('active-text');
    }

    if ($('#toDate').val() != "") {
        $('#toDate').addClass('active-text');
    } else {
        $('#toDate').removeClass('active-text');
    }
    
      var load_action = 'inactive';
      var limit = 50; //The number of records to display per request
      var start = 1; //The starting pointer of the data
      get_event_search(limit,start);
    });

} 


});

var limit = 50; //The number of records to display per request
var start = 1; //The starting pointer of the data
var load_action = 'inactive';

   // load_tickets('',0);

   if(load_action == 'inactive')
    {
        load_action = 'active';
        get_event_search(limit,start);
    }


function get_event_search(limit=50,start=1){

        $("#page_no").val(parseInt(start));
    //if(ticket_keyword.length >= 1){
        $(".loading_img").show();
    
        var keywords            = $("#keyword").val();
        var tournament          = $("#tournament").val();
        var event_start_date    = $("#fromDate").val();
        var event_end_date      = $("#toDate").val();
        var action   = base_url + "tickets/get_bulk_events";
       
        $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'keywords' : keywords,'tournament' : tournament,'event_start_date' : event_start_date,'event_end_date' : event_end_date,"limit" :  limit  , "page" :  start},
                  beforeSend: function() {
                  $(".loading_img").show();
                  // $("#state-list").addClass("loader");

                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                  // $('.order_right').css('opacity', '1');
                  $(".loading_img").hide();
                  $(".loading").hide();
                  },
                  success: function(data) {
                  
                    $(".loading_img").hide();
                    $(".loading").hide();

                            if(start == 1){
                              $('#list_body').html("");
                            } 
                            
                            
                            if(data.count >= 1)
                            {
                              $('#list_body').append(data.events);
                            //$('#load_data_message_a').remove();
                            load_action = 'inactive';
                            $('.load_more').show();
                            }
                            else
                            {   
                               if(start == 1){
                             $('#list_body').html(data.events); 
                                
                             }        
                             $('.load_more').hide();
                            //$('#load_data_message_a').html(loader);
                            load_action = 'active';
                            }

                            if(keywords != ""){
                              $('.load_more').hide();
                            load_action = 'active';
                            }
                  }
                  });
    //}   
    
}


$('.load_more').on('click',function(){
    
    if(load_action == 'inactive'){
      
      load_action = 'active';
      var start =  $("#page_no").val();
      $("#page_no").val( parseInt( start) + 1);
     get_event_search(limit,parseInt(start) + 1);
    }


})


   /* $(window).scroll(function(){

        if($(window).scrollTop() + $(window).height() > $("#list_body").height() && load_action == 'inactive')
        {
            load_action = 'active';
            var start =  $("#page_no").val();

            $("#page_no").val( parseInt( start) + 1);
              
                setTimeout(function(){

                  get_event_search(limit,parseInt(start) + 1);

            }, 1000);
        }
    });*/
$(document).ready(function(){

  $("select").change(function(){
    if ($(this).val()=="") $(this).css({color: "#aaa"});
    else $(this).css({color: "#000"});
  });


 //$('.datepick').keyup(function () {
  $( ".datepick" ).on( "click", function() { 

    if ($.trim($(this).val()).length) {
        $(this).addClass('active-text');
    } else {
        $(this).removeClass('active-text');
    }
});

  $('.form-control').keyup(function () {
    if ($.trim($(this).val()).length) {
        $(this).addClass('active-text');
    } else {
        $(this).removeClass('active-text');
    }
});


$('.custom-select').change(function () {
    if ($.trim($(this).val()).length) {
        $(this).addClass('active-text');
    } else {
        $(this).removeClass('active-text');
    }
});


$('.clear_selection').click(function () { 
    $('.matchcheck').each(function() {
                     $(this).prop('checked',false);
                });
});
  
}); 
</script>