<?php

class TextileEditorField extends TextareaField
{
	/**
	 * Includes the JavaScript neccesary for this field to work using the {@link Requirements} system.
	 */
	public static function include_js() {
		
		Requirements::clear('textilefield/javascript/TextileEditorField.js');
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::css('textilefield/thirdparty/markitup/sets/textile/style.css');
		Requirements::css('textilefield/css/TextileEditorField.css');

		Requirements::javascript('textilefield/thirdparty/markitup/jquery.markitup.js');
		
		Requirements::javascript('textilefield/javascript/TextileEditorField.js');
		
	}

	function FieldHolder() {

		$id = $this->id();

		self::include_js();
        
		$customScript = <<<JS
			Behaviour.register({
				'#$id' : {
					ready : function() {
						convertInputToTextileEditorField(jQuery('#$id input'));
					}
			}
		});
JS;

		Requirements::customScript($customScript);
;
		$this->extend("fieldholderjs");

		return parent::FieldHolder();
	}
	/**
	 * @see TextareaField::__construct()
	 */
	public function __construct($name, $title = null, $rows = 30, $cols = 20, $value = '', $form = null) {
		parent::__construct($name, $title, $rows, $cols, $value, $form);
		
		$this->addExtraClass('TextileEditorField');
	}

	/**
	 * @todo add broken link checking
	 * @return string
	 */
	function Field() {
		if($this->readonly) {
			$attributes = array(
				'id' => $this->id(),
				'class' => 'readonly' . ($this->extraClass() ? $this->extraClass() : ''),
				'name' => $this->name,
				'tabindex' => $this->getTabIndex(),
				'readonly' => 'readonly'
			);

			return $this->createTag(
				'span',
				$attributes,
				(($this->value) ? nl2br(htmlentities($this->value, ENT_COMPAT, 'UTF-8')) : '<i>(' . _t('FormField.NONE', 'none') . ')</i>')
			);
		}
		else {
			$value  = $this->value;

			return $this->createTag (
				'textarea',
				array (
					'class'   => $this->extraClass(),
					'rows'    => $this->rows,
					'cols'    => $this->cols,
					'style'   => 'width: 100%; box-sizing: border-box; height: ' . ($this->rows * 11) . 'px', // prevents horizontal scrollbars
					'tinymce' => 'true',
					'id'      => $this->id(),
					'name'    => $this->name
				),
				htmlentities((string) $value, ENT_COMPAT, 'UTF-8')
			);
		}
	}
}

/**
 * Readonly version of an {@link TextileEditorField}.
 * @package forms
 * @subpackage fields-formattedinput
 */
class TextileEditorField_Readonly extends ReadonlyField {
	function Field() {
		$valforInput = $this->value ? Convert::raw2att($this->value) : "";
		return "<span class=\"readonly typography\" id=\"" . $this->id() . "\">" . ( $this->value && $this->value != '<p></p>' ? $this->value : '<i>(not set)</i>' ) . "</span><input type=\"hidden\" name=\"".$this->name."\" value=\"".$valforInput."\" />";
	}
	function Type() {
		return 'TextileEditorfield readonly';
	}
}

/**
 * External toolbar for the TextileEditorField.
 * This is used by the CMS
 * @package forms
 * @subpackage fields-formattedinput
 */
class TextileEditorField_Toolbar extends RequestHandler {
	protected $controller, $name;
	
	function __construct($controller, $name) {
		parent::__construct();
		Requirements::javascript(SAPPHIRE_DIR . "/thirdparty/behaviour/behaviour.js");
		Requirements::javascript(SAPPHIRE_DIR . "/javascript/tiny_mce_improvements.js");
		
		Requirements::javascript(SAPPHIRE_DIR ."/thirdparty/jquery-form/jquery.form.js");
		Requirements::javascript(SAPPHIRE_DIR ."/javascript/TextileEditorField.js");
		
		$this->controller = $controller;
		$this->name = $name;
	}

	/**
	 * Searches the SiteTree for display in the dropdown
	 *  
	 * @return callback
	 */	
	function siteTreeSearchCallback($sourceObject, $labelField, $search) {
		return DataObject::get($sourceObject, "\"MenuTitle\" LIKE '%$search%' OR \"Title\" LIKE '%$search%'");
	}
	
	/**
	 * Return a {@link Form} instance allowing a user to
	 * add links in the TinyMCE content editor.
	 *  
	 * @return Form
	 */
	function LinkForm() {
		$siteTree = new TreeDropdownField('internal', _t('TextileEditorField.PAGE', "Page"), 'SiteTree', 'ID', 'MenuTitle', true);
		// mimic the SiteTree::getMenuTitle(), which is bypassed when the search is performed
		$siteTree->setSearchFunction(array($this, 'siteTreeSearchCallback'));
		
		$form = new Form(
			$this->controller,
			"{$this->name}/LinkForm", 
			new FieldSet(
				new LiteralField('Heading', '<h2><img src="cms/images/closeicon.gif" alt="' . _t('TextileEditorField.CLOSE', 'close').'" title="' . _t('TextileEditorField.CLOSE', 'close') . '" />' . _t('TextileEditorField.LINK', 'Link') . '</h2>'),
				new OptionsetField(
					'LinkType',
					_t('TextileEditorField.LINKTO', 'Link to'), 
					array(
						'internal' => _t('TextileEditorField.LINKINTERNAL', 'Page on the site'),
						'external' => _t('TextileEditorField.LINKEXTERNAL', 'Another website'),
						'anchor' => _t('TextileEditorField.LINKANCHOR', 'Anchor on this page'),
						'email' => _t('TextileEditorField.LINKEMAIL', 'Email address'),
						'file' => _t('TextileEditorField.LINKFILE', 'Download a file'),			
					)
				),
				$siteTree,
				new TextField('external', _t('TextileEditorField.URL', 'URL'), 'http://'),
				new EmailField('email', _t('TextileEditorField.EMAIL', 'Email address')),
				new TreeDropdownField('file', _t('TextileEditorField.FILE', 'File'), 'File', 'Filename', 'Title', true),
				new TextField('Anchor', _t('TextileEditorField.ANCHORVALUE', 'Anchor')),
				new TextField('LinkText', _t('TextileEditorField.LINKTEXT', 'Link text')),
				new TextField('Description', _t('TextileEditorField.LINKDESCR', 'Link description')),
				new CheckboxField('TargetBlank', _t('TextileEditorField.LINKOPENNEWWIN', 'Open link in a new window?')),
				new HiddenField('Locale', null, $this->controller->Locale)
			),
			new FieldSet(
				new FormAction('insert', _t('TextileEditorField.BUTTONINSERTLINK', 'Insert link')),
				new FormAction('remove', _t('TextileEditorField.BUTTONREMOVELINK', 'Remove link'))
			)
		);
		
		$form->loadDataFrom($this);
		
		$this->extend('updateLinkForm', $form);
		
		return $form;
	}

}
