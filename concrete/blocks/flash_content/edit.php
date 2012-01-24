<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
	$al = Loader::helper('concrete/asset_library');
	$bf = null;
	if ($controller->getFileID() > 0) { 
		$bf = $controller->getFileObject();
	}
?>
<h2><?php echo t('Flash File')?></h2>
<?php echo $al->file('ccm-b-file', 'fID', t('Choose File'), $bf);?>

<br/><br/>
<h2><?php echo t('Quality')?></h2>
<select name="quality">
	<option value="low" <?php echo ($quality == "low"?"selected=\"selected\"":"")?>><?php echo t('low')?></option>
    <option value="autolow" <?php echo ($quality == "autolow"?"selected=\"selected\"":"")?>><?php echo t('autolow')?></option>
    <option value="autohigh" <?php echo ($quality == "autohigh"?"selected=\"selected\"":"")?>><?php echo t('autohigh')?></option>
    <option value="medium" <?php echo ($quality == "medium"?"selected=\"selected\"":"")?>><?php echo t('medium')?></option>
    <option value="high" <?php echo ($quality == "high"?"selected=\"selected\"":"")?>><?php echo t('high')?></option>
    <option value="best" <?php echo ($quality == "best"?"selected=\"selected\"":"")?>><?php echo t('best')?></option>
</select><br /><br />

<h2><?php echo t('Minimum Flash Player Version')?></h2>
<input type="text" name="minVersion" value="<?php echo $minVersion?>" /><br /><br />