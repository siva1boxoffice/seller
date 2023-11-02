  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
     <style>

/*.table {border: 1px solid #ccc;}
.table .thead-light th {color: #1A1919;background-color: #f2f2f2;border-color: #dee2e6;font-weight: 700;font-size: 13px;}
.table tbody tr td {font-size: 13px;background: #f9f9f9;color: #867c7d;}
.wid_20{width: 30%;}
.wid_20{width: 15%;}
.wid_10{width: 10%;}
.table td:nth-of-type(6) {text-align: right;}
.table .thead-light th:nth-of-type(6) {text-align: right;}
.table td:nth-of-type(5) {text-align: right;}
.table .thead-light th:nth-of-type(5) {text-align: right;}
.table td:nth-of-type(4) {text-align: right;}
.table td:nth-of-type(3) {text-align: right;}
.table .thead-light th:nth-of-type(4) {text-align: right;}
.table .thead-light th:nth-of-type(3) {text-align: right;}
.table tbody tr th:nth-of-type(2){text-align: right;}
.table tbody tr th:nth-of-type(3){text-align: right;}
.table tbody tr th:nth-of-type(4){text-align: right;}
.table tbody tr th:nth-of-type(5){text-align: right;}
.table tbody tr th:nth-of-type(6){text-align: right;}*/

.table .thead-light td {color: #000;background-color: #f2f2f2;border-color: #dee2e6;font-weight: bold;}
.table tbody tr th {color: #1A1919;background-color: #fff;border-color: #dee2e6;font-weight: 700;font-size: 14px;}
.thead-light th {font-size: 13px;}
.table tbody tr td a{color: #0037D5;font-weight: bold;}
.table tbody tr td a:hover{color: #337ab7;text-decoration: unset;}
.table_head{font-size: 16px;font-weight: bold;color: #463944;}
/*.fa, .fas{font-size: 11px;}*/

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

td.month_color {background: #FAFBFF !important;color: #00A3ED !important;padding: 15px;}
.order_right {-webkit-transition: all 0.5s ease;-moz-transition: all 0.5s ease;-o-transition: all 0.5s ease;transition: all 0.5s ease;}
.order_filter_options.active {background: #EAEAEA;}


/*************new ************/

.balance_show {background: #f5f5f5;padding: 10px 20px;position: relative;margin-bottom: 20px;border-radius: 5px;}
.price_range {display: flex;align-items: center;justify-content: space-between;}
.price_hold {width: 100%;max-width: 130px;}
.balance {font-size: 16px;margin: 0 0 15px 0;color: #475877;font-weight: 600;}
.flag_country img {width: 40px;height: 40px;margin-bottom: 10px;}
.total_value span {color: #46d4a0;font-size: 20px;font-weight: 700;}
.total_value p {color: #a5acc8;font-size: 15px;}
span.price_val {color: #5d638d;font-size: 14px;font-weight: 700;}
span.price_on_hold {color: #e04763;font-size: 14px;font-weight: 700;}
.price_delivery p {color: #565555;}
.balance_show .fa-info-circle{color: #4cb076;}
.tabs_sections {margin: 30px 0;}
.tabs_sections li {margin: 0 25px 0 0;}
.tabs_sections li a {font-size: 18px;color: #000;font-weight: 600;}
.status_report {margin: 15px 0;}
.sale_status {max-width: 160px;width: 100%;display: inline-block;margin-right: 20px;}
.status {max-width: 160px;width: 100%;display: inline-block;float: left;}
.down_report {float: right;margin: 15px 0;}
.down_report .btn-primary {color: #fff;background-color: #0037D5;border-color: #0037D5;font-size: 13px;}
.table-details-all {background: #fff !important;}
select.custom-select{height: 35px;}
.table-details-all thead th {border-bottom: 0px solid #dee2e6;border-top: 0px solid #dee2e6;}
.color-org {color: #ff8227;}
.color-red {color: #d80027;}
.color-green {color: #03ba8a;}
.table-details-all .fa, .table-details-all .fas {font-size: 18px;}
.tabs_sections .nav-tabs .nav-item.show .nav-link, .tabs_sections .nav-tabs .nav-link.active {
    border-bottom: 3px solid #0037D5;border-color: none;
}
.nav-tabs .nav-link {border: none;}
.status_report label{font-weight: 600;}
span.date_time {color: blue;text-align: center;font-size: 11px;}
.search_status {max-width: 220px;width: 100%;display: inline-block;float: left;margin-right: 20px;}
.search_status .example input[type=text] {padding: 10px;font-size: 15px;border: 1px solid #b5b8c4;float: left;width: 80%;background: #fff;height: 35px;border-right: 0px;outline: none;}
.search_status .example button {float: left;width: 20%;padding: 0px;background: #fff;color: #b5b8c4;font-size: 15px;border: 1px solid #b5b8c4;border-left: none;cursor: pointer;height: 35px;}
.search_status .example button:hover {background: #fff;outline: none;}
.search_status .example::after {content: "";clear: both;display: table;}
.edit_buttn {position: absolute;right: 25px;top: 15px;text-decoration: underline;
    font-size: 14px;text-transform: capitalize;}
.edit_buttn a{color: #5d638d;}

.payout_loader{
    float: left;
    width: 100%;
    margin: 30px auto;
    text-align: center;
}
</style>


<div class="main">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="balance">Balances</div>
            </div>
            <div class="col-md-3">
                <div class="balance_show">
                    <div class="flag_country">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/uk-flag.svg">
                            <div class="edit_buttn">
                         <?php if($bank_accounts_gbp->bank_id != ""){?>
                                
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="GBP" class="check_type" data-account="<?php echo $bank_accounts_gbp->bank_id;?>">Edit</a>

                              <?php }else{ ?>
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="GBP" class="check_type">Add</a>
                              <?php } ?>
                            </div>
                    </div>
                  
                    <div class="price_range">
                        <div class="price_delivery">
                            <span class="price_val">£ <?php echo $pending_delivery_gbp->total_ticket_amount ? number_format($pending_delivery_gbp->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Delivery <i class="fas fa-info-circle"></i></p>
                        </div>
                        <div class="price_hold">
                            <span class="price_on_hold">£ <?php echo $holding_gbp->total_hold_amount ? number_format($holding_gbp->total_hold_amount,2) : number_format(0,2);?></span>
                            <p>on Hold <i class="fas fa-info-circle"></i></p>
                        </div>
                    </div>
                     <div class="price_delivery">
                        <span class="price_val">£ <?php echo $pending_payout_gbp->total_ticket_amount ? number_format($pending_payout_gbp->total_ticket_amount,2) : number_format(0,2);?></span>
                        <p>Pending Payout <i class="fas fa-info-circle"></i></p>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="balance_show">
                    <div class="flag_country">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/euro_flag.png">
                        <div class="edit_buttn">
                         <?php if($bank_accounts_eur->bank_id != ""){?>
                                
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="EUR" class="check_type" data-account="<?php echo $bank_accounts_eur->bank_id;?>">Edit</a>

                              <?php }else{ ?>
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="EUR" class="check_type">Add</a>
                              <?php } ?>
                            </div>
                    </div>
                    <!-- <div class="total_value">
                        <span>€ <?php echo $pending_payout_eur->total_ticket_amount ? number_format($pending_payout_eur->total_ticket_amount,2) : number_format(0,2);?></span>
                        <p>Available Balance</p>
                    </div> -->
                    <div class="price_range">
                        <div class="price_delivery">
                            <span class="price_val">€ <?php echo $pending_delivery_eur->total_ticket_amount ? number_format($pending_delivery_eur->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Delivery <i class="fas fa-info-circle"></i></p>
                        </div>
                        <div class="price_hold">
                            <span class="price_on_hold">€ <?php echo $holding_eur->total_hold_amount ? number_format($holding_eur->total_hold_amount,2) : number_format(0,2);?></span>
                            <p>on Hold <i class="fas fa-info-circle"></i></p>
                        </div>
                    </div>
                    <div class="price_delivery">
                            <span class="price_val">€ <?php echo $pending_payout_eur->total_ticket_amount ? number_format($pending_payout_eur->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Payout <i class="fas fa-info-circle"></i></p>
                        </div>
                   
                </div>
            </div>

            <div class="col-md-3">
                <div class="balance_show">
                    <div class="flag_country">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/usd_flag.png">
                        <div class="edit_buttn">
                         <?php if($bank_accounts_usd->bank_id != ""){?>
                                
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="USD" class="check_type" data-account="<?php echo $bank_accounts_usd->bank_id;?>">Edit</a>

                              <?php }else{ ?>
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="USD" class="check_type">Add</a>
                              <?php } ?>
                            </div>
                    </div>
                   <!--  <div class="total_value">
                        <span>$ <?php echo $pending_payout_usd->total_ticket_amount ? number_format($pending_payout_usd->total_ticket_amount,2) : number_format(0,2);?></span>
                        <p>Available Balance</p>
                    </div> -->
                    <div class="price_range">
                        <div class="price_delivery">
                            <span class="price_val">$ <?php echo $pending_delivery_usd->total_ticket_amount ? number_format($pending_delivery_usd->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Delivery <i class="fas fa-info-circle"></i></p>
                        </div>
                        <div class="price_hold">
                            <span class="price_on_hold">$ <?php echo $holding_usd->total_hold_amount ? number_format($holding_usd->total_hold_amount,2) : number_format(0,2);?></span>
                            <p>on Hold <i class="fas fa-info-circle"></i></p>
                        </div>
                    </div>
                    <div class="price_delivery">
                            <span class="price_val">$ <?php echo $pending_payout_usd->total_ticket_amount ? number_format($pending_payout_usd->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Payout <i class="fas fa-info-circle"></i></p>
                        </div>
                </div>
            </div>
           
            <div class="col-md-3">
                <div class="balance_show">
                    <div class="flag_country">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/aed_flag.png">
                        <div class="edit_buttn">
                         <?php if($bank_accounts_aed->bank_id != ""){?>
                                
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="AED" class="check_type" data-account="<?php echo $bank_accounts_aed->bank_id;?>">Edit</a>

                              <?php }else{ ?>
                                <a href="javascrip:void(0);" data-toggle="modal" data-target="#myModal1" data-type="AED" class="check_type">Add</a>
                              <?php } ?>
                            </div>
                    </div>
                   <!--  <div class="total_value">
                        <span>AED <?php echo $pending_payout_aed->total_ticket_amount ? number_format($pending_payout_aed->total_ticket_amount,2) : number_format(0,2);?></span>
                        <p>Available Balance</p>
                    </div> -->
                    <div class="price_range">
                        <div class="price_delivery">
                            <span class="price_val">AED <?php echo $pending_delivery_aed->total_ticket_amount ? number_format($pending_delivery_aed->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Delivery <i class="fas fa-info-circle"></i></p>
                        </div>
                        <div class="price_hold">
                            <span class="price_on_hold">AED <?php echo $holding_aed->total_hold_amount ? number_format($holding_aed->total_hold_amount,2) : number_format(0,2);

                            ?></span>
                            <p>on Hold <i class="fas fa-info-circle"></i></p>
                        </div>
                    </div>
                    <div class="price_delivery">
                            <span class="price_val">AED <?php echo $pending_payout_aed->total_ticket_amount ? number_format($pending_payout_aed->total_ticket_amount,2) : number_format(0,2);?></span>
                            <p>Pending Payout <i class="fas fa-info-circle"></i></p>
                        </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tabs_sections">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home">My Payouts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu1">My Orders</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane active"><br>
                            <div class="status_report">
                                <div class="search_status">
                                    <label>Payment Reference</label>
                                    <form class="example" action="">
                                        <input type="text" id="payment_reference_history" placeholder="Search.." name="payment_reference_history" onkeyup="payout_histories();">
                                        <button type="button" onclick="payout_histories();"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="down_report">
                                     <a href="<?php echo base_url();?>payout/download_payout_report" class="btn btn-primary">Download Report</a>
                                </div>
                            </div>
                             <div id="payout_histories_block" >
                                <span class="payout_loader" style="text-align:center !important; font-size: 18px; font-weight: bold;"><i class="fa fa-spinner fa-spin" style="color:rgb(0 55 213);"></i>&nbsp;Please Wait ...</span>
                            </div>
                            <!-- <table class="table table-details-all">
                                <thead>
                                    <tr>
                                        <th>Payment Reference</th>
                                        <th>To Account</th>
                                        <th>Amount</th>
                                        <th>Initiated Date</th>
                                        <th>Expected Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($payout_histories)){
                                        foreach ($payout_histories as $payout_history) {
                                            //echo "<pre>";print_r($payout_history);
                                        ?>
                                    <tr>
                                        <td><?php echo $payout_history->payout_no;?></td>
                                        <td><?php if($payout_history->account_number != ""){ echo $payout_history->account_number;}else{ echo "-";}?></td>
                                        <td>
                                        <?php if($payout_history->payout_currency == "GBP"){?>
                                        £<?php } ?><?php if($payout_history->payout_currency == "EUR"){?>
                                        €<?php } ?><?php if($payout_history->payout_currency == "USD"){?>
                                        $<?php } ?><?php if($payout_history->payout_currency == "AED"){?>
                                        د.إ <?php } ?>
                                         <?php echo $payout_history->total_payable;?></td>
                                        <td><?php echo date('j F Y',strtotime($payout_history->paid_date_time))?></td>
                                        <td><?php echo date('j F Y',strtotime($payout_history->paid_date_time. ' + 2 days'))?></td>
                                        <td><span class="color-green"><i class="fas fa-check-circle"></i></span></td>
                                    </tr>
                                <?php }} ?>
                                </tbody>
                            </table> -->
                        </div>
                        <div id="menu1" class="tab-pane fade"><br>
                            <div class="status_report">
                                <div class="search_status">
                                    <label>Payment Or Order Reference</label>
                                    <form class="example" action="">
                                        <input type="text" id="payment_reference_orders" placeholder="Search.." name="payment_reference_orders" onkeyup="payout_orders();">
                                         <button type="button" onclick="payout_orders();"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="status">
                                    <label>Status</label>
                                    <select class="custom-select" id="payout_status" onchange="payout_orders();">
                                    <option value="" selected="selected">Show All</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Pending</option>
                                    <option value="2">Dispute</option>
                                    </select>
                                </div>
                                <div class="down_report">
                                     <a href="<?php echo base_url();?>payout/download_order_report" class="btn btn-primary">Download Report</a>
                                </div>
                            </div>
                            <div id="payout_orders_block" >
                                <span class="payout_loader" style="text-align:center !important; font-size: 18px; font-weight: bold;"><i class="fa fa-spinner fa-spin" style="color:rgb(0 55 213);"></i>&nbsp;Please Wait ...</span>
                            </div>
                            <!-- <table class="table table-details-all">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Payment Reference</th>
                                        <th>Event</th>
                                        <th>Net Amount</th>
                                        <th>Deductions</th>
                                        <th>Payment Initiated Date</th>
                                        <th>Ticket</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                     <?php if(!empty($payout_orders)){
                                        foreach ($payout_orders as $payout_order) {
                                            //echo "<pre>";print_r($payout_history);
                                        ?>
                                         <tr>
                                        <td><?php echo $payout_order->booking_no;?> <br>
                                        <span class="date_time"><?php echo date('j M, Y',strtotime($payout_order->created_at))?></span></td>
                                        <td><?php echo $payout_order->payout_no;?></td>
                                        <td><?php echo $payout_order->match_name;?> <br>
                                        <span class="date_time"><?php echo date('j M, Y',strtotime($payout_order->event_date))?></span></td>
                                        <td>
                                            <?php if($payout_order->payout_status == '2'){ ?>
                                            <span class="color-red">
                                            <?php } ?>
                                            <?php if($payout_order->currency_type == "GBP"){?>
                                        £<?php } ?><?php if($payout_order->currency_type == "EUR"){?>
                                        €<?php } ?><?php if($payout_order->currency_type == "USD"){?>
                                        $<?php } ?><?php if($payout_order->currency_type == "AED"){?>
                                            AED
                                        <?php } ?>

                                         <?php echo number_format($payout_order->ticket_amount,2);?>
                                     <?php if($payout_order->payout_status == '2'){ ?>
                                            </span>
                                            <?php } ?>
                                     </td>
                                        <td>
                                            <?php if($payout_order->payout_status == '2'){ ?>
                                            <span class="color-red">
                                                 <?php if($payout_order->currency_type == "GBP"){?>
                                        £<?php } ?><?php if($payout_order->currency_type == "EUR"){?>
                                        €<?php } ?><?php if($payout_order->currency_type == "USD"){?>
                                        $<?php } ?><?php if($payout_order->currency_type == "AED"){?>
                                            AED
                                        <?php } ?>
                                                <?php echo number_format($payout_order->on_hold,2);?></span>
                                            <?php }else{ ?>
                                                -
                                            <?php } ?>
                                        </td>
                                        <td>
                                             <?php if($payout_order->payout_status == '1'){?>
                                            <?php echo date('j F Y',strtotime($payout_order->paid_date_time))?>
                                        <?php }else if($payout_order->payout_status == '2'){ ?>
                                             Dispute
                                          <?php }else if($payout_order->payout_status == '0'){ ?>
                                           Pending Payment
                                         <?php } ?>

                                            </td>
                                        <td><?php echo $payout_order->quantity;?> * <?php echo $payout_order->seat_category;?></td>
                                        <td>
                                        <?php if($payout_order->payout_status == '1'){?>
                                        <span class="color-green"><i class="fas fa-check-circle"></i></span>
                                        <?php }else if($payout_order->payout_status == '2'){ ?>
                                             <span class="color-red"><i class="fa fa-circle-xmark"></i></span>
                                          <?php }else if($payout_order->payout_status == '0'){ ?>
                                             <span class="color-org"><i class="fas fa-check-circle"></i></span>
                                         <?php } ?>
                                    </td>
                                    </tr>
                                    <?php }} ?>
                                </tbody>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


  
<div class="main page_full_widd">
    <div class="container mt-5">
        <div class="row">
        
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
         <div class="my_modal modal_widd">
      <div class="modal fade" id="myModal1" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="upload_docs">
                    <h3>Add Payout Accounts</h3>
                  </div>
                  <form id="save_bank_accounts" novalidate action="<?php echo base_url(); ?>payout/save_bank_accounts"  method="post">
                  <div class="section_event">
                    <div class="row">
                          <div class="col-md-12">
                          <div class="form-group">
                              <label>Country</label>
                              <div class="input-group">
                               <select name="country" id="country" class="country custom-select" required data-error="#errNm321" >
                              <option value="">Select Country</option>
                              <?php foreach ($countries as $country) {?>
                              <option value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
                            <?php } ?>
                            </select>
                              </div>
                              <div id="errNm1"></div>
                          </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Currency</label>
                              <div class="input-group">
                               <select name="currency" id="currency" class="currency custom-select" required data-error="#errNm1" >
                              <option value="">Select Currency</option>
                              <option value="GBP">GBP</option>
                              <option value="EUR">EUR</option>
                              <option value="USD">USD</option>
                              <option value="AED">AED</option>
                            </select>
                              </div>
                              <div id="errNm1"></div>
                          </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Account Holder Name</label>
                              <div class="input-group">
                                <input type="text" id="account_name" name="account_name" class="form-control valid" placeholder="Enter Account Holder Name" required data-error="#errNm2">
                              </div>
                               <div id="errNm2"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Bank Name </label>
                              <div class="input-group">
                                <input type="text" id="bank" name="bank" class="form-control valid" placeholder="Enter Bank Name" required data-error="#errNm3">
                              </div>
                              <div id="errNm3"></div>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                              <label>Branch Name</label>
                              <div class="input-group">
                                <input type="text" id="branch" name="branch" class="form-control valid" placeholder="Enter Branch Name" required data-error="#errNm5">
                              </div>
                                <div id="errNm5"></div>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                              <label>IBAN/Account Number</label>
                              <div class="input-group">
                                <input type="text" id="account_number" name="account_number" class="form-control valid" placeholder="Enter Account Number" required data-error="#errNm4">
                              </div>
                              <div id="errNm4"></div>
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                              <label>Confirm Account Number</label>
                              <div class="input-group">
                                <input type="text" id="confirm_account_number" name="confirm_account_number" class="form-control valid" placeholder="Confirm Account Number" required data-error="#errNm44">
                              </div>
                              <div id="errNm44"></div>
                            </div>
                        </div> -->
                        
                         <div class="col-md-12">
                            <div class="form-group">
                              <label>Sort Code/SWIFT/BIC/Routing Number</label>
                              <div class="input-group">
                                <input type="text" id="sort_code" name="sort_code" class="form-control valid" placeholder="Sort Code" required data-error="#errNm6">
                              </div>
                               <div id="errNm6"></div>
                            </div>
                        </div>
                        <!--  <div class="col-md-12">
                            <div class="form-group">
                              <label>IBAN </label>
                              <div class="input-group">
                                <input type="text" id="iban" name="iban" class="form-control valid" placeholder="IBAN" required data-error="#errNm7">
                              </div>
                               <div id="errNm7"></div>
                            </div>
                        </div> -->

                        <!--  <div class="col-md-12">
                            <div class="form-group">
                              <label>Swift / BIC</label>
                              <div class="input-group">
                                <input type="text" id="swift" name="swift" class="form-control valid" placeholder="Swift / BIC" required data-error="#errNm8">
                              </div>
                              <div id="errNm8"></div>
                            </div>
                        </div> -->      
                    </div>
                    <div class="btn_save_bttns">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-cancel">Cancel</button>
                        <button id="sub_submit" type="submit" form-id="" class="btn btn-primary ml-3">Add</button> 
                    </div>
                  </div>
                </form>
                </div>
              </div>
            </div>

          </div>
          
        </div>
      </div>
    </div>  
      <input type="hidden" value="1" name="page" id="page_no">
      <input type="hidden" value="1" name="filter" id="filter" value="all">
   <?php $this->load->view(THEME_NAME.'/common/footer');?>

<script type="text/javascript">

    payout_histories();
    payout_orders();

     function payout_histories(){

              var payment_reference = $("#payment_reference_history").val();
              $.ajax({
                        type: 'POST',
                        url: base_url + 'payout/ajax_payout_histories',
                        data: {
                            'payment_reference' : payment_reference
                        },
                        dataType: "json",
                        success: function(data) {
                            if(data.status == 1){
                                $("#payout_histories_block").html(data.response);
                            }
                        }
                    });

        }

        function payout_orders(){

            var payment_reference = $("#payment_reference_orders").val();
            var status            = $("#payout_status").val();
            
              $.ajax({
                        type: 'POST',
                        url: base_url + 'payout/ajax_payout_orders',
                        data: {
                            'payment_reference' : payment_reference,'status' : status
                        },
                        dataType: "json",
                        success: function(data) {
                           if(data.status == 1){
                                $("#payout_orders_block").html(data.response);
                            }
                        }
                    });

        }

    $( document ).ready(function() {

       

         $('.check_type').on('click',function(){
      var flag_type  = $(this).attr('data-type');
      var account_id = $(this).attr('data-account');
      if(flag_type == 'all'){ 
        $('#save_bank_accounts').trigger("reset");

      }
      else{
        $("#currency").val(flag_type.trim());
      }
      
      
      if(account_id != undefined){

         $.ajax({
                        type: 'POST',
                        url: base_url + 'payout/get_bank_accounts',
                        data: {
                            'account_id' : account_id
                        },
                        dataType: "json",
                        success: function(data) {
                            if(data.result.bank_id != ""){
                            $('#country').val(data.result.country);
                                $('#currency').val(data.result.currency);
                                $('#account_name').val(data.result.beneficiary_name);
                                $('#bank').val(data.result.bank_name);
                                $('#account_number').val(data.result.account_number);
                                $('#branch').val(data.result.bank_address);
                                $('#sort_code').val(data.result.sort_code);
                                $('#iban').val(data.result.iban_number);
                                $('#swift').val(data.result.swift_code);
                            }
                        }
                    });

      }else{
        $('#save_bank_accounts').trigger("reset");
        $("#currency").val(flag_type.trim());
      }
    })

    });
</script>