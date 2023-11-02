<table class="table table-details-all">
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
                                        <td><?php echo $payout_order->quantity;?> × <?php echo $payout_order->seat_category;?></td>
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
                            </table>