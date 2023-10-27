  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
     <style type="text/css">
/*.table {border: 1px solid #ccc;}*/
.table .thead-light td {color: #000;background-color: #f2f2f2;border-color: #dee2e6;font-weight: bold;}
/*.table .thead-light th {color: #1A1919;background-color: #f2f2f2;border-color: #dee2e6;font-weight: 700;font-size: 13px;}*/
.table tbody tr th {color: #1A1919;background-color: #fff;border-color: #dee2e6;font-weight: 700;font-size: 14px;}
.thead-light th {font-size: 13px;}
/*.table tbody tr td {font-size: 13px;background: #f9f9f9;color: #867c7d;}*/
.table tbody tr td a{color: #0037D5;font-weight: bold;}
.table tbody tr td a:hover{color: #337ab7;text-decoration: unset;}
/*.wid_20{width: 30%;}*/
/*.wid_20{width: 15%;}
.wid_10{width: 10%;}*/
.table_head{font-size: 16px;font-weight: bold;color: #463944;}
.fa, .fas{font-size: 11px;}
/*.table td:nth-of-type(6) {text-align: right;}
.table .thead-light th:nth-of-type(6) {text-align: right;}
.table td:nth-of-type(5) {text-align: right;}
.table .thead-light th:nth-of-type(5) {text-align: right;}*/
/*.table td:nth-of-type(4) {text-align: right;}
.table td:nth-of-type(3) {text-align: right;}*/
/*.table .thead-light th:nth-of-type(4) {text-align: right;}
.table .thead-light th:nth-of-type(3) {text-align: right;}*/


/*.table tbody tr th:nth-of-type(2){text-align: right;}
.table tbody tr th:nth-of-type(3){text-align: right;}
.table tbody tr th:nth-of-type(4){text-align: right;}
.table tbody tr th:nth-of-type(5){text-align: right;}
.table tbody tr th:nth-of-type(6){text-align: right;}*/



.table_payment .table {border: 1px solid #E8EAEF;}
.table_payment .table .thead-light th{background-color: #F6F8FA;color: #1A1919;font-size: 14px;font-weight: 700;border-color: #E8EAEF;}
.table_payment .table thead th {border-bottom: 0px solid #E8EAEF;}
.table_payment .table td, .table_payment .table th {border-top: 1px solid #E8EAEF;}
.table_payment .table tbody tr td {font-size: 14px;background: #FFFFFF;color: #1A1919;font-weight: 400;}

.filter_opt .sort_filters ul li:nth-child(1){width:100%;flex:0 0 14%}
.filter_opt .sort_filters ul li:nth-child(2){width:100%;flex:0 0 14%}
.filter_opt .sort_filters ul li:nth-child(3){width:100%;flex:0 0 14%;margin-right:10px;border-right: 0px;}
.filter_opt .sort_filters ul li:nth-child(4){width:100%;flex:0 0 14%;border-right: 0px;}
.filter_opt .sort_filters ul li:nth-child(5){display: flex;justify-content: flex-end;margin-right: 20px;border-right: 0px;}
.nopadds{padding-right:0}
.sort_by{border:1px solid #E8EAEF;padding:11px 0;float:left;width:100%;color:#414D96;text-transform:inherit;text-align:center;background-color:#fff;font-weight:700}
.sort_filters{background:#F6F8FA}.sort_filters ul{padding:0;list-style-type:none;margin-bottom:0;display:flex;align-items:center;flex-wrap:wrap}
.sort_list .btn-group{display:block}
.sort_list .btn-light{background-color:#F6F8FA;border-color:#F6F8FA}
.sort_list .dropdown-toggle{display:flex;align-items:center;text-align:center;justify-content:space-between;width:100%;font-weight:600;color:#414D96;font-size:13px}
.dropdown-item{color:#1A1919}
.sort_filters ul li{border-right:1px solid #E8EAEF;align-items:center;flex-basis:0;flex-grow:1;justify-content:center;text-align:center;font-weight:600;color:#414D96;padding: 5px 0;}
a.clear_all{font-weight:800;border-bottom:2px solid;color:#0037D5}
a.report_sts{color:#fff}
.datemark{position: relative;}
.datemark_to{position: relative;}
.datemark .form-control {
    height: 30px;
    font-size: 12px;
    border-radius: 0px;
    font-weight: 600;
    background: #F6F8FA;
    color: #B4C0DE;
    border-color: #B4C0DE;
}
.datemark_to .form-control {
    height: 30px;
    font-size: 12px;
    border-radius: 0px;
    font-weight: 600;
    background: #F6F8FA;
    color: #B4C0DE;
    border-color: #B4C0DE;
}
::placeholder{color: #B4C0DE;}
.datemark::after {
    content: '\f078';
    font-family: "Font Awesome 5 Free"; 
    font-size: 12px;
    color: #414D96;
    /* left: 1rem; */
    top: 50%;
    right: 12px;
    position: absolute;
    text-indent: 0;
    transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
}
.datemark_to::after {
    content: '\f078';
    font-family: "Font Awesome 5 Free"; 
    font-size: 12px;
    color: #414D96;
    /* left: 1rem; */
    top: 50%;
    right: 12px;
    position: absolute;
    text-indent: 0;
    transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
}
.ui-datepicker th {
    color: #333333;
}
.ui-datepicker td a{color: #000 !important;}

td.month_color {
    background: #FAFBFF !important;
    color: #00A3ED !important;
    padding: 15px;
}
.order_right {
      -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
  }
  .order_filter_options.active {
    background: #EAEAEA;
}
</style>
    
   <div class="main page_full_widd">
        <div class="container mt-5">
            <div class="row">
              <div class="col-lg-1 order_left px-0">
                <div class="order_filt filter-collsed">

                  <img class="list_head  menu_click_expand" src="<?php echo base_url().THEME_NAME;?>/images/menus.svg">
                  <div class="order_filter_options" id="order_filter_options_gbp" onclick="payout_data('GBP');">
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
                   <div class="order_filter_options" id="order_filter_options_usd" onclick="payout_data('USD');">
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
                   <div class="order_filter_options" id="order_filter_options_eur" onclick="payout_data('EUR');">
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
                   <div class="order_filter_options" id="order_filter_options_aed" onclick="payout_data('AED');">
                      <div class="tooltip_new">
                        <div class="order_options">
                            <div class="tooltip_order_lists_right">
                              <img src="<?php echo base_url().THEME_NAME;?>/images/aed.png">
                              <h4>Payout in AED</h4>
                            </div>
                        </div>
                        <span class="tooltiptext">Payout in AED</span>
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
                    <div class="filter_opt">               
                        <div class="row">
                            <div class="col-md-1 nopadds">
                                <div class="sort_by">
                                    <span>Sort By:</span>
                                </div>
                            </div>
                            <div class="col-md-11">
                                <div class="sort_filters">
                                    <ul>
                                        <li class="sort_list">
                                            <div class="btn-group">
                                                <div class="dropdown">
                                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="selected_payment_id">Payment ID</span> <i class="mdi mdi-chevron-down"></i></button>
                                                    <div id="payment_id"
                                                        class="selected_payment_id dropdown-menu dropdown-menu-custom"
                                                        aria-labelledby="dropdownMenuButton"
                                                        x-placement="bottom-start"
                                                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);"
                                                    >
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="sort_list">Payment Date Filter</li>
                                        <li class="sort_list">
                                             <div class="form-group datemark mb-0">
                                                <input class="form-control" id="fromdate" type="text" name="fromdate" placeholder="From">
                                             </div>
                                        </li>
                                        <li class="sort_list">
                                             <div class="form-group datemark_to mb-0">
                                                <input class="form-control" id="todate" type="text" name="todate" placeholder="To">
                                             </div>
                                        </li>
                                        <li class="sort_list">
                                            <a class="clear_all" href="javascript:void(0);">Clear All</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                     </div>
                   <div class="table_payment mt-3" id="content_2">
                   </div>
                </div>
              </div>
            </div>
        </div>
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
    <!-- <div class="row">
    <div class="container" style="height: 100px;"></div>
   </div> -->
      <input type="hidden" value="1" name="page" id="page_no">
      <input type="hidden" value="1" name="filter" id="filter" value="all">
   <?php $this->load->view(THEME_NAME.'/common/footer');?>



   <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

   
    <script type="text/javascript">
      payout_data('GBP');
      function payout_data(currency = ''){

        if(currency != ""){
            $('#filter').val(currency);
            var txnid= '';
            var fromdate = $('#fromdate').val();
            var todate   = $('#todate').val();
            $(".selected_payment_id .active").each(function() {
            txnid= $(this).attr('data-id');
            });

            $(".order_filter_options").each(function() {
             $(this).removeClass('active');
            });

            if(currency == "GBP"){
              $('#order_filter_options_gbp').addClass('active');
            }
            else if(currency == "USD"){
              $('#order_filter_options_usd').addClass('active');
            }
            else if(currency == "EUR"){
              $('#order_filter_options_eur').addClass('active');
            }
            else if(currency == "AED"){
              $('#order_filter_options_aed').addClass('active');
            }
            var action = "<?php echo base_url();?>payout/payment_history_ajax";
    
               $.ajax({
                type: "POST",
                dataType: "json",
                 url: action,
                data: {"currency":currency,"txnid" : txnid,"fromdate" : fromdate,"todate" : todate},
                success: function(list) {
                  $('#content_2').html(list.payouts);
                  var payment_id = '';
                  if(list.payout_txns.length > 0){
                     $.each(list.payout_txns, function(key,value) {
                     payment_id +='<a class="dropdown-item choose_payment_id" data-id="'+value.value +'" data-label="'+value.label +'" href="javascript:void(0);">'+value.label+'</a>'
                    });
                   $('#payment_id').html(payment_id);
                  }
                  
              }
              });

        }

      }


        $("body").on("click",".choose_payment_id",function(){
          $(".choose_payment_id").each(function() {
          $(this).removeClass('active');
          });
          $(this).addClass('active');
          $("#selected_payment_id").text($(this).attr('data-label'));
            payout_data($('#filter').val());
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
          $(".order_left").addClass("col-lg-1");
          $(".order_left").removeClass("col-lg-3");
          $(".order_right").addClass("col-lg-11");
          $(".order_right").removeClass("col-lg-9");
          $(".slide_filter a").addClass("slide_expand");
          $(".slide_filter a").removeClass("slide_collpase");
          $(".order_left").removeClass("opened_slider")
        });

          $(document).ready(function () {
   //$("#fromdate").datepicker();
  // $("#todate").datepicker();

   $("#fromdate").datepicker({
    dateFormat: 'dd-mm-yy',
    changeYear: true ,changeMonth: true,
    onSelect: function(dateText) {
        payout_data($('#filter').val());
    }
});

      $("#todate").datepicker({
        dateFormat: 'dd-mm-yy',
            changeYear: true ,changeMonth: true,
    onSelect: function(dateText) {
        payout_data($('#filter').val());
    }
});

  $("body").on("click",".clear_all",function(){
    $("#fromdate").val('');
    $("#todate").val('');
    $(".choose_payment_id").each(function() {
      $(this).removeClass('active');
    });
    $("#selected_payment_id").text("Payment ID");
      payout_data($('#filter').val());
  });
   

  
}); 

</script>