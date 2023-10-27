  <tr>
                  <th>Ip</th>
                  <th>Click Ref</th>
                  <th>Reference Url</th>
                  <th style="width: 150px;">Checkout Url</th>
                  <th>Match Name</th>
                  <th>Quantity</th>
                  <th>Date</th>
                 
                          </tr>
 <?php 
                if ($results) {
                foreach ($results as $row) {
                  
                  
                ?>
                <tr style="cursor: pointer;" class=" fs-15">
           
                  <td><?php echo $row->ip_address;?></td>
                  <td><?php  if(@$row->click_ref) {?> 

                      <a class="title_hover"  title="<?php echo $row->click_ref;?>" >Click Ref </a> <span class=""><i onclick="copyToClipboard('<?php echo $row->click_ref;?>')" class="fa fa-copy"></i></span>
                      <?php } ?></td>

                      <td><?php  if(@$row->refernce_url) {?> 

                      <a class="title_hover"  title="<?php echo $row->refernce_url;?>" > Reference Url </a> <span class=""><i onclick="copyToClipboard('<?php echo $row->refernce_url;?>')" class="fa fa-copy"></i></span>
                      <?php } ?></td>

                      <td><?php  if(@$row->checkout_url) {?> 

                      <a class="title_hover"  title="<?php echo $row->checkout_url;?>" > Checkout Url  </a> <span class=""><i onclick="copyToClipboard('<?php echo $row->checkout_url;?>')" class="fa fa-copy"></i></span>
                      <?php } ?></td>

                  
                  <td><?php echo $row->match_name;?><br><?php echo $row->match_date;?></td>
                  <td><?php echo $row->quantity;?></td>
                  <td><?php echo $row->created_at;?></td>

                  </tr>
                    
                  <?php
                  }
                  }
                  else{ ?>
                  <tr><td colspan="9"><h5 class="dash-welcome-head fs-24">No Recent Logs.</h5></td></tr>
                  <?php }
                  ?>