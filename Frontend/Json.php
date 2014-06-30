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
    
}