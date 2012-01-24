<?php  defined('C5_EXECUTE') or die("Access Denied.");?>

<h1><span><?php echo t('Multilingual Setup')?></span></h1>
<div class="ccm-dashboard-inner">
<h2><?php echo t('Interface')?></h2>
<?php  

if (count($languages) == 0) { ?>
	<?php echo t("You don't have any interface languages installed. You must run concrete5 in English.");?>
<?php  } else { ?>
	
	<form method="post" action="<?php echo $this->action('save_interface_language')?>">
	<div><?php echo $form->checkbox('LANGUAGE_CHOOSE_ON_LOGIN', 1, $LANGUAGE_CHOOSE_ON_LOGIN)?> <?php echo $form->label('LANGUAGE_CHOOSE_ON_LOGIN', t('Offer choice of language on login.'))?></div>
	<?php  if (defined('LOCALE')) { ?>
	<br/>
		<strong><?php echo t('Default Language: ')?></strong>
		<div><?php  foreach($interfacelocales as $sl => $v) {
			if ($sl == LOCALE) {
				print $v;
			}
		} ?> <?php echo t('This has been set in config/site.php')?></div>
	<?php  } else { ?>
		<div><?php echo $form->label('SITE_LOCALE', t('Default Language'))?> <?php echo $form->select('SITE_LOCALE', $interfacelocales, SITE_LOCALE);?></div>
	<?php  } ?>
	
	<br/>
	<?php echo Loader::helper('validation/token')->output('save_interface_language')?>
	<?php echo  Loader::helper('concrete/interface')->submit(t('Save'), 'save', 'left')?>
	</form>
	
<?php  } ?>

</div>