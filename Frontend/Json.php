<?php
class Document_Frontend_Json extends Tinebase_Frontend_Json_Abstract{
    protected $_documentController = NULL;

    /**
     * the constructor
     *
     */
    public function __construct()
    {
        $this->_applicationName = 'Document';
        $this->_documentController = Document_Controller_Document::getInstance();
		}

    public function getDocument($id){
    	if(!$id ) {
            $obj = $this->_documentController->getEmptyDocument();
        } else {
            $obj = $this->_documentController->get($id);
        }

        $objData = $obj->toArray();
        return $objData;
    }

    public function searchDocuments($filter,$paging){
    	$result = $this->_search($filter,$paging,$this->_documentController, 'Document_Model_DocumentFilter');
    	return $result;
    }

    public function deleteDocuments($ids){
    	 return $this->_delete($ids, $this->_documentController);
    }

    /**
     * Create a new folder via seeddms api.
     *
     * @param int $parentId
     * @param int $id
     * @param string $name
     * @return string JSON from seeddms
     */
    public function createFolder($parentId, $id, $name) {
        $seeddms = Document_Backend_Seeddms::getInstance();

        return $seeddms->getCreateFolder($parentId, $id, $name);
    }

    /**
     * Get a folder from seeddms by a sopen record id.
     *
     * @param string $sopenid
     * @return string JSON from seeddms
     */
    public function getFolderBySopenId($sopenid) {
        $seeddms = Document_Backend_Seeddms::getInstance();

        return $seeddms->getFolderByAttr($sopenid);
    }

    public function saveDocument($recordData){
    	$obj = new Document_Model_Document();
        $obj->setFromJsonInUsersTimezone($recordData);
        //$obj->setFromArray($recordData);

        if (!$obj->getId()) {
            $obj = $this->_documentController->create($obj);
        } else {
            $obj = $this->_documentController->update($obj);
        }
        $result =  $this->getDocument($obj->getId());
        return $result;
    }

    /**
     * Returns settings for document app
     *
     * @return  array record data
     *
     * @todo    return json store style with totalcount/result?
     */
    public function getSettings()
    {
        $result = Document_Controller::getInstance()->getSettings()->toArray();

        return $result;
    }

    public function saveSettings($recordData) {
        $settings = new Document_Model_Config($recordData);
        $result = Document_Controller::getInstance()->saveSettings($settings)->toArray();

        return $result;
    }

    public function getUpload($parentid, $blob, $name) {
        $seeddms = Document_Backend_Seeddms::getInstance();
        $folder = $this->getFolderBySopenId($parentid);
        return $seeddms->uploadDocument($folder->data[0]->id, $blob, $name, true);
    }
}
