<?php  defined('C5_EXECUTE') or die("Access Denied."); 

$form = Loader::helper('form');
$json = Loader::helper('json');
if ($akSelectAllowMultipleValues && $akSelectAllowOtherValues) { // display autocomplete form
	$attrKeyID = $this->attributeKey->getAttributeKeyID();
	?>
	
<div class="ccm-attribute-type-select-autocomplete">

	<div id="selectedAttrValueRows_<?php  echo $attrKeyID;?>">
		<?php  
		foreach($selectedOptions as $optID) { 
			$opt = SelectAttributeTypeOption::getByID($optID);
			
			?>
			<div class="existingAttrValue">
				<?php echo $form->hidden($this->field('atSelectOptionID') . '[]', $opt->getSelectAttributeOptionID(), array('style'=>'position:relative;')); ?>
				<?php echo $opt->getSelectAttributeOptionValue()?>
				<a href="javascript:void(0);" onclick="$(this).parent().remove()">x</a>	
			</div>
		<?php  } 
		
		// now we get items from the post
		$vals = $this->post('atSelectNewOption');
		if (is_array($vals)) {
			foreach($vals as $v) { ?>
				<div class="newAttrValue">
					<?php echo $form->hidden($this->field('atSelectNewOption') . '[]', $v)?>
					<?php echo $v?>
					<a onclick="ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>.remove(this)" href="javascript:void(0)">x</a>
				</div>
			<?php  
			}
		}
		
		?>
	</div>
	<span style="position: relative">
	
	<?php  
	echo $form->text('newAttrValueRows'.$attrKeyID, array('class' => 'ccm-attribute-type-select-autocomplete-text', 'style'=>'position:relative; width: 200px'));
	?>
	<input type="button" class="ccm-input-button" value="<?php echo t('Add')?>" onclick="ccmAttributeTypeSelectTagHelper<?php echo $attrKeyID?>.addButtonClick(); return false" />
	</span>
</div>

	<script type="text/javascript">
	//<![CDATA[
	$(function() {
		var availableTags = <?php echo $json->encode($opt_values);?>;
		$("#newAttrValueRows<?php  echo $attrKeyID?>").autocomplete({
			source: "<?php echo $this->action('load_autocomplete_values')?>",
			select: function( event, ui ) {
				ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>.add(ui.item.value);
				$(this).val('');
				return false;
			}
		});

		$("#newAttrValueRows<?php  echo $attrKeyID?>").bind("keydown", function(e) {
			if (e.keyCode == 13) { // comma or enter
				if($(this).val().length > 0) {
					ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>.add($(this).val());
					$(this).val('');
					$("#newAttrValueRows<?php  echo $this->attributeKey->getAttributeKeyID()?>").autocomplete( "close" );	
				}
				return false;
			}
		});
	});

	var ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>={  
			addButtonClick: function() {
				var valrow = $("input[name=newAttrValueRows<?php echo $attrKeyID?>]");
				ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>.add(valrow.val());
				valrow.val('');
				$("#newAttrValueRows<?php  echo $this->attributeKey->getAttributeKeyID()?>").autocomplete( "close" );
				return false;
			},
			add:function(value){
				var newRow=document.createElement('div');
				newRow.className='newAttrValue';
				newRow.innerHTML='<input name="<?php echo $this->field('atSelectNewOption')?>[]" type="hidden" value="'+value+'" /> ';
				newRow.innerHTML+=value;
				newRow.innerHTML+=' <a onclick="ccmAttributeTypeSelectTagHelper<?php  echo $attrKeyID?>.remove(this)" href="javascript:void(0)">x</a>';
				$('#selectedAttrValueRows_<?php  echo $attrKeyID;?>').append(newRow);				
			},
			remove:function(a){
				$(a.parentNode).remove();			
			}
		}
	//]]>
	</script>
	<?php 
} else {

	$options = $this->controller->getOptions();

	if ($akSelectAllowMultipleValues) { ?>
			
		<?php  foreach($options as $opt) { ?>
			<div>
				<?php echo $form->checkbox($this->field('atSelectOptionID') . '[]', $opt->getSelectAttributeOptionID(), in_array($opt->getSelectAttributeOptionID(), $selectedOptions)); ?>
				<?php echo $opt->getSelectAttributeOptionValue()?></div>
		<?php  } ?>
	<?php  } else { 
		$opts = array('' => t('** None'));
		foreach($options as $opt) { 
			$opts[$opt->getSelectAttributeOptionID()] = $opt->getSelectAttributeOptionValue();
		}
		?>
		<?php echo $form->select($this->field('atSelectOptionID') . '[]', $opts, $selectedOptions[0]); ?>
	
	<?php  } 
	
	if ($akSelectAllowOtherValues) { ?>
		<div id="newAttrValueRows<?php echo $this->attributeKey->getAttributeKeyID()?>" class="newAttrValueRows"></div>
		<div><a href="javascript:void(0)" onclick="ccmAttributeTypeSelectHelper.add(<?php echo $this->attributeKey->getAttributeKeyID()?>, '<?php echo $this->field('atSelectNewOption')?>[]')">
			<?php echo t('Add Another Option')?></a>
		</div>
	<?php  } ?>
	
	<script type="text/javascript">
	//<![CDATA[
	var ccmAttributeTypeSelectHelper={  
		add:function(akID, field){
			var newRow=document.createElement('div');
			newRow.className='newAttrValueRow';
			newRow.innerHTML='<input name="' + field + '" type="text" value="" /> ';
			newRow.innerHTML+='<a onclick="ccmAttributeTypeSelectHelper.remove(this)" href="javascript:void(0)">[X]</a>';
			$('#newAttrValueRows'+akID).append(newRow);				
		},
		remove:function(a){
			$(a.parentNode).remove();			
		}
	}
	//]]>
	</script>
<?php  } ?>