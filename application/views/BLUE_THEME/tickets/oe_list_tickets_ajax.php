 <?php 
 if(!empty($listings)){
 foreach ($listings as $list_ticket) {
                $tickets = $list_ticket->tickets;
                $min_price = min(array_column($tickets, 'price'));
                $max_price = max(array_column($tickets, 'price'));
                $total_qty = array_sum(array_column($tickets,'quantity'));
                $total_sold = array_sum(array_column($tickets,'sold'));
                $teams = explode('vs',$list_ticket->match_name);
                if($teams[1] == ''){
                $teams = explode('Vs',$list_ticket->match_name);
                }
                $final_status = array_sum(array_column($tickets,'status'));


                $future = strtotime($list_ticket->match_date);
                $now = time();

                $d1 = new DateTime(date('Y-m-d h:i:s'));
                $d2 = new DateTime($list_ticket->match_date);
                $interval = $d1->diff($d2);
                $diffInSeconds = $interval->s; //45
                $diffInMinutes = $interval->i; //23
                $diffInHours   = $interval->h; //8
                $diffInDays    = $interval->d; //21
                $diffInMonths  = $interval->m; //4
                $diffInYears   = $interval->y; //1
                //echo $diffInYears.'-'.$diffInMonths.'-'.$diffInDays.'-'.$diffInHours.'-'.$diffInMinutes.'-'.$diffInSeconds;

                if ($diffInYears >= 1) {
                $event_left =  $diffInYears . ' Year Left';
                }
                else if ($diffInYears < 1 && $diffInMonths >= 1) {
                $event_left =  $diffInMonths . ' Months Left';
                }
                else if ($diffInMonths < 1 && $diffInDays >= 1) {
                $event_left =  $diffInDays . ' Days Left';
                }
                else if ($diffInDays < 1 && $diffInHours >= 1) {
                $event_left =  $diffInHours . ' Hours Left';
                }
                else if ($diffInHours < 1 && $diffInMinutes >= 1) {
                $event_left =  $diffInMinutes . ' minutes Left';
                }
                else if ($diffInMinutes >= 1 && $diffInSeconds >= 1) {
                $event_left =  $diffInMinutes . ' minutes Left';
                }
                else if ($diffInSeconds >= 1) {
                $event_left =  $diffInMinutes . ' Seconds Left';
                }
                else{
                $event_left = 'Expired';
                } 

                $time_now = time(); // or your date as well
                $match_date_ = strtotime($list_ticket->match_date);
                $fillment_daysc = $match_date_ - $time_now;
                $fillment_days = round($fillment_daysc / (60 * 60 * 24));
                $full_fillment_day = $fillment_days - FULLFILLMENT_DAY;
                if($full_fillment_day < 0){
                  $full_fillment_day = "Expired";
                  //$event_left = 'Expired';
                }
                if($total_sold <= 0){
                  $full_fillment_day = 0;
                }
               /* echo date('Y-m-d', strtotime($date. ' + 10 days')); 
                $full_fillment_date = $list_ticket->match_date*/
            ?>
        
<tr id="<?php echo 'diffInYears = '.$diffInYears;?>" id="<?php echo 'diffInMonths = '.$diffInMonths;?>" id="<?php echo 'diffInDays = '.$diffInDays;?>">
<td><input class="tdcheckbox singlecheck"  data-ticket-id="<?php echo $list_ticket->match_id; ?>" type="checkbox" name="singlecheck[]" value="<?php echo $list_ticket->match_id; ?>"></td>
<!-- <td><a href="javascript:void(0);" data-ticket-id="<?php echo $list_ticket->s_no; ?>" class="open_details"><img src="<?php echo base_url().THEME_NAME;?>/images/Vector.svg"></a></td>
 --><td class="entire_row" style="cursor: pointer;" data-href="<?php echo base_url();?>tickets/index/listing_details/<?php echo $list_ticket->m_id; ?>"><?php echo $list_ticket->match_name; ?> <!-- - <?php echo date('l', strtotime($list_ticket->match_date));?> <span>[<?php echo $list_ticket->ticket_group_id; ?>]</span> --><br><span><?php echo $list_ticket->country_name . ', ' . $list_ticket->city_name; ?></span><br><?php echo date('D d M Y', strtotime($list_ticket->match_date));?> <?php echo $list_ticket->match_time; ?> </td>
<td class="entire_row" style="cursor: pointer;" data-href="<?php echo base_url();?>tickets/index/listing_details/<?php echo $list_ticket->m_id; ?>"><span>Available Tickets:&nbsp;</span><?php echo $total_qty;?></td>
<td class="entire_row" style="cursor: pointer;" data-href="<?php echo base_url();?>tickets/index/listing_details/<?php echo $list_ticket->m_id; ?>"><span>Tickets sold:&nbsp;</span><?php echo $list_ticket->sold_qty;?></td>
<td class="entire_row" style="cursor: pointer;" data-href="<?php echo base_url();?>tickets/index/listing_details/<?php echo $list_ticket->m_id; ?>"><span>Pending Fulfillment:&nbsp;</span> <?php echo $list_ticket->pending_fullfillment;?></td>
<td class="entire_row" style="cursor: pointer;" data-href="<?php echo base_url();?>tickets/index/listing_details/<?php echo $list_ticket->m_id; ?>"><?php echo $event_left;?></td>
<td><a href="<?php echo base_url();?>tickets/index/oe_listing_details/<?php echo $list_ticket->m_id; ?>" class=""><img src="<?php echo base_url().THEME_NAME;?>/images/zoom.svg"></a></td>
</tr>
<?php } }else{ ?>
  <tr colspan="9"><td><h3>No tickets listed</h3></td></tr>
<?php } ?>
<script type="text/javascript">

 
  $(document).ready(function() {

     
      $('.entire_row').on('click',function(){
     
        window.location.href= $(this).attr("data-href");

    })

    $('.open_details').on('click',function(){
      var ticket_id = $(this).attr('data-ticket-id');
      $('#myLargeModalLabel').modal();  


    })

  })
</script>