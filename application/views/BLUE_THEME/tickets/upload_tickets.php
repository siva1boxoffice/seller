  <?php $this->load->view(THEME_NAME.'/common/header');?>
  <style type="text/css">
  	input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    width: 100%;
    background-color:#039871;
    border-radius: 5px;
    color: #fff;
}
  </style>
    <div class="seller">
      <div class="container seller_container">
         <form id="bulk_upload_tickets" novalidate action="<?php echo base_url(); ?>tickets/bulk_upload_tickets" enctype="multipart/form-data" class="validate_form_bulk" method="post">
        <div class="row">
          <div class="col-lg-12" >
            <div class="search_btn text-center" style="margin:auto;max-width:600px">
              <h3>Please upload Excel file to Create Bulk Tickets <a href="#" data-toggle="tooltip" title="Tooltip"><img src="<?php echo base_url().THEME_NAME;?>/images/tooltip.svg"></a></h3>
				<label for="file-upload" class="custom-file-upload">
				<i class="fa fa-cloud-upload"></i> <span id="filename">Upload Excel File</span>
				</label>
              <input type="file" id="file-upload" class="form-control uploadFile" name="uploadFile" required>
              <!-- <p>
              <span><a download href="<?php echo ADMIN_UPLOAD;?>/uploads/bulktickets/SampleTicketsForWC.xlsx">Click here to download sample Ticket file</a></span>
          </p> -->
                    <span id="errNm0"></span>
              
          </div>
          </div>
        </div>
   		<div class="row">
                <div class="col-md-12">
                  <div class="upcoming-match-btn-view-all">
                    <button name="submit" type="submit" value="submit" style="cursor: pointer;" class="onebox-btn">Upload Tickets</button>
                  </div>
                </div>
              </div>
      </form>
      </div>
    </div>


<?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">
	$('#file-upload').change(function(e){
  var filename = e.target.files[0].name;
  $('#filename').text(filename);
  console.log(filename);
});
</script>