  <?php $this->load->view(THEME_NAME.'/common/header');?>
        <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <style type="text/css">
        .table {border: 1px solid #ccc;}
        .table .thead-light td {color: #000;background-color: #e9ecef;border-color: #dee2e6;font-weight: bold;}
        .table .thead-light th {color: #000;background-color: #e9ecef;border-color: #dee2e6;font-weight: bold;text-transform: uppercase;}
     </style>
   <div class="main page_full_widd">
      <div class="container">
        <div class="row">
          <div class="col-lg-1 order_left px-0">
            <div class="order_filt filter-collsed">
              <h2>Order Filters</h2>

              <img class="list_head  menu_click_expand" src="<?php echo base_url().THEME_NAME;?>/images/menus.svg">
             

                
              <div class="order_filter_options" onclick="payout_data('GBP');">
                  <div class="tooltip_new">
                    <div class="order_options">
                        <div class="tooltip_order_lists_right">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/pound_v1.svg">
                          <h4>Payout in GBP</h4>
                        </div>
                  
                    </div>
                    <span class="tooltiptext">Payout in GBP</span>
                  </div>
              </div>

               <div class="order_filter_options" onclick="payout_data('USD');">
                  <div class="tooltip_new">
                    <div class="order_options">
                        <div class="tooltip_order_lists_right">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/dollar_v1.svg">
                          <h4>Payout in USD</h4>
                        </div>
                  
                    </div>
                    <span class="tooltiptext">Payout in USD</span>
                  </div>
              </div>

               <div class="order_filter_options" onclick="payout_data('EUR');">
                  <div class="tooltip_new">
                    <div class="order_options">
                        <div class="tooltip_order_lists_right">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/euro_v1.svg">
                          <h4>Payout in EUR</h4>
                        </div>
                    </div>
                    <span class="tooltiptext">Payout in EUR</span>
                  </div>
              </div>
               

            </div>

            <div class="slide_open slide_filter">
              <a href="javascript:void(0)" class="slide_expand">
                <img class="slide_right_arrow" src="<?php echo base_url().THEME_NAME;?>/images/right-arrow.svg">
                <img class="slide_left_arrow" src="<?php echo base_url().THEME_NAME;?>/images/left-arrow.svg"></a>

            </div>
          </div>
     
          <div class="col-lg-11  order_right">
            <div class="mt-2">
     

        <section class="recent-orders" id="content_2">
      
        </section>

    </div>


    <div class="my_modal">
        

         <div class="row">
    <div class="container" style="height: 100px;"></div>
   </div>
      <input type="hidden" value="1" name="page" id="page_no">
      <input type="hidden" value="1" name="filter" id="filter" value="all">
   <?php $this->load->view(THEME_NAME.'/common/footer');?>


    <script type="text/javascript">
      payout_data('GBP');
      function payout_data(currency = ''){

        if(currency != ""){

            var action = "<?php echo base_url();?>payout/payout_history_ajax";
    
               $.ajax({
                type: "POST",
                dataType: "json",
                 url: action,
                data: {"currency":currency},
                success: function(list) {
                  $('#content_2').html(list.payouts);
              }
              });

        }

      }


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
          $(".order_left").addClass("col-lg-1");
          $(".order_left").removeClass("col-lg-3");
          $(".order_right").addClass("col-lg-11");
          $(".order_right").removeClass("col-lg-9");
          $(".slide_filter a").addClass("slide_expand");
          $(".slide_filter a").removeClass("slide_collpase");
          $(".order_left").removeClass("opened_slider")
        });


</script>