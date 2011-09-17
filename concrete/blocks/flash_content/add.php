<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$includeAssetLibrary = true;
$al = Loader::helper('concrete/asset_library');
?>
<h2><?php  echo t('Flash File')?></h2>
<?php  echo $al->file('ccm-b-file', 'fID', t('Choose File'));?>

<br/>
<h2><?php  echo t('Quality')?></h2>
<select name="quality">
	<option value="low"><?php  echo t('low')?></option>
    <option value="autolow"><?php  echo t('autolow')?></option>
    <option value="autohigh"><?php  echo t('autohigh')?></option>
    <option value="medium"><?php  echo t('medium')?></option>
    <option value="high" selected="selected"><?php  echo t('high')?></option>
    <option value="best"><?php  echo t('best')?></option>
</select><br /><br />

<h2><?php  echo t('Minimum Flash Player Version')?></h2>
<input type="text" name="minVersion" value="8.0" /><br /><br />