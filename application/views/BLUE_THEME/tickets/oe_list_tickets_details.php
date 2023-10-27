  <?php $this->load->view(THEME_NAME.'/common/header');?>
      <div class="main">
      <div class="container">


        <div class="row">
          <div class="col-lg-12">
            <div class="mt-5">
              <div class="row">
                <div class="col-lg-4">
                  <div class="publish">
                    <input class="tdcheckbox checkall" type="checkbox" name="checkall">
                    <a href="javascript:void(0);" onclick="doaction('unpublish','tickets');">Unpublish</a>
                    <a href="javascript:void(0);" onclick="doaction('publish','tickets');">Publish</a>
                    <a href="javascript:void(0);" onclick="deleteaction('delete','tickets');">Delete</a>
                  </div>
                </div>
             
            </div>
          </div>
          </div>
        </div>

        <section class="listing-orders order_s" id="list_body">
          
        </section>
    </div>

  
 
<?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">
     $(document).ready(function() {
    oe_load_tickets_details('<?php echo $match_id;?>',0);
});
</script>