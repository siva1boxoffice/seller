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
              <!--   <div class="col-lg-4">
                  <div class="search">
                      <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                        </div>
                        <input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Event, Venue, City, Section or Listing ID">
                      </div>
                  </div>
                </div>
                <div class="col-lg-4 text-right">
                  <a href="" class="btn theme-btn_new text-uppercase">Show active listing <img src="<?php echo base_url().THEME_NAME;?>/images/down-arrow.svg"></a>
                  <a href="" class="btn theme-btn-1 text-uppercase"><img src="<?php echo base_url().THEME_NAME;?>/images/plus.svg"> sell tickets</a>
                </div> -->
            </div>
          </div>
          </div>
        </div>

         <section class="listing-orders order_s" id="list_body">
          <div style="background: #FAFBFF;     font-size: 15px; padding: 10px; text-align: center;
    font-weight: 700;
    border: 1px solid #dee2e6;color: #323A70;"><i class="fa fa-spinner fa-spin" style="color:#323A70;"></i>&nbsp;Please Wait ...</div>
        </section>
    </div>

  
 
<?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">
     $(document).ready(function() {
    load_tickets_details('<?php echo $match_id;?>',0);
});
</script>