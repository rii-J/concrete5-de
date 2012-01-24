<?php 
defined('C5_EXECUTE') or die("Access Denied.");
?>

<form method="post" action="<?php echo $this->action('save')?>">

<h1><span><?php echo t('Composer Settings')?></span></h1>

<?php  
$form = Loader::helper('form');
$html = Loader::helper('html');
$ih = Loader::helper('concrete/interface');
$cap = Loader::helper('concrete/dashboard');
$ctArray = CollectionType::getList();
if ($cap->canAccessComposer()) { ?>

	<div class="ccm-dashboard-inner">
	<?php echo $form->hidden('ctID', $ct->getCollectionTypeID()); ?>	
	<table cellspacing="1" cellpadding="0" border="0" class="entry-form">
	<tr>
		<td colspan="3" class="header"><?php echo t('Included in Composer?')?></td>
	</tr>
	<tr>
		<td colspan="3">
			<?php echo $form->checkbox('ctIncludeInComposer', 1, $ct->isCollectionTypeIncludedInComposer() == 1)?> <?php echo $form->label('ctIncludeInComposer', t('Yes, include this page type in Composer.'))?>
		</td>
	</tr>
	<tr class="row-composer">
		<td colspan="3" class="subheader"><?php echo t('Composer Publishing Settings')?></td>
	</tr>
	<tr class="row-composer">
		<td colspan="3">
			<div>
			<?php echo $form->radio('ctComposerPublishPageMethod', 'CHOOSE', $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE' || $ct->getCollectionTypeComposerPublishMethod == null)?>
			<?php echo $form->label('ctComposerPublishPageMethod1', t('Choose from all pages when publishing.'))?>
			
			</div>
			
			<div>
			<?php echo $form->radio('ctComposerPublishPageMethod', 'PAGE_TYPE', $ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE')?>
			<?php echo $form->label('ctComposerPublishPageMethod2', t('Choose from pages of a certain type when publishing.'))?>
			
			</div>

			<div style="display: none; padding: 10px" id="ccm-composer-choose-parent-page-type">
				<?php  $types = array();
				foreach($ctArray as $cta) {
					$types[$cta->getCollectionTypeID()] = $cta->getCollectionTypeName();
				}
				?>
				<?php echo $form->select('ctComposerPublishPageTypeID', $types, $ct->getCollectionTypeComposerPublishPageTypeID())?>
			</div>
			
			<div>
			<?php echo $form->radio('ctComposerPublishPageMethod', 'PARENT', $ct->getCollectionTypeComposerPublishMethod() == 'PARENT')?>
			<?php echo $form->label('ctComposerPublishPageMethod3', t('Always publish below a certain page.'))?>
			</div>
			
			
			<div style="display: none; padding: 10px" id="ccm-composer-choose-parent">
			
			<?php  $pf = Loader::helper('form/page_selector');
			print $pf->selectPage('ctComposerPublishPageParentID', $ct->getCollectionTypeComposerPublishPageParentID());
			?>
			
			</div>
	
		
		</td>
	</tr>
	<tr class="row-composer">
		<td colspan="3" class="subheader"><?php echo t('Attributes to Display in Composer')?></td>
	</tr>
	<?php 
		$selectedAttributes = array();
		$cpattribs = $ct->getComposerAttributeKeys();
		foreach($cpattribs as $cpa) {
			$selectedAttributes[] = $cpa->getAttributeKeyID();
		}
		
		$attribs = CollectionAttributeKey::getList();
		$i = 0;
		foreach($attribs as $ak) { 
		if ($i == 0) { ?>
			<tr class="row-composer">
		<?php  } ?>
		
		<td><?php echo $form->checkbox('composerAKID[]', $ak->getAttributeKeyID(), in_array($ak->getAttributeKeyID(), $selectedAttributes))?> <?php echo $form->label('composerAKID_' . $ak->getAttributeKeyID(), $ak->getAttributeKeyName())?></td>
		
		<?php  $i++;
		
		if ($i == 3) { ?>
		</tr>
		<?php  
		$i = 0;
		}
		
	}
	
	if ($i < 3 && $i > 0) {
		for ($j = $i; $j < 3; $j++) { ?>
			<td>&nbsp;</td>
		<?php  }
	?></tr>
	<?php  } ?>
	<tr>
		<td colspan="3" class="header"><?php echo t('Composer Content Order')?></td>
	</tr>
	<tr>
		<td colspan="3">
			<div class="ccm-composer-content-item-list">
			<?php 
			$cur = Loader::helper('concrete/urls');
						
			foreach($contentitems as $ci) { 
				if ($ci instanceof AttributeKey) {
					$ak = $ci;
				?>
			
			<div class="ccm-composer-content-item" id="item_akID<?php echo $ak->getAttributeKeyID()?>">
				<img class="ccm-composer-content-item-icon" src="<?php echo $ak->getAttributeKeyIconSRC()?>" width="16" height="16" /><?php echo $ak->getAttributeKeyName()?>
			</div>
	
				<?php  } else if ($ci instanceof Block) { 
					$b = $ci; ?>

				
			<div class="ccm-composer-content-item" id="item_bID<?php echo $b->getBlockID()?>">
				<img class="ccm-composer-content-item-icon" src="<?php echo $cur->getBlockTypeIconURL($b)?>" width="16" height="16" /><?php 
					if ($b->getBlockName()) {
						print $b->getBlockName();
					} else {
						print $b->getBlockTypeName();
					}
				?>
			</div>
				<?php  } ?>

			<?php  } ?>
			
			</div>
		</td>
	</tr>
	
	<tr>
		<td colspan="3" class="header">
		<?php  print $ih->submit(t('Save Composer Settings'), 'update', 'right');?>
		<?php  print $ih->button(t('Back to Page Types'), $this->url('/dashboard/pages/types'), 'left');?>
		</td>
	</tr>
	</table>
		
	</div>
	
<?php  } else { ?>
	<div class="ccm-dashboard-inner">
		<?php echo t('Unable to access composer settings.'); ?>
	</div>
<?php  } ?>
</form>

<script type="text/javascript">
ccm_setupComposerFields = function() {
	
	if ($("input[name=ctIncludeInComposer]").prop('checked')) {
		$(".row-composer input, .row-composer select").attr('disabled', false);
	} else {
		$(".row-composer input, .row-composer select").attr('disabled', 'true');
	}
	var val = $('input[name=ctComposerPublishPageMethod]:checked').val();
	switch(val) {
		case 'PAGE_TYPE':
			$("#ccm-composer-choose-parent-page-type").show();
			$("#ccm-composer-choose-parent").hide();
			break;
		case 'PARENT':
			$("#ccm-composer-choose-parent-page-type").hide();
			$("#ccm-composer-choose-parent").show();
			break;
		default:
			$("#ccm-composer-choose-parent-page-type").hide();
			$("#ccm-composer-choose-parent").hide();
		break;
	}
	
	$("div.ccm-composer-content-item-list").sortable({
		handle: 'img.ccm-composer-content-item-icon',
		cursor: 'move',
		opacity: 0.5,
		stop: function() {
			var ualist = $(this).sortable('serialize');
			$.post('<?php echo $this->action("save_content_items", $ct->getCollectionTypeID())?>', ualist, function(r) {

			});
		}
	
	});
}

$(function() {
	$("input[name=ctIncludeInComposer], input[name=ctComposerPublishPageMethod]").click(function() {
		ccm_setupComposerFields();
	});
	ccm_setupComposerFields();
});

</script>




