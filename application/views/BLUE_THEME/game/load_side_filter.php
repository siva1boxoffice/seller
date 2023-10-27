 
            <div class="order_filt">
            <!--   <h2>Order Filters</h2> -->

              <img class="list_head  menu_click_expand" src="<?php echo base_url().THEME_NAME;?>/images/menus.svg">
              <?php if($orders['pending_all'] > 0){?>
              <div class="order_filter_options" onclick="load_data('pending',10,1);">

                <div class="tooltip_new">

                <div class="order_options">
                   <div class="tooltip_order_lists_left">
                      <img src="<?php echo base_url().THEME_NAME;?>/images/watch_later.svg">
                      <h4>Pending Orders</h4>
                    </div>
                    <div class="tooltip_order_lists_right">
                     <?php if(array_sum(array_column($orders['pending_gbp'],'quantity')) > 0){ ?>
                     <p>£  <?php echo format_price(array_sum(array_column($orders['pending_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['pending_gbp'],'quantity'));?>)</p>
                    <?php } ?>
                    <?php if(array_sum(array_column($orders['pending_eur'],'quantity')) > 0){ ?>
                      <p>€ <?php echo format_price(array_sum(array_column($orders['pending_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['pending_eur'],'quantity'));?>)</p>
                     <?php } ?>
                     <?php if(array_sum(array_column($orders['pending_usd'],'quantity')) > 0){ ?>
                      <p>$ <?php echo format_price(array_sum(array_column($orders['pending_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['pending_usd'],'quantity'));?>)</p>
                      <?php } ?>
                       <?php if(array_sum(array_column($orders['pending_aed'],'quantity')) > 0){ ?>
                      <p>.إ <?php echo format_price(array_sum(array_column($orders['pending_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['pending_aed'],'quantity'));?>)</p>
                      <?php } ?>
                    </div>
                </div>
                <span class="tooltiptext">Pending Orders</span>
                </div>
                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['pending_all'];?></span>
                </div>
              </div>
            <?php } ?>

            

               <?php if($orders['confirmed_all'] > 0){?>
                <div class="order_filter_options order_filter_active" onclick="load_data('confirmed',10,1);">
                  <div class="tooltip_new">
                    <div class="order_options">
                          <div class="tooltip_order_lists_left">
                            <img src="<?php echo base_url().THEME_NAME;?>/images/cartv1.svg" >
                            <h4>Confirmed Orders</h4><!-- <h4>Transfer Tickets</h4> -->
                          </div>
                          <div class="tooltip_order_lists_right">
                            <?php if(array_sum(array_column($orders['confirmed_gbp'],'quantity')) > 0){ ?>
                            <p>£  <?php echo format_price(array_sum(array_column($orders['confirmed_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['confirmed_gbp'],'quantity'));?>)</p>
                          <?php } ?>
                           <?php if(array_sum(array_column($orders['confirmed_eur'],'quantity')) > 0){ ?>
                            <p>€ <?php echo format_price(array_sum(array_column($orders['confirmed_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['confirmed_eur'],'quantity'));?>)</p>
                            <?php } ?>
                            <?php if(array_sum(array_column($orders['confirmed_usd'],'quantity')) > 0){ ?>
                            <p>$ <?php echo format_price(array_sum(array_column($orders['confirmed_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['confirmed_usd'],'quantity'));?>)</p>
                            <?php } ?>
                            <?php if(array_sum(array_column($orders['confirmed_aed'],'quantity')) > 0){ ?>
                            <p>.إ <?php echo format_price(array_sum(array_column($orders['confirmed_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['confirmed_aed'],'quantity'));?>)</p>
                            <?php } ?>
                          </div>
                    </div>
                    <span class="tooltiptext">Confirmed Orders</span>
                  </div>
                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['confirmed_all'];?></span>
                </div>
              </div>
            <?php } ?>
           
              <?php if($orders['getpaid_all'] > 0){?>
              <div class="order_filter_options" onclick="load_data('getpaid',10,1);">

                <div class="tooltip_new">
                  <div class="order_options">
                      <div class="tooltip_order_lists_left">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/paid.svg">
                        <h4>Get Paid</h4>
                      </div>
                      <div class="tooltip_order_lists_right">
                        <?php if(array_sum(array_column($orders['getpaid_gbp'],'quantity')) > 0){ ?>
                        <p>£  <?php echo format_price(array_sum(array_column($orders['getpaid_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['getpaid_gbp'],'quantity'));?>)</p>
                      <?php } ?>
                      <?php if(array_sum(array_column($orders['getpaid_eur'],'quantity')) > 0){ ?>
                        <p>€ <?php echo format_price(array_sum(array_column($orders['getpaid_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['getpaid_eur'],'quantity'));?>)</p>
                         <?php } ?>
                         <?php if(array_sum(array_column($orders['getpaid_usd'],'quantity')) > 0){ ?>
                        <p>$ <?php echo format_price(array_sum(array_column($orders['getpaid_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['getpaid_usd'],'quantity'));?>)</p>
                        <?php } ?>
                         <?php if(array_sum(array_column($orders['getpaid_aed'],'quantity')) > 0){ ?>
                        <p>.إ <?php echo format_price(array_sum(array_column($orders['getpaid_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['getpaid_aed'],'quantity'));?>)</p>
                        <?php } ?>
                      </div>
                  </div>
                  <span class="tooltiptext">Get Paid</span>
                </div>

                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['getpaid_all'];?></span>
                </div>
              </div>
            <?php } ?>
            <?php if($orders['completed_all'] > 0){?>
              <div class="order_filter_options" onclick="load_data('completed',10,1);">
                <div class="tooltip_new">
                  <div class="order_options">
                      <div class="tooltip_order_lists_left">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/check_circle.svg">
                        <h4>Complete</h4>
                      </div>
                      <div class="tooltip_order_lists_right">
                        <?php if(array_sum(array_column($orders['completed_gbp'],'quantity')) > 0){ ?>
                        <p>£  <?php echo format_price(array_sum(array_column($orders['completed_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['completed_gbp'],'quantity'));?>)</p>
                      <?php } ?>
                       <?php if(array_sum(array_column($orders['completed_eur'],'quantity')) > 0){ ?>
                        <p>€ <?php echo format_price(array_sum(array_column($orders['completed_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['completed_eur'],'quantity'));?>)</p>
                        <?php } ?>
                        <?php if(array_sum(array_column($orders['completed_usd'],'quantity')) > 0){ ?>
                        <p>$ <?php echo format_price(array_sum(array_column($orders['completed_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['completed_usd'],'quantity'));?>)</p>
                        <?php } ?>
                        <?php if(array_sum(array_column($orders['completed_aed'],'quantity')) > 0){ ?>
                        <p>.إ <?php echo format_price(array_sum(array_column($orders['completed_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['completed_aed'],'quantity'));?>)</p>
                        <?php } ?>
                      </div>
                  </div>
                  <span class="tooltiptext">Complete</span>
                </div>

                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['completed_all'];?></span>
                </div>
              </div>
            <?php } ?>
            <?php if($orders['issue_all'] > 0){?>
              <div class="order_filter_options" onclick="load_data('issue',10,1);">
                <div class="tooltip_new">
                  <div class="order_options">
                      <div class="tooltip_order_lists_left">
                        <img src="<?php echo base_url().THEME_NAME;?>/images/issue.svg">
                        <h4>Issue</h4>
                      </div>
                      <div class="tooltip_order_lists_right">
                        <?php if(array_sum(array_column($orders['issue_gbp'],'quantity')) > 0){ ?>
                        <p>£  <?php echo format_price(array_sum(array_column($orders['issue_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['issue_gbp'],'quantity'));?>)</p>
                      <?php } ?>
                      <?php if(array_sum(array_column($orders['issue_eur'],'quantity')) > 0){ ?>
                        <p>€ <?php echo format_price(array_sum(array_column($orders['issue_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['issue_eur'],'quantity'));?>)</p>
                        <?php } ?>
                        <?php if(array_sum(array_column($orders['issue_usd'],'quantity')) > 0){ ?>
                        <p>$ <?php echo format_price(array_sum(array_column($orders['issue_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['issue_usd'],'quantity'));?>)</p>
                        <?php } ?>
                         <?php if(array_sum(array_column($orders['issue_aed'],'quantity')) > 0){ ?>
                        <p>.إ <?php echo format_price(array_sum(array_column($orders['issue_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['issue_aed'],'quantity'));?>)</p>
                        <?php } ?>
                      </div>
                  </div>
                  <span class="tooltiptext">Issue</span>
                </div>

                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['issue_all'];?></span>
                </div>
              </div>
            <?php } ?>

              <?php if($orders['cancelled_all'] > 0){?>
              <div class="order_filter_options" onclick="load_data('cancelled',10,1);">
                  <div class="tooltip_new">
                    <div class="order_options">
                        <div class="tooltip_order_lists_right">
                          <img src="<?php echo base_url().THEME_NAME;?>/images/highlight_off.svg">
                          <h4>Cancelled</h4>
                        </div>
                        <div class="tooltip_order_lists_right">
                          <?php if(array_sum(array_column($orders['cancelled_gbp'],'quantity')) > 0){ ?>
                          <p>£  <?php echo format_price(array_sum(array_column($orders['cancelled_gbp'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['cancelled_gbp'],'quantity'));?>)</p>
                        <?php } ?>
                          <?php if(array_sum(array_column($orders['cancelled_eur'],'quantity')) > 0){ ?>
                          <p>€ <?php echo format_price(array_sum(array_column($orders['cancelled_eur'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['cancelled_eur'],'quantity'));?>)</p>
                          <?php } ?>
                          <?php if(array_sum(array_column($orders['cancelled_usd'],'quantity')) > 0){ ?>
                          <p>$ <?php echo format_price(array_sum(array_column($orders['cancelled_usd'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['cancelled_usd'],'quantity'));?>)</p>
                          <?php } ?>
                          <?php if(array_sum(array_column($orders['cancelled_aed'],'quantity')) > 0){ ?>
                          <p>.إ <?php echo format_price(array_sum(array_column($orders['cancelled_aed'],'total_amount')));?> / (<?php echo array_sum(array_column($orders['cancelled_aed'],'quantity'));?>)</p>
                          <?php } ?>
                        </div>
                    </div>
                    <span class="tooltiptext">Cancelled</span>
                  </div>

                <div class="ticket_val">
                  <span class="tick_price"><?php echo $orders['cancelled_all'];?></span>
                </div>
              </div>
               <?php } ?>

           

            

            </div>

            <div class="slide_open slide_filter">
              <a href="javascript:void(0)" class="slide_collpase">
                <img class="slide_right_arrow" src="<?php echo base_url().THEME_NAME;?>/images/right-arrow.svg">
                <img class="slide_left_arrow" src="<?php echo base_url().THEME_NAME;?>/images/left-arrow.svg"></a>

            </div>

         