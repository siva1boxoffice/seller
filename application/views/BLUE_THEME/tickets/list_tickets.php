  <?php $this->load->view(THEME_NAME.'/common/header');?>
      <div class="main">
      <div class="container">


        <!-- <div class="row">
          <div class="col-lg-12">
            <div class="mt-5">
              <div class="row">
                <div class="col-lg-4">
                  <div class="publish">
                    <input class="tdcheckbox " type="checkbox" name="">
                    <a href="">Unpublish</a>
                    <a href="">Publish</a>
                    <a href="">Delete</a>
                  </div>
                </div>
                <div class="col-lg-4">
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
                </div>
            </div>
          </div>
          </div>
        </div> -->
         <div class="row">
          <div class="col-lg-12">
            <div class="mt-5">
              <div class="search_options">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="publish">
                      <input class="tdcheckbox checkall" type="checkbox" name="checkall">
                      <a href="javascript:void(0);" onclick="doaction('unpublish','match');">Unpublish</a>
                      <a href="javascript:void(0);" onclick="doaction('publish','match');">Publish</a>
                      <a href="javascript:void(0);" onclick="deleteaction('delete','match');">Delete</a>
                    </div>
                  </div>
                  <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                    <div class="search">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-lg"><img src="<?php echo base_url().THEME_NAME;?>/images/search.svg"></span>
                          </div>
                          <input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Event, Venue, City, Section or Listing ID" onkeyup="get_ticket_search(this.value,'');">
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 text-right">
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <select id="publish_unpublish" onchange="get_ticket_search('',this.value);" class="custom-select rt-select">
                                     <!--  <option value="upcoming">Upcoming</option>
                                      <option value="expired">Expired</option> -->
                                      <option value="publish">Published</option>
                                      <option value="unpublish">Unpublished</option>
                                    <!--   <option value="deleted">Deleted</option> -->
                                      
                                    </select>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url();?>tickets/index/create_ticket" class="btn theme-btn-1 text-uppercase"><!-- <img src="<?php echo base_url().THEME_NAME;?>/images/plus.svg"> --><i class="fa fa-plus"></i> Sell Tickets</a>
                      </div>
                    </div>

                    
                   <!--  <a href="" class="btn theme-btn_new text-uppercase">Show active listing <img src="<?php echo base_url().THEME_NAME;?>/images/down-arrow.svg"></a> -->
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section class="listing-orders">
            <table class="table" id="list_body">
             
            </table>

        </section>
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
            <div class="modal-body">
              <div class="row">
                <div class="team_name">
                <h3 style="text-align: center;"><i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...</h3>
              </div>
            </div>
             
            </div>
          </div>
        </div>
      </div>
    </div>

<?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">

   
      $(window).load(function(){
        $("#content_1").mCustomScrollbar({
          scrollButtons:{
            enable:true
          }
        });
      });
        
</script>

 <script>

   
  $(document).ready(function() {


    bulmaCalendar.attach("#event-start", {
      startDate: new Date('<?php echo date('m/d/Y'); ?>'),
      color: themeColors.primary,
      lang: "en",
      showHeader: false,
      showButtons: false,
      showFooter: false
    });

    bulmaCalendar.attach("#event-end", {
      startDate: new Date('<?php echo date('m/d/Y', strtotime(date("m/d/Y") . ' +1 day')) ?>'),
      color: themeColors.primary,
      lang: "en",
      showHeader: false,
      showButtons: false,
      showFooter: false
    });

   
get_ticket_search('','publish');
   // load_tickets('',0);

  });
 </script>
 