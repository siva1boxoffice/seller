  <?php $this->load->view(THEME_NAME.'/common/header');?>
  <style type="text/css">
    .sort_by .active{ background: #0037d5; }
    .sort_by .active a{ color: #FFF; }
  </style>
   <div class="main">
      <div class="container">
        <div class="row">
          
     
          <div class="col-lg-12  order_right">
            <div class="mt-5">
              <div class="row">
                <div class="col-lg-12">
                  <!-- <div class="search">
                      <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                        </div>
                        <input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="File Name" onkeyup="search_logs(this.value);">
                      </div>
                  </div> -->
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="sort_filter">
                    <div class="sort">
                      <span>Filter By:</span>
                    </div>
                    <div class="sort_by">
                      <ul>
                        
                       
                        <li class=""><a href="javascript:void(0);" class="search_logs"  onclick="search_logs('Events');"> Events</a></li>
                        <li><a href="javascript:void(0);" class="search_logs" onclick="search_logs('Tickets');">Tickets</a></li>
                        <li><a href="javascript:void(0);" class="search_logs" onclick="search_logs('Ticket Create');">Create Tickets</a></li>
                       
                        <li><a href="javascript:void(0);" class="search_logs" onclick="search_logs('Ticket Update');">Edit Tickets</a></li>
                        <li><a href="javascript:void(0);" class="search_logs" onclick="search_logs('Ticket Delete');">Delete Tickets</a></li>
                        <!-- <li><a href="javascript:void(0);" class="search_logs" onclick="search_logs('delete tickets');">Pushing Tickets</a></li> -->
                       
                        
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

      

        <section class="recent-orders">
          <h3 class="dash-title fs-20 float-left"><span class="logs_class">Events</span> Logs</h3>
            <!-- <span class="float-right"><a href="" > <img src="<?php echo base_url().THEME_NAME;?>/images/icon_excel.svg" > Download orders on to an Excel file</a></span> -->
            <table class="table table-striped">

              <tbody id="list_ajax">
               
              
              </tbody>

            </table>
        </section>
   
    </div>



   <?php $this->load->view(THEME_NAME.'/common/footer');?>
    <script type="text/javascript">
    search_logs("Events");

    $(".search_logs").on("click",function(){
        $(this).parents(".sort_by").find("li").removeClass("active");
        $(this).parents("li").addClass("active");
    });

  function search_logs(request_type){
    $(".logs_class").html(request_type)

    var keyword = "";
$('#list_ajax').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    

    var action = base_url + "logs/ajax";
    
       $.ajax({
        type: "POST",
        dataType: "json",
        url: action,
        data: {'request_type' : request_type,"keyword" : keyword},
        success: function(list) {
          console.log(list);
          $('#list_ajax').html(list.orders);
      },
        error : function(){
             $('#list_ajax').html('Server Error');
        }
    });

  }

</script>