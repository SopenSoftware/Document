<?php
/**
 * class Document_Model_Config
 *
 * @package     Document
 * @subpackage  Record
 */
class Document_Model_Config extends Tinebase_Record_Abstract {

    /**
     * record validators
     *
     * @var array
     */
    protected $_validators = array(
        'id' => array('allowEmpty' => true ),
        'addressbook_id' => array('allowEmpty' => true ),
        'fundproject_id' => array('allowEmpty' => true ),
        'membership_id' => array('allowEmpty' => true )
    );

    /**
     * identifier
     *
     * @var string
     */
    protected $_identifier = 'id';
}
