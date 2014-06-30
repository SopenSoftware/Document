<?php
class Document_Backend_Document extends Tinebase_Backend_Abstract
{
    /**
     * backend type
     *
     * @var string
     */
    protected $_type = 'SeedDMS';

    /**
     * url of rest api
     *
     * @var string
     */
    protected $_url = '';

    /**
     * password for logging into seeddms
     *
     * @var string
     */
    protected $_password = '';

    /**
     * username for logging into seeddms
     *
     * @var string
     */
    protected $_user = '';

    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Document_Model_Document';

    public function __construct ($_url = NULL, $_user = NULL, $_password = NULL)
    {
        $this->_url = $_url;
        $this->_user = $_user;
        $this->_password = $_password;
    }

    /**
     * Search for records matching given filter
     *
     * @param  Tinebase_Model_Filter_FilterGroup $_filter
     * @param  Tinebase_Model_Pagination         $_pagination
     * @param  boolean                           $_onlyIds
     * @return Tinebase_Record_RecordSet
     */
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE)
    {
        $config = \Tinebase_Config::getInstance()->getConfig('seeddms', NULL, TRUE)->value;

        $seeddms = new Document_Backend_Seeddms($config['url']);
        $data = $seeddms->login($config['user'], $config['pass']);

        if(!$data->success)
            return $this->_rawDataToRecordSet(array());

        $objectid = $_filter->getFilter('record_id');

        $data = $seeddms->getFolderByAttr($objectid);
        $result = array();
        if($data->success && $data->data) {
            $data = $seeddms->getFolderChildren($data->data[0]->id, $config['parentFolder']);

            foreach($data->data as $obj) {
                $result[] = array('id'=>$obj->id, 'name'=>$obj->name, 'version'=>$obj->version, 'comment'=>$obj->comment, 'creation_date'=>$obj->date, 'mimetype'=>$obj->mimetype); //$obj->comment);
            }
        }
        return $this->_rawDataToRecordSet($result);
            return $this->_rawDataToRecordSet(array(array('id'=>7, 'name'=>'Document 1', 'version'=>1, 'comment'=>'Kommentar fuer Doc1' /*.var_export($_filter, true)*/, 'creation_date'=>'2014-01-04'), array('id'=>8, 'name'=>'Hallo', 'version'=>2, 'comment'=>'Kommentar', 'creation_date'=>'2014-01-04')));
        }
    
    /**
     * Gets total count of search with $_filter
     * 
     * @param Tinebase_Model_Filter_FilterGroup $_filter
     * @return int
     */
    public function searchCount(Tinebase_Model_Filter_FilterGroup $_filter) {
        return 30;
    }
    
    /**
     * Return a single record
     *
     * @param string $_id
     * @param $_getDeleted get deleted records
     * @return Tinebase_Record_Interface
     */
    public function get($_id, $_getDeleted = FALSE) {
    }
    
    /**
     * Returns a set of contacts identified by their id's
     * 
     * @param  string|array $_id Ids
     * @param array $_containerIds all allowed container ids that are added to getMultiple query
     * @return Tinebase_RecordSet of Tinebase_Record_Interface
     */
    public function getMultiple($_ids, $_containerIds = NULL) {
    }

    /**
     * Gets all entries
     *
     * @param string $_orderBy Order result by
     * @param string $_orderDirection Order direction - allowed are ASC and DESC
     * @throws Tinebase_Exception_InvalidArgument
     * @return Tinebase_Record_RecordSet
     */
    public function getAll($_orderBy = 'id', $_orderDirection = 'ASC') {
    }
    
    /**
     * Create a new persistent contact
     *
     * @param  Tinebase_Record_Interface $_record
     * @return Tinebase_Record_Interface
     */
    public function create(Tinebase_Record_Interface $_record) {
    }
    
    /**
     * Upates an existing persistent record
     *
     * @param  Tinebase_Record_Interface $_contact
     * @return Tinebase_Record_Interface|NULL
     */
    public function update(Tinebase_Record_Interface $_record) {
    }
    
    /**
     * Updates multiple entries
     *
     * @param array $_ids to update
     * @param array $_data
     * @return integer number of affected rows
     */
    public function updateMultiple($_ids, $_data) {
    }
        
    /**
     * Deletes one or more existing persistent record(s)
     *
     * @param string|array $_identifier
     * @return void
     */
    public function delete($_identifier) {
    }

    /**
     * converts raw data from adapter into a set of records
     *
     * @param  array $_rawDatas of arrays
     * @return Tinebase_Record_RecordSet
     */
    protected function _rawDataToRecordSet(array $_rawDatas)
    {
        return new Tinebase_Record_RecordSet($this->_modelName, $_rawDatas, true);
    }
    
}

// vim: set ts=4 sw=4 expandtab
