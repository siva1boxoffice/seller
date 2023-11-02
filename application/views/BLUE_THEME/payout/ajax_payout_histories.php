<table class="table table-details-all">
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
                            </table>