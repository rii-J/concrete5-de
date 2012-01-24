<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<h1><span><?php echo t('Drafts')?></h1>
<div class="ccm-dashboard-inner">
<?php  
$today = Loader::helper('date')->getLocalDateTime('now', 'Y-m-d');
if (count($drafts) > 0) { ?>

<table class="ccm-results-list">
<tr>
	<th width="60%"><?php echo t('Page Name')?></th>
	<th width="20%"><?php echo t('Page Type')?></th>
	<th width="20%"><?php echo t('Last Modified')?></th>
</tr>
<?php  foreach($drafts as $dr) { ?>
<tr>
	<td><a href="<?php echo $this->url('/dashboard/composer/write', 'edit', $dr->getCollectionID())?>"><?php  if (!$dr->getCollectionName()) {
		print t('(Untitled Page)');
	} else {
		print $dr->getCollectionName();
	} ?></a></td>
	<td><?php echo $dr->getCollectionTypeName()?></td>
	<td><?php 
		$mask = 'F jS Y - g:i a';
		if ($today == $dr->getCollectionDateLastModified("Y-m-d")) {
			$mask = 'g:i a';
		}
		print $dr->getCollectionDateLastModified($mask)?></td>
<?php  } ?>
</table>

<?php  } else { ?>
	
	<p><?php echo t('You have not created any drafts. <a href="%s">Visit Composer &gt;</a>', $this->url('/dashboard/composer/write'))?></p>

<?php  } ?>
</div>