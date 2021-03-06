<?php  defined('C5_EXECUTE') or die("Access Denied.");
	
class TagsBlockController extends BlockController {

	protected $btTable = 'btTags';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "350";
	
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	
	public $attributeHandle = 'tags';
	public $displayMode = 'page';
	public $cloudCount = 10;
	public $helpers =  array('navigation');	
	
	/** 
	 * Used for localization. If we want to localize the name/description we have to include this
	*/
	public function getBlockTypeDescription() {
		return t("List pages based on type, area.");
	}
	
	public function getBlockTypeName() {
		return t("Tags");
	}
	
	public function add() { 
		$ak = $this->loadAttribute();
		$this->set('ak',$ak);
		
		$this->set('displayMode','page');
	}
	
	protected function loadAttribute() {
		Loader::model('attribute/categories/collection');
		$ak = CollectionAttributeKey::getByHandle($this->attributeHandle);
		return $ak;
	}
	
	public function edit() { 
		$ak = $this->loadAttribute();
		$this->set('ak',$ak);
	}
	
	public function view() {
		$ak = $this->loadAttribute();
		if($this->displayMode == "cloud") {
			$type = $ak->getAttributeType();
			$controller = $type->getController();
			$controller->setAttributeKey($ak);
			$items = $controller->getOptions();
			$options = new SelectAttributeTypeOptionList();
			if($this->cloudCount > 0 && $items instanceof SelectAttributeTypeOptionList && $items->count()) {
				$i = 1;
				foreach($items as $item) {
					$options->add($item); 
					if($i >=$this->cloudCount) { break;}
					$i++;
				}
			} else {
				$options = $items;
			}
		} else {
			$c = Page::getCurrentPage();
			$av = $c->getAttributeValueObject($ak);
			$controller = $ak->getController();
			$options = $c->getAttribute($ak->getAttributeKeyHandle());
		}
		
		if($this->targetCID > 0) {
			$target = Page::getByID($this->targetCID);
			$this->set('target',$target);
		}		
		
		// grab selected tag, if we're linking to a page with a tag block on it.
		if(is_array($_REQUEST['akID'])) {
			$res = $_REQUEST['akID'][$ak->getAttributeKeyID()]['atSelectOptionID'][0];
			if(is_numeric($res) && $res > 0) {
				$selectedOptionID = $res;
			}
		}
		$this->set('selectedOptionID',$selectedOptionID);
		$this->set('options',$options);
		$this->set('akc',$controller);
		$this->set('ak',$ak);
	}
	
	public function save($args) {
		$ak = $this->loadAttribute();
		$c = Page::getByID($_REQUEST['cID'], 'RECENT');
		$nvc = $c->getVersionToModify();
		$ak->saveAttributeForm($nvc);
		$nvc->refreshCache();
		
		$args['cloudCount'] = (is_numeric($args['cloudCount'])?$args['cloudCount']:0);
		
		parent::save($args);
	}	
}