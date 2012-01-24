<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php  $bt = BlockType::getByID($b->getBlockTypeID());
$ci = Loader::helper("concrete/urls");
$btIcon = $ci->getBlockTypeIconURL($bt); 			 

?>

 <div class="ccm-composer-list-item" id="ccm-composer-list-item-<?php echo intval($b->bID)?>"> 
	 <div class="ccm-block-type">  
	 	<?php  if ($displayEditLink) { ?>
		<div class="options"> 
			<a href="javascript:void(0)" onclick="ccm_composerEditBlock(<?php echo $b->getBlockCollectionID()?>, <?php echo $b->getBlockID()?>, '<?php echo $b->getAreaHandle()?>', <?php echo $bt->getBlockTypeInterfaceWidth()?> , <?php echo $bt->getBlockTypeInterfaceHeight()?> )" ><?php echo t('Edit')?></a> 
		</div>  
		<?php  } ?>
		<div class="ccm-block-type-inner">
			<div class="ccm-block-type-inner-icon ccm-scrapbook-item-handle" style="background: url(<?php echo $btIcon?>) no-repeat center left;">
			<img src="<?php echo ASSETS_URL_IMAGES?>/spacer.gif" width="16" height="16" />
			</div>
			<?php 
			if ($b->getBlockName() != '') { 
				$btName = $b->getBlockName();
			} else {
				$btName = $bt->getBlockTypeName();
				
			}
			?>
			<div class="view"><?php echo $btName?></div>
		</div>
		<div class="ccm-composer-block-detail">
		<?php  Loader::element('block_header', array('b' => $b))?>

