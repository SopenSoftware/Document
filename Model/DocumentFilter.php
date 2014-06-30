<?php
class Document_Model_DocumentFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Document';
    
    protected $_className = 'Document_Model_DocumentFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
        'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
        'name' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'query' => array('filter' => 'Tinebase_Model_Filter_Query', 'options' => array('fields' => array('name','version','comment','creation_date'))),
        'record_model' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'record_id' => array('filter' => 'Tinebase_Model_Filter_Text'),
    );

		protected $_filter;

		function setFromArrayInUsersTimezone($filter) {
			$this->_filter = $filter;
		}

		function getFilter($field) {
			foreach($this->_filter as $filter) {
				if($filter['field'] == $field)
					return $filter['value'];
			}
		}
}
?>
