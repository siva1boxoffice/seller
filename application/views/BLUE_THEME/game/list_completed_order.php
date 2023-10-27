  <?php $this->load->view(THEME_NAME.'/common/header');?>
  <style type="text/css">
    .btn-uploaded, 
    .btn-upload-1{
      border-radius: 0;
      width: 100px;
      text-align: center;
    }
  </style>
   <div class="main">
      <div class="container">
        <div class="row">
         
     
          <div class="col-lg-12  order_right">
            <div class="mt-5">
              <div class="row">
                <div class="col-lg-12">
                  <div class="search">
                      <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                        </div>
                        <input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Event, Venue, City, Section or Order ID" onkeyup="get_order_search(this.value,'','','completed');">
                      </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="sort_filter">
                    <div class="sort">
                      <span>Sort By:</span>
                    </div>
                    <div class="sort_by">
                      <ul>
                        <li><a href="javascript:void(0);" id="sale_date_click" sort-attr="DESC" onclick="get_order_search('','sale_date',this,'completed');">Sale Date</a></li>
                        <li><a href="javascript:void(0);" id="event_name_click" sort-attr="ASC" onclick="get_order_search('','event_name',this,'completed');">Event Name</a></li>
                        <li><a href="javascript:void(0);" id="event_date_click" sort-attr="DESC" onclick="get_order_search('','event_date',this,'completed');">Event Date</a></li>
                        <li><a href="javascript:void(0);" id="proceeds_click" sort-attr="ASC" onclick="get_order_search('','proceeds',this,'completed');">Proceeds</a></li> 
                        <li><a href="javascript:void(0);" id="ticket_type_click" sort-attr="DESC" onclick="get_order_search('','ticket_type',this,'completed');">Ticket Type</a></li> 
                        
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        

         <?php  if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) { ?>
      

        <section class="recent-orders">
          <h3 class="dash-title fs-20 float-left">Orders</h3>
            <!-- <span class="float-right"><a href="" > <img src="<?php echo base_url().THEME_NAME;?>/images/icon_excel.svg" > Download orders on to an Excel file</a></span> -->
            <table class="table table-striped">

              <tbody id="lis_order_ajax">
               
              
              </tbody>

            </table>
        </section>
      <?php } ?>
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

   <?php $this->load->view(THEME_NAME.'/common/footer');?>
    <script type="text/javascript">
   get_orders();
  function get_orders(){

$('#lis_order_ajax').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    

    var action = base_url + "game/orders/get_ajax_orders";
    
     $.ajax({
      type: "POST",
      dataType: "json",
       url: action,
      data: {"filter":'completed'},
      success: function(list) {
        $('#lis_order_ajax').html(list.orders);
    }
    });

  }

   
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

      $(window).load(function(){
        $("#content_1").mCustomScrollbar({
          scrollButtons:{
            enable:true
          }
        });
      });
        

       $("body").on("click",".slide_filter a.slide_expand",function(){
          $(".order_filt").removeClass("filter-collsed");
          $(".order_left").removeClass("col-lg-1");
          $(".order_left").addClass("col-lg-3");
          $(".order_right").removeClass("col-lg-11");
          $(".order_right").addClass("col-lg-9");
          $(".slide_filter a").removeClass("slide_expand");
          $(".slide_filter a").addClass("slide_collpase");
        });

        $("body").on("click",".slide_filter a.slide_collpase",function(){
       
          $(".order_filt").addClass("filter-collsed");
          $(".order_left").addClass("col-lg-1");
          $(".order_left").removeClass("col-lg-3");
          $(".order_right").addClass("col-lg-11");
          $(".order_right").removeClass("col-lg-9");
          $(".slide_filter a").addClass("slide_expand");
          $(".slide_filter a").removeClass("slide_collpase");
        });

</script>