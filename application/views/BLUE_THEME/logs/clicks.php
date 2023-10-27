  <?php $this->load->view(THEME_NAME.'/common/header');?>
  <style type="text/css">
    .sort_by .active{ background: #0037d5; }
    .sort_by .active a{ color: #FFF; }

  .title_hover {
  position: relative;
  display: inline-block;
  margin-top: 20px;
}

.title_hover[title]:hover::after {
  content: attr(title);
  position: absolute;
  top: -100%;
  right: 0;
  background: #98b1e9;
  padding: 10px;
  border-radius: 5px;
   inline-size: 250px;
    overflow-wrap: break-word;
}
</style> 
   <div class="main">
      <div class="container">
        <div class="row">
          
     
          <div class="col-lg-12  order_right">
          

         <?php  if ($this->session->userdata('role') == 2) { ?>
      

        <section class="recent-orders">
          <h3 class="dash-title fs-20 float-left"><span class="logs_class">Clicks</span> Logs</h3>
            <!-- <span class="float-right"><a href="" > <img src="<?php echo base_url().THEME_NAME;?>/images/icon_excel.svg" > Download orders on to an Excel file</a></span> -->
            <table class="table table-striped">

              <tbody id="list_ajax">
               
              
              </tbody>

            </table>
        </section>
      <?php } ?>
    </div>



   <?php $this->load->view(THEME_NAME.'/common/footer');?>
    <script type="text/javascript">
    search_logs("Checkout");

    $(".search_logs").on("click",function(){
        $(this).parents(".sort_by").find("li").removeClass("active");
        $(this).parents("li").addClass("active");
    });

  function search_logs(request_type){
    $(".logs_class").html(request_type)

    var keyword = "";
$('#list_ajax').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    

    var action = base_url + "logs/clicks_ajax";
    
       $.ajax({
        type: "POST",
        dataType: "json",
        url: action,
        data: {'request_type' : request_type,"keyword" : keyword},
        success: function(list) {
          console.log(list.orders);
          $('#list_ajax').html(list.orders);
      },
        error : function(){
             $('#list_ajax').html('Server Error');
        }
    });

  }


</script>