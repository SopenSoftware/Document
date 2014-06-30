<?php 
class Document_Model_Document extends Tinebase_Record_Abstract
{
	/**
	 * key in $_validators/$_properties array for the filed which
	 * represents the identifier
	 *
	 * @var string
	 */
	protected $_identifier = 'id';

  /**
   * @var string application of this filter group
   */
  protected $_application = 'Document';
    
	protected $_validators = array(
        'id'                    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
        'name'      => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'version'      => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'comment'      => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'creation_date'=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'mimetype'=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
	);

	protected $_datetimeFields = array(
		'creation_date',
	);

    /**
     * fills a record from json data
     *
     * @param string $_data json encoded data
     * @return void
     */
    public function setFromJson($_data)
    {
        parent::setFromJson($_data);
       
        // do something here if you like
   } 
}
?>
