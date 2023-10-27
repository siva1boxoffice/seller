 <!-- End Header -->
    <script type="text/javascript">var base_url = "<?php echo base_url();?>";</script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/app.js"></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/functions.js"></script>
  <!--   <script src="<?php echo base_url().THEME_NAME;?>/js/jquery.js"></script> -->
    <!-- <script src="<?php echo base_url().THEME_NAME;?>/js/validate_v1/jquery.js"></script> -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
     <script src="<?php echo base_url().THEME_NAME;?>/js/validate_v1/jquery.validate.js"></script>
<!--     <script src="<?php echo base_url().THEME_NAME;?>/js/validate/jquery.validate.js?ver=2.4.8" async></script> -->
    
    <script src="<?php echo base_url().THEME_NAME;?>/js/popper.min.js"></script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <!--  <script src="<?php echo base_url().THEME_NAME;?>/js/jquery-ui.min.js"></script> -->
     <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
     <script src="<?php echo base_url().THEME_NAME;?>/js/daterangepicker.js"></script>


     <!--   <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
  
    <!-- mousewheel plugin --> 
      <script src="<?php echo base_url().THEME_NAME;?>/js/jquery.mousewheel.min.js"></script> 
      <!-- custom scrollbars plugin --> 
      <script src="<?php echo base_url().THEME_NAME;?>/js/jquery.mCustomScrollbar.min.js"></script> 
      <script src="<?php echo base_url().THEME_NAME;?>/js/ticket.js?v=2.1"></script>
      <script src="<?php echo base_url().THEME_NAME;?>/js/validate/custom.js?ver=2.6.2" async></script>

      <!--  <script src="<?php echo base_url().THEME_NAME;?>/js/custom.js?ver=2.4.8" async></script> -->
    <script type="text/javascript">
     // $('#clone-listing-modal').modal();
      $(".clone-listing-close").on("click",function(){
        $("#clone-listing-modal").modal('hide');
      });

      $(".clone-listing-btn").on("click",function(){
 
        var clone    = $("#clone-listing-table-tr").clone();

        clone.find('input').val('');
        clone.find('select').val('');
        //$(".clone-listing-table").css("background" ,"RED");
        $(".clone-listing-table tr:last").after(clone);
      });
      
     $( document ).ready(function() {
        $("#content_1").mCustomScrollbar({
          scrollButtons:{
            enable:true
          }
        });
      });
    </script>
    <script src="<?php echo base_url().THEME_NAME;?>/js/select2.min.js"></script>
     

  </body>
</html>