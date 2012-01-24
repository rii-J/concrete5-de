<?php  defined('C5_EXECUTE') or die("Access Denied.");

$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
	<div class="ccm-edit-mode-disabled-item" style="height: 400px">
		<div style="padding: 80px 0px 0px 0px"><?php echo t('Google Map disabled in edit mode.')?></div>
	</div>
<?php  } else { ?>
	<style type="text/css">
	.googleMapCanvas{ width:100%; border:0px none; height: 400px;}
	</style>
	<script type="text/javascript"> 
	function googleMapInit<?php echo $bID?>() { 
		try{
			var latlng = new google.maps.LatLng(<?php echo $latitude?>, <?php echo $longitude?>);
		    var mapOptions = {
		      zoom: <?php echo $zoom?>,
		      center: latlng,
		      mapTypeId: google.maps.MapTypeId.ROADMAP,
		      streetViewControl: false,
		      mapTypeControl: false
			};
		    var map = new google.maps.Map(document.getElementById('googleMapCanvas<?php echo $bID?>'), mapOptions);
		    var marker = new google.maps.Marker({
		        position: latlng, 
		        map: map
		    });
		}catch(e){alert(e.message)} 
	}
	</script>
	
	<?php  if( strlen($title)>0){ ?><h3><?php echo $title?></h3><?php  } ?>
	<div id="googleMapCanvas<?php echo $bID?>" class="googleMapCanvas"></div>
	<script type="text/javascript">$(function() {
		googleMapInit<?php echo $bID?>();
	});</script>
	
<?php  } ?>