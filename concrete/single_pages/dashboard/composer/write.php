<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php  
if (isset($entry)) { ?>

	<form method="post" enctype="multipart/form-data" action="<?php  echo $this->action('save')?>" id="ccm-dashboard-composer-form">
	
	<h1><span><?php  echo ucfirst($action)?> <?php  echo $ct->getCollectionTypeName()?></span></h1>
	<div class="ccm-dashboard-inner" id="ccm-dashboard-composer">
	<div id="composer-save-status"></div>
	<h2><?php  echo t("Basic Information")?></h2>
	<ol>
		<li>
		<strong><?php  echo $form->label('cName', t('Name'))?></strong><br/>
		<?php  echo $form->text('cName', $name)?>		
		</li>
		<li>
		<strong><?php  echo $form->label('cDescription', t('Short Description'))?></strong><br/>
		<?php  echo $form->textarea('cDescription', $description)?>		
		</li>
		<li>
		<strong><?php  echo $form->label('cDatePublic', t('Date Posted'))?></strong><br/>
		<?php   
		if ($this->controller->isPost()) { 	
			$cDatePublic = Loader::helper('form/date_time')->translate('cDatePublic');
		}
		?>		
		<?php  echo Loader::helper('form/date_time')->datetime('cDatePublic', $cDatePublic)?>		
		</li>
	</ol>

	<?php   if ($entry->isComposerDraft()) { ?>
	<h2><?php  echo t('Publish Location')?></h2>
	<ol><li><span id="ccm-composer-publish-location"><?php  
		if ($entry->getComposerDraftPublishParentID() > 0) { 
			print $this->controller->getComposerDraftPublishText($entry);
		} ?>
		</span>
		
		<?php   
	
	if ($ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE' || $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE') { ?>
		
		<a href="javascript:void(0)" onclick="ccm_openComposerPublishTargetWindow(false)"><?php  echo t('Choose publish location.')?></a>
	
	<?php   } 
	
	?></li></ol>
	<?php   } ?>
	

	<h2><?php  echo t('Attributes &amp; Content')?></h2>
	
	<ol>
	<?php   
	foreach($contentitems as $ci) {
		if ($ci instanceof AttributeKey) { 
			$ak = $ci;
			if (is_object($entry)) {
				$value = $entry->getAttributeValueObject($ak);
			}
			?>
			<li><strong><?php  echo $ak->render('label');?></strong><br/>
			<?php  echo $ak->render('form', $value, true)?>	
			</li>
		
		<?php   } else { 
			$b = $ci; 
			$b = $entry->getComposerBlockInstance($b);
			?>
		
		<li>
		<?php  
		if (is_object($b)) {
			$bv = new BlockView();
			$bv->render($b, 'composer');
		} else {
			print t('Block not found. Unable to edit in composer.');
		}
		?>
		
		</li>
		
		<?php  
		} ?>
	<?php   }  ?>
	</ol>
	
	
		<?php  
		$v = $entry->getVersionObject();
		
		?>
		
		<?php  echo Loader::helper('concrete/interface')->submit(t('Save'), 'save', 'left')?>
		<?php  echo Loader::helper('concrete/interface')->submit(t('Discard'), 'discard', 'left', 'ccm-composer-hide-on-approved')?>
		<?php  echo Loader::helper('concrete/interface')->button_js(t('Preview'), 'javascript:ccm_composerLaunchPreview()', 'left', 'ccm-composer-hide-on-approved')?>

		<?php   if ($entry->isComposerDraft()) { 
		$pp = new Permissions($entry);
		?>
			<?php   if (PERMISSIONS_MODEL != 'simple' && $pp->canAdmin()) { ?>
				<?php  echo Loader::helper('concrete/interface')->button_js(t('Permissions'), 'javascript:ccm_composerLaunchPermissions()', 'left', 'ccm-composer-hide-on-no-target')?>
			<?php   } ?>
			<?php  echo Loader::helper('concrete/interface')->submit(t('Publish Page'), 'publish')?>
		<?php   } else { ?>
			<?php  echo Loader::helper('concrete/interface')->submit(t('Publish Changes'), 'publish')?>
		<?php   } ?>
		
		<?php  echo $form->hidden('entryID', $entry->getCollectionID())?>
		<?php   if ($entry->isComposerDraft()) { ?>
			<input type="hidden" name="cPublishParentID" value="<?php  echo $entry->getComposerDraftPublishParentID()?>" />
		<?php   } ?>
		<?php  echo $form->hidden('autosave', 0)?>
		<?php  echo Loader::helper('validation/token')->output('composer')?>
		<div class="ccm-spacer">&nbsp;</div>
		
	</div>
	</form>

	<script type="text/javascript">
	var ccm_composerAutoSaveInterval = false;
	var ccm_composerDoAutoSaveAllowed = true;
	
	ccm_composerDoAutoSave = function(callback) {
		if (!ccm_composerDoAutoSaveAllowed) {
			return false;
		}
		
		$('input[name=autosave]').val('1');
		try {
			tinyMCE.triggerSave(true, true);
		} catch(e) { }
		
		$('#ccm-dashboard-composer-form').ajaxSubmit({
			'dataType': 'json',
			'success': function(r) {
				$('input[name=autosave]').val('0');
				ccm_composerLastSaveTime = new Date();
				$("#composer-save-status").html('<?php  echo t("Page saved at ")?>' + r.time);
				$(".ccm-composer-hide-on-approved").show();
				if (callback) {
					callback();
				}
			}
		});
	}
	
	ccm_composerLaunchPreview = function() {
		jQuery.fn.dialog.showLoader();
		<?php   $t = PageTheme::getSiteTheme(); ?>
		ccm_composerDoAutoSave(function() {
			ccm_previewInternalTheme(<?php  echo $entry->getCollectionID()?>, <?php  echo $t->getThemeID()?>, '<?php  echo addslashes(str_replace(array("\r","\n","\n"),'',$t->getThemeName()))?>');
		});
	}
	
	ccm_composerSelectParentPage = function(cID) {
		$("input[name=cPublishParentID]").val(cID);
		$(".ccm-composer-hide-on-no-target").show();
		$("#ccm-composer-publish-location").load('<?php  echo $this->action("select_publish_target")?>', {'entryID': <?php  echo $entry->getCollectionID()?>, 'cPublishParentID': cID});
		jQuery.fn.dialog.closeTop();

	}	

	ccm_composerSelectParentPageAndSubmit = function(cID) {
		$("input[name=cPublishParentID]").val(cID);
		$(".ccm-composer-hide-on-no-target").show();
		$("#ccm-composer-publish-location").load('<?php  echo $this->action("select_publish_target")?>', {'entryID': <?php  echo $entry->getCollectionID()?>, 'cPublishParentID': cID}, function() {
		 	$("input[name=ccm-submit-publish]").click();
		});
	}	
		
	ccm_composerLaunchPermissions = function(cID) {
		var shref = CCM_TOOLS_PATH + '/edit_collection_popup?ctask=edit_permissions_composer&cID=<?php  echo $entry->getCollectionID()?>';
		jQuery.fn.dialog.open({
			title: '<?php  echo t("Permissions")?>',
			href: shref,
			width: '640',
			modal: false,
			height: '310'
		});
	}
	
	ccm_composerEditBlock = function(cID, bID, arHandle, w, h) {
		if(!w) w=550;
		if(!h) h=380; 
		var editBlockURL = '<?php  echo REL_DIR_FILES_TOOLS_REQUIRED ?>/edit_block_popup';
		$.fn.dialog.open({
			title: ccmi18n.editBlock,
			href: editBlockURL+'?cID='+cID+'&bID='+bID+'&arHandle=' + encodeURIComponent(arHandle) + '&btask=edit',
			width: w,
			modal: false,
			height: h
		});		
	}
	
	ccm_openComposerPublishTargetWindow = function(submitOnChoose) {
		var shref = CCM_TOOLS_PATH + '/composer_target?cID=<?php  echo $entry->getCollectionID()?>';
		if (submitOnChoose) {
			shref += '&submitOnChoose=1';
		}
		jQuery.fn.dialog.open({
			title: '<?php  echo t("Publish Page")?>',
			href: shref,
			width: '550',
			modal: false,
			height: '400'
		});
	}
	
	$(function() {
		<?php   if (is_object($v) && $v->isApproved()) { ?>
			$(".ccm-composer-hide-on-approved").hide();
		<?php   } ?>

		if ($("input[name=cPublishParentID]").val() < 1) {
			$(".ccm-composer-hide-on-no-target").hide();
		}
		
		var ccm_composerAutoSaveIntervalTimeout = 7000;
		var ccm_composerIsPublishClicked = false;
		
		$("#ccm-submit-discard").click(function() {
			return (confirm('<?php  echo t("Discard this draft?")?>'));
		});
		
		$("#ccm-submit-publish").click(function() {
			ccm_composerIsPublishClicked = true;
		});
		
		$("#ccm-dashboard-composer-form").submit(function() {
			ccm_composerDoAutoSaveAllowed = false;
		});
		
		<?php   if ($entry->isComposerDraft()) { ?>
			$("#ccm-dashboard-composer-form").submit(function() {
				if ($("input[name=cPublishParentID]").val() > 0) {
					return true;
				}
				if (ccm_composerIsPublishClicked) {
					ccm_composerIsPublishClicked = false;			
	
					<?php   if ($ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE' || $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE') { ?>
						ccm_openComposerPublishTargetWindow(true);
						return false;
					<?php   } else if ($ct->getCollectionTypeComposerPublishMethod() == 'PARENT') { ?>
						return true;				
					<?php   } else { ?>
						return false;
					<?php   } ?>
				}
			});
		<?php   } ?>
		ccm_composerAutoSaveInterval = setInterval(function() {
			ccm_composerDoAutoSave();
		}, 
		ccm_composerAutoSaveIntervalTimeout);
		
	});
	</script>
	
	
<?php   } else { ?>

	<h1><span><?php  echo t('Composer')?></span></h1>
	<div class="ccm-dashboard-inner" id="ccm-dashboard-composer">


	<?php   if (count($ctArray) > 0) { ?>
	<h2><?php  echo t('What type of page would you like to write?')?></h2>
	<ul>
	<?php   foreach($ctArray as $ct) { ?>
		<li><a href="<?php  echo $this->url('/dashboard/composer/write', $ct->getCollectionTypeID())?>"><?php  echo $ct->getCollectionTypeName()?></a></li>
	<?php   } ?>
	</ul>
	<?php   } else { ?>
		<p><?php  echo t('You have not setup any page types for Composer.')?></p>
	<?php   } ?>

	</div>
	
<?php   } ?>

