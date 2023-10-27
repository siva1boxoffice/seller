  <?php $this->load->view(THEME_NAME.'/common/header');?>
      <div class="main">
      <div class="container">


       
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
                          <input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="" placeholder="Search by Event, Venue, City, Section or Listing ID" onkeyup="get_oe_ticket_search(this.value,'');">
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 text-right">
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <select id="publish_unpublish" onchange="get_oe_ticket_search('',this.value);" class="custom-select rt-select">
                                      <option value="publish">Published</option>
                                      <option value="unpublish">Unpublished</option>
                                      
                                    </select>
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url();?>tickets/index/create_ticket" class="btn theme-btn-1 text-uppercase"><!-- <img src="<?php echo base_url().THEME_NAME;?>/images/plus.svg"> --><i class="fa fa-plus"></i> Sell Tickets</a>
                      </div>
                    </div>
                    
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

   
get_oe_ticket_search('','publish');
   // load_tickets('',0);

  });
 </script>
 