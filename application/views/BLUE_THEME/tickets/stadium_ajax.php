

<?php 

	if(strpos($get_mtch->stadium_image, 'svg') !== false){ 

		if($get_mtch->stadium_type == 1){
		?>
	


				<link rel="stylesheet" type="text/css" href="<?php echo base_url(THEME_NAME.'/css/svgmap.css');?>">

			<div class="mapsvg"></div>

			<script src="<?php echo base_url().THEME_NAME;?>/js/jquery.js"></script>
			<script type="text/javascript" src="<?php echo base_url(THEME_NAME.'/js/mapsvg-new.js');?>"></script>
			<script type="text/javascript" src="<?php echo base_url(THEME_NAME.'/js/mousewheel.js');?>"></script>
			<script src="<?php echo base_url().THEME_NAME;?>/js/popper.min.js"></script>
			 <script src="<?php echo base_url().THEME_NAME;?>/js/bootstrap.min.js"></script>
			<script type="text/javascript">
				 jQuery(".mapsvg").mapSvg(<?php echo $get_mtch->stadium_svg ;?>);
			</script>
		<?php }

		else{
			?>
			<style type="text/css">
				 path{
         -moz-transition: all 0.5s ease;  /* FF4+ */
            -o-transition: all 0.5s ease;  /* Opera 10.5+ */
            -webkit-transition: all 0.5s ease;  /* Saf3.2+, Chrome */
            -ms-transition: all 0.5s ease;  /* IE10? */
            transition: all 0.5s ease;
    }
    .disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}

.svg-stadium g text {
    fill: #000 !important;
    font-family: 'Poppins' !important;
    font-weight: 500 !important;
    stroke: transparent;
}

.svg-stadium rect, .svg-stadium g, .svg-stadium path {
    cursor: pointer !important;
}
.block{ stroke:#CCC !important; opacity:0.3 }
.ticket_category_block li  span{ opacity:0.3 }
			</style>

			    <img src="<?php echo $get_mtch->stadium_image;?>" id="map_svg">

			 <script src="<?php echo base_url().THEME_NAME;?>/js/jquery.js"></script>
			 <script src="<?php echo base_url().THEME_NAME;?>/js/init-svg.js?v=1"></script>
			 <script type="text/javascript">
			
				   $('#map_svg').inlineSvg();
				var stadium_json = <?php echo $get_mtch->stadium_svg ;?>;
				   
				       function load_category(json_data){
				        $.each(json_data, function (indx, itm) {
				            $('[data-section="'+itm.full_block_name+'"] .block').css('fill',itm.fill);
				        });
				    }

				    setTimeout(function () {
				        load_category(stadium_json);
				    },1000);
    
			</script>

			<?php
		}
		?>
	<?php } else{  ?>
	<img src="<?php echo $get_mtch->stadium_image;?>" />
	<?php } ?>