<?php

/**
 * Class ilBiblFieldFilterTableGUI
 *
 * @author Benjamin Seglias   <bs@studer-raimann.ch>
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilBiblFieldFilterTableGUI extends ilTable2GUI {

	use \ILIAS\Modules\OrgUnit\ARHelper\DIC;
	const TBL_ID = 'tbl_bibl_filters';
	/**
	 * @var \ilBiblFactoryFacade
	 */
	protected $facade;
	/**
	 * @var \ilBiblFieldFilterGUI
	 */
	protected $parent_obj;
	/**
	 * @var array
	 */
	protected $filter = [];


	/**
	 * ilBiblFieldFilterTableGUI constructor.
	 *
	 * @param \ilBiblFieldFilterGUI $a_parent_obj
	 * @param \ilBiblFactoryFacade  $facade
	 */
	public function __construct(\ilBiblFieldFilterGUI $a_parent_obj, ilBiblFactoryFacade $facade) {
		$this->facade = $facade;
		$this->parent_obj = $a_parent_obj;

		$this->setId(self::TBL_ID);
		$this->setPrefix(self::TBL_ID);
		$this->setFormName(self::TBL_ID);
		$this->ctrl()->saveParameter($a_parent_obj, $this->getNavParameter());

		$this->initButtons();

		parent::__construct($a_parent_obj);
		$this->parent_obj = $a_parent_obj;
		$this->setRowTemplate('tpl.bibl_settings_filters_list_row.html', 'Modules/Bibliographic');

		$this->setFormAction($this->ctrl()->getFormActionByClass(ilBiblFieldFilterGUI::class));

		$this->setDefaultOrderField("id");
		$this->setDefaultOrderDirection("asc");
		$this->setEnableHeader(true);

		$this->initColumns();
		$this->addFilterItems();
		$this->parseData();
	}


	protected function initButtons() {
		if ($this->access()->checkAccess('write', "", $this->facade->iliasRefId())) {
			$new_filter_link = $this->ctrl()->getLinkTargetByClass(ilBiblFieldFilterGUI::class, ilBiblFieldFilterGUI::CMD_ADD);
			$ilLinkButton = ilLinkButton::getInstance();
			$ilLinkButton->setCaption($this->lng()->txt("add_filter"), false);
			$ilLinkButton->setUrl($new_filter_link);
			$this->toolbar()->addButtonInstance($ilLinkButton);
		}
	}


	protected function initColumns() {
		$this->addColumn($this->lng()->txt('field'), 'field');
		$this->addColumn($this->lng()->txt('filter_type'), 'filter_type');
		$this->addColumn($this->lng()->txt('actions'), '', '150px');
	}


	protected function addFilterItems() {
		$field = new ilTextInputGUI($this->lng()->txt('field'), 'field');
		$this->addAndReadFilterItem($field);
	}


	/**
	 * @param $field
	 */
	protected function addAndReadFilterItem(ilFormPropertyGUI $field) {
		$this->addFilterItem($field);
		$field->readFromSession();
		if ($field instanceof ilCheckboxInputGUI) {
			$this->filter[$field->getPostVar()] = $field->getChecked();
		} else {
			$this->filter[$field->getPostVar()] = $field->getValue();
		}
	}


	/**
	 * Fills table rows with content from $a_set.
	 *
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		/**
		 * @var ilBiblFieldFilter $filter
		 * @var ilBiblField       $field
		 */
		$filter = $this->facade->filterFactory()->findById((int)$a_set['id']);
		$field = $this->facade->fieldFactory()->findById($filter->getFieldId());

		$this->tpl->setVariable('VAL_FIELD', $this->facade->translationFactory()
		                                                  ->translate($field));
		$this->tpl->setVariable('VAL_FILTER_TYPE', $this->lng()->txt("filter_type_"
		                                                             . $filter->getFilterType()));

		$this->addActionMenu($filter);
	}


	/**
	 * @param \ilBiblFieldFilter $ilBiblFieldFilter
	 */
	protected function addActionMenu(ilBiblFieldFilter $ilBiblFieldFilter) {
		$this->ctrl()->setParameterByClass(ilBiblFieldFilterGUI::class, ilBiblFieldFilterGUI::FILTER_ID, $ilBiblFieldFilter->getId());

		$current_selection_list = new ilAdvancedSelectionListGUI();
		$current_selection_list->setListTitle($this->lng->txt("actions"));
		$current_selection_list->setId($ilBiblFieldFilter->getId());
		$current_selection_list->addItem($this->lng()->txt("edit"), "", $this->ctrl()
		                                                                     ->getLinkTargetByClass(ilBiblFieldFilterGUI::class, ilBiblFieldFilterGUI::CMD_EDIT));
		$current_selection_list->addItem($this->lng()->txt("delete"), "", $this->ctrl()
		                                                                       ->getLinkTargetByClass(ilBiblFieldFilterGUI::class, ilBiblFieldFilterGUI::CMD_DELETE));
		$this->tpl->setVariable('VAL_ACTIONS', $current_selection_list->getHTML());
	}


	protected function parseData() {
		$this->determineOffsetAndOrder();
		$this->determineLimit();

		$sorting_column = $this->getOrderField() ? $this->getOrderField() : 'id';
		$sorting_column = 'id';
		$offset = $this->getOffset() ? $this->getOffset() : 0;

		$sorting_direction = $this->getOrderDirection();
		$num = $this->getLimit();

		$info = new ilBiblTableQueryInfo();
		$info->setSortingColumn($sorting_column);
		$info->setOffset($offset);
		$info->setSortingDirection($sorting_direction);
		$info->setLimit($num);

		$filter = $this->facade->filterFactory()->filterItemsForTable($this->facade->iliasObjId(), $info);
		$this->setData($filter);
	}
}