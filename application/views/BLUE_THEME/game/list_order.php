  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     
  <style type="text/css">
    .btn-uploaded, 
    .btn-upload-1{
      border-radius: 0;
      width: 100px;
      text-align: center;
    }    

    .page-item  a{
   position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #039871;
    background-color: #fff;
    border: 1px solid #dee2e6;

}

.pagination{
  float: right;
}

.page-item.active .page-link {
  
    background-color: #039871;
    border-color: #039871;
}

.recent-orders{
  height: auto;
}
/* Absolute Center Spinner */
.loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

/* Transparent Overlay */
/*.loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
}*/

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgb(0 55 213) 1.5em 0 0 0, rgb(0 55 213) 1.1em 1.1em 0 0, rgb(0 55 213) 0 1.5em 0 0, rgb(0 55 213) -1.1em 1.1em 0 0, rgb(0 55 213) -1.5em 0 0 0, rgb(0 55 213) -1.1em -1.1em 0 0, rgb(0 55 213) 0 -1.5em 0 0, rgb(0 55 213) 1.1em -1.1em 0 0;
    box-shadow: rgb(0 55 213) 1.5em 0 0 0, rgb(0 55 213) 1.1em 1.1em 0 0, rgb(0 55 213) 0 1.5em 0 0, rgb(0 55 213) -1.1em 1.1em 0 0, rgb(0 55 213) -1.5em 0 0 0, rgb(0 55 213) -1.1em -1.1em 0 0, rgb(0 55 213) 0 -1.5em 0 0, rgb(0 55 213) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
.order_right {
      -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
  }
  </style>
   <div class="main page_full_widd">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 order_left opened_slider px-0" id="side_filter">
          </div>
         
     
          <div class="col-lg-9  order_right">
            <div class="mt-2">
              <div class="row">
                <div class="col-lg-12">
                  <div class="search">
                      <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                        </div>
                        <input type="text" id="barsearch" class="form-control" aria-label="Large" flag="all" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Event, Venue, City, Section or Order ID" onkeyup="get_order_search(this.value,'','',this.getAttribute('flag'));">
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
                        <li class="sortli"><a href="javascript:void(0);" id="sale_date_click" class="get_order_search" data-sort-attr="DESC"   data-type="sale_date" >Sale Date</a></li>
                        <li class="sortli"><a href="javascript:void(0);" id="event_name_click" class="get_order_search" data-sort-attr="ASC" data-type="event_name"  >Event Name</a></li>
                        <li class="sortli"><a href="javascript:void(0);" id="event_date_click" class="get_order_search" data-sort-attr="DESC" data-type="event_date"  >Event Date</a></li>
                        <li class="sortli"><a href="javascript:void(0);" id="proceeds_click" class="get_order_search" data-sort-attr="ASC" data-type="proceeds"  >Proceeds</a></li> 
                        <li class="sortli"><a href="javascript:void(0);" id="ticket_type_click" class="get_order_search" data-sort-attr="DESC" data-type="ticket_type"  >Ticket Type</a></li> 
                        <!-- <li><a href="javascript:void(0);" id="delivered" sort-attr="DESC" onclick="get_order_search('','delivered',this,'all');">Delivered</a></li>
                        <li><a href="javascript:void(0);" id="rejected" sort-attr="DESC" onclick="get_order_search('','rejected',this,'all');">Rejected</a></li>  -->
                        <!-- <li><a href="">Ship by date</a></li>
                        <li><a href="">Event date filter</a></li>  
                        <li><div class="form-group menu" style="">
                          <select class="form-control" id="exampleFormControlSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div></li>
                        <li><div class="form-group menu" style="">
                          <select class="form-control" id="exampleFormControlSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div></li> -->
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        

         <?php  if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) { ?>
      

        <section class="recent-orders" id="content_2">
         <!--  <h3 class="dash-title fs-20 float-left">Orders</h3> -->
            <!-- <span class="float-right"><a href="" > <img src="<?php echo base_url().THEME_NAME;?>/images/icon_excel.svg" > Download orders on to an Excel file</a></span> -->
            <table class="table table-striped">

              <tbody id="lis_order_ajax">
              
              </tbody>
              <tfoot class="loading_img">
                <tr><td align="center" colspan="9"><span style="text-align:center !important; font-size: 18px; font-weight: bold;"><i class="fa fa-spinner fa-spin" style="color:rgb(0 55 213);"></i>&nbsp;Please Wait ...</span></td></tr>
              </tfoot>
            </table>
            <!-- <div id="pagination"></div> -->
        </section>

         <div class="row">
    <div class="container" style="height: 100px;"></div>
   </div>

      <?php } ?>
    </div>


    <div class="my_modal">
        <div class="modal fade bd-example-modal-lg" id="myLargeModalLabel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" id="order_info_close" class="close" data-dismiss="modal" aria-label="Close">
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
    </div>

   <div class="loading" style="display: none;"></div>
      <input type="hidden" value="1" name="page" id="page_no">
      <input type="hidden" value="1" name="filter" id="filter" value="uploadticket">
   <?php $this->load->view(THEME_NAME.'/common/footer');?>


    <script type="text/javascript">

$(document).on('click','.order_filter_options',function() {

  $(".order_filter_options").each(function() {
   $(this).removeClass("order_filter_active");
  });
    $(this).addClass("order_filter_active");
})



$('.sortli').on('click',function(){

  $(".sortli").each(function() {
   $(this).removeClass("sort_active");
  });
    $(this).addClass("sort_active");
})

load_side_filter();
function load_side_filter(){

  var action_url = base_url + "game/orders/load_side_filter";

        $.ajax({
            type: "POST",
            url: action_url,
            dataType: "json",
            beforeSend: function() {
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
            },
            success: function(data){
              if(data.load_side_filter){
                $("#side_filter").html(data.load_side_filter);
              }
            }
        });

}

$('#pagination').on('click','a',function(e){  
       e.preventDefault();   
       var pageno = $(this).attr('data-ci-pagination-page');  
       //loadPagination(pageno); 
        var filter = $("#filter").val();
         load_data(filter,10,pageno); 
     });  


    var limit = 10; //The number of records to display per request
    var start = 1; //The starting pointer of the data
    var action = 'inactive'; //Check if c

       //load_data('all',limit,start);
     // function load_data(filter='uploadticket',limit, start,type="event_date",sort="ASC")
      function load_data(filter='confirmed',limit, start,type="event_date",sort="ASC")
    { 


      $("#page_no").val(parseInt(start));
      $("#filter").val(filter);
      document.getElementById("barsearch").setAttribute("flag", filter);

      $(".loading_img").show();
      // $(".loading").show();
     // $('.order_right').css('opacity', '0.3');
       var action_url = base_url + "game/orders/get_ajax_orders/"+start;

        if(start == 1){
        $('#lis_order_ajax').html("");
        }
        $.ajax({
            type: "POST",
            url: action_url,
            dataType: "json",
            data: {"filter":filter, "limit" :  limit  , "page" :  start, "sort_label" :  type, "sort_value" :  sort},
            beforeSend: function() {
              $(".loading_img").show();
                // $("#state-list").addClass("loader");
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
             // $('.order_right').css('opacity', '1');
              $(".loading_img").hide();
              $(".loading").hide();
            },
            success: function(data){
               // $('.order_right').css('opacity', '1');
                $(".loading_img").hide();
                $(".loading").hide();

               $('#pagination').html(data.pagination);
                if(start == 1){
                    $('#lis_order_ajax').html("");
                }
              
                $('#lis_order_ajax').append(data.orders);
                //$('#lis_order_ajax').html(data.orders);

               // console.log(data.html);
                 if(data.orders !="")
                {
                    //$('#load_data_message_a').remove();
                    action = 'inactive';
                }
                else
                {                
                    //$('#load_data_message_a').html(loader);
                    action = 'active';
                }

  
               
            }
        });


    }

    if(action == 'inactive')
    {
        action = 'active';
        load_data("confirmed",limit, 1);
    }

    $(window).scroll(function(){

        if($(window).scrollTop() + $(window).height() > $("#lis_order_ajax").height() && action == 'inactive')
        {
            action = 'active';
            var start =  $("#page_no").val();

            $("#page_no").val( parseInt( start) + 1);
              var filter =   $("#filter").val();
                setTimeout(function(){

                   var type = $(".sortli.sort_active a").data("type");
                   var sort = $(".sortli.sort_active a").data("sort-attr");
                   if(sort == "DESC"){
                    sort = "ASC";
                   }
                   else{
                    sort = "DESC";
                   }
                   console.log(sort+" "+type)
                load_data(filter,limit, parseInt(start) + 1,type,sort);
            }, 1000);
        }
    });

    $(".get_order_search").on("click",function(){
        var type = $(this).data("type");
        var sort = $(this).data("sort-attr");

        if(sort == "DESC"){
            $(this).data("sort-attr", "ASC");
         }
       else if(sort == "ASC"){
          $(this).data("sort-attr", "DESC");
       }

         var filter = $("#filter").val();
         load_data(filter,limit, 1,type,sort);
        
    });

  // function load_data(filter='all'){

  //   $('#lis_order_ajax').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');

  //   var action = base_url + "game/orders/get_ajax_orders";
    
  //    $.ajax({
  //     type: "POST",
  //     dataType: "json",
  //      url: action,
  //     data: {"filter":filter},
  //     success: function(list) {
  //       $('#lis_order_ajax').html(list.orders);
  //   }
  //   });

  // }

   
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
        // $("#content_2").mCustomScrollbar({
        //   scrollInertia: 3500,
        //   mouseWheelPixels: 170,
        //   autoDraggerLength:false,
        //   scrollButtons:{
        //     enable:true
        //   }
        // });
      });
        

       $("body").on("click",".slide_filter a.slide_expand,  .menu_click_expand",function(){
          $(".order_filt").removeClass("filter-collsed");
          $(".order_left").removeClass("col-lg-1");
          $(".order_left").addClass("col-lg-3");
          $(".order_right").removeClass("col-lg-11");
          $(".order_right").addClass("col-lg-9");
          $(".slide_filter a").removeClass("slide_expand");
          $(".slide_filter a").addClass("slide_collpase");
          $(".order_left").addClass("opened_slider")
        });

        $("body").on("click",".slide_filter a.slide_collpase",function(){
       
          $(".order_filt").addClass("filter-collsed");
          $(".order_left").removeClass("col-lg-3");
          $(".order_left").addClass("col-lg-1");
          $(".order_right").addClass("col-lg-11");
          $(".order_right").removeClass("col-lg-9");
          $(".slide_filter a").addClass("slide_expand");
          $(".slide_filter a").removeClass("slide_collpase");
          $(".order_left").removeClass("opened_slider")
        });


</script>