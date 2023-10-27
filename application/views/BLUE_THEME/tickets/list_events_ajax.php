				<?php if($page == 1){ ?>
        <tr>
                  <th>Select</th>
                  <th>Event Name</th>
                  <th>Event Date Time (Local)</th>
                  <th>Tournament</th>
                  <th>Venue Name</th>
                  <th>Price Range</th>
                  <th>Tickets available</th>
        </tr>
      <?php } ?>
                <?php 
                if(!empty($list_events[0])){
                foreach($list_events as $list_event){?>
                <tr>
                  <td data-label="Select"><input class="tdcheckbox matchcheck" data-ticket-id="<?php echo $list_event->m_id;?>" type="checkbox" name="matchcheck[]" value="<?php echo $list_event->m_id;?>">
                  </td>
                  <td data-label="Event Name"><b><?php echo $list_event->match_name;?></b></td>
                  <td data-label="Event Date Time (Local)"><?php echo date('l', strtotime($list_event->match_date));?> <?php echo date('d F Y', strtotime($list_event->match_date));?> - <?php echo date('H:i A', strtotime($list_event->match_date));?></td>
                  <td data-label="Tournament"><?php echo $list_event->tournament_name;?></td>
                  <td data-label="Venue Name"><?php echo $list_event->stadium_name;?> </td>
                  <td data-label="Price Range"><?php echo $list_event->min_fare ?$list_event->min_fare : '';?> - <?php echo $list_event->max_fare ?$list_event->max_fare : '';?> <?php echo $list_event->ticket_currency ?$list_event->ticket_currency : '';?><!-- 90.36 - 174.27 GBP --></td>
                  <td data-label="Tickets Available"><?php echo $list_event->tickets_available ?$list_event->tickets_available : 0;?></td>
                </tr>
                <?php }
            	}else{ ?>
                	<tr><td colspan="7"><h5>No Events Available For Your Search.</h5></td></tr>
                	<?php } ?>