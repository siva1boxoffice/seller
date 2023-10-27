 <?php 
                if ($results) {
                foreach ($results as $row) {
                  
                
                ?>
                <tr style="cursor: pointer;" class=" fs-15">
           
                  <td>
                    <h5 class="fs-15"><?php echo $row->request_type ;?></h5></td>
                   <?php if($row->request_filename) {  $req = explode("/",$row->request_filename) ;?>
                    <td>
                    <h5 class="fs-15">
                      <a  href="<?php echo base_url("logs/download/".end($req)) ;?>" ><?php echo end($req) ?></a></h5></td>
                  <?php }?>
                      <?php if($row->response_filename) {  $res = explode("/",$row->response_filename);?>
                    <td>
                    <h5 class="fs-15"> <a  href="<?php echo base_url("logs/download/".end($res)) ;?>"    ><?php  echo end($res);?></a></h5></td>
                    <?php } ?>
                    <td>
                    <h5 class="fs-15"><?php echo $row->created_at;?></h5></td>
                 </tr>

                    
                  <?php
                  }
                  }
                  else{ ?>
                  <tr><td colspan="9"><h5 class="dash-welcome-head fs-24">No Recent Logs.</h5></td></tr>
                  <?php }
                  ?>