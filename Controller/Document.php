<?php
/**
 * Document controller for Document application
 *
 * @package     Document
 * @subpackage  Controller
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 */

/**
 * Document controller class for Document application
 *
 * @package     Document
 * @subpackage  Controller
 */
class Document_Controller_Document extends Tinebase_Controller_Record_Abstract
{
    /**
     * config of courses
     *
     * @var Zend_Config
     */
    protected $_config = NULL;

    /**
     * the constructor
     *
     * don't use the constructor. use the singleton
     */
    private function __construct() {
        $this->_applicationName = 'Document';
        $this->_modelName = 'Document_Model_Document';
        $this->_purgeRecords = FALSE;
        // activate this if you want to use containers
        $this->_doContainerACLChecks = FALSE;
        $this->_resolveCustomFields = FALSE;
        $this->_config = isset(Tinebase_Core::getConfig()->Document) ? Tinebase_Core::getConfig()->Document : new Zend_Config(array());
        $this->_backend = new Document_Backend_Document();
    }

    /**
     * holds the instance of the singleton
     *
     * @var Document_Controller_Document
     */
    private static $_instance = NULL;

    /**
     * the singleton pattern
     *
     * @return Document_Controller_Document
     */
    public static function getInstance()
    {
        if (self::$_instance === NULL) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}
