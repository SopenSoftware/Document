<?php
/**
 * Tine 2.0
 * 
 * MAIN controller for seeddms document, does event and container handling
 *
 * @package     Document
 * @subpackage  Controller
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Controller.php 14551 2010-05-28 12:47:00Z f.wiechmann@metaways.de $
 * 
 */

/**
 * main controller for Document
 *
 * @package     Document
 * @subpackage  Controller
 */
class Document_Controller extends Tinebase_Controller_Event implements Tinebase_Container_Interface
{
    /**
     * holds the instance of the singleton
     *
     * @var Document_Controller
     */
    private static $_instance = NULL;

    /**
     * constructor (get current user)
     */
    private function __construct() {
			$this->_applicationName = 'Document';
			$this->_backend = new Document_Backend_Project();
			$this->_modelName = 'Document_Model_Document';
			$this->_currentAccount = Tinebase_Core::getUser();
			$this->_config = isset(Tinebase_Core::getConfig()->Document) ? Tinebase_Core::getConfig()->Document : new Zend_Config(array());
    }
    
    /**
     * don't clone. Use the singleton.
     *
     */
    private function __clone() 
    {        
    }
    
    /**
     * the singleton pattern
     *
     * @return Document_Controller
     */
    public static function getInstance() 
    {
        if (self::$_instance === NULL) {
            self::$_instance = new Document_Controller;
        }
        
        return self::$_instance;
    }
    
}

