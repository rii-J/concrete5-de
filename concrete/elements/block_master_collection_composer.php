<?php  
defined('C5_EXECUTE') or die("Access Denied.");
global $c;?>
<?php  
$form = Loader::helper('form');
$bt = BlockType::getByID($b->getBlockTypeID());
$templates = $bt->getBlockTypeComposerTemplates();
$txt = Loader::helper('text');
?>
<form method="post" id="ccmComposerCustomTemplateForm" action="<?php  echo $b->getBlockUpdateComposerSettingsAction()?>&rcID=<?php  echo intval($rcID) ?>">

	<strong><?php  echo t('Composer')?></strong><br/>
	<?php  echo $form->checkbox('bIncludeInComposer', 1, $b->isBlockIncludedInComposer())?> <?php  echo t("Include block in Composer")?>
	<br/><br/>
	
	
	<strong><?php  echo t('Block Name')?></strong><br/>
	<?php  echo $form->text('bName', $b->getBlockName(), array('style' => 'width: 280px'))?>
	<br/><br/>
	
	<strong><?php  echo t('Custom Composer Template')?></strong><br>
	
	<?php   if (count($templates) == 0) { ?>
	
		<?php  echo t('There are no custom templates available.')?>

	<?php   } else { ?>
	
		<select name="cbFilename">
			<option value="">(<?php  echo t('None selected')?>)</option>
			<?php   foreach($templates as $tpl) { ?>
				<option value="<?php  echo $tpl?>" <?php   if ($b->getBlockComposerFilename() == $tpl) { ?> selected <?php   } ?>><?php  	
					if (strpos($tpl, '.') !== false) {
						print substr($txt->unhandle($tpl), 0, strrpos($tpl, '.'));
					} else {
						print $txt->unhandle($tpl);
					}
					?></option>		
			<?php   } ?>
		</select>
		
	<?php   } ?>
<?php  
$valt = Loader::helper('validation/token');
$valt->output();
?>

		<div class="ccm-buttons">
		<a href="#" class="ccm-dialog-close ccm-button-left cancel"><span><em class="ccm-button-close"><?php  echo t('Cancel')?></em></span></a>
		<a href="javascript:void(0)" onclick="$('#ccmComposerCustomTemplateForm').submit()" class="ccm-button-right accept"><span><?php  echo t('Update')?></span></a>
		</div>

</form>

<script type="text/javascript">
$(function() {
	$('#ccmComposerCustomTemplateForm').each(function() {
		ccm_setupBlockForm($(this), '<?php  echo $b->getBlockID()?>', 'edit');
	});
});
</script>