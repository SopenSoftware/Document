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
class Document_Controller extends Tinebase_Controller_Abstract implements Tinebase_Event_Interface, Tinebase_Container_Interface
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
        $this->_backend = new Document_Backend_Document();
        $this->_modelName = 'Document_Model_Document';
        $this->_currentAccount = Tinebase_Core::getUser();
        $this->_config = isset(Tinebase_Core::getConfig()->Document) ? Tinebase_Core::getConfig()->Document : new Zend_Config(array());
    }

    /**
     * Don't clone. Use the singleton.
     */
    private function __clone() {
    }

    /**
     * The singleton pattern
     *
     * @return Document_Controller
     */
    public static function getInstance() {
        if (self::$_instance === NULL) {
            self::$_instance = new Document_Controller;
        }

        return self::$_instance;
    }

    /**
     * Not needed.
     *
     * @param string $_accountId
     * @return boolean
     */
    public function createPersonalFolder($_accountId) {
        return false;
    }

    /**
     * Event handler
     *
     * @param \Tinebase_Event_Abstract $_eventObject
     * @return \Tinebase_Event_Abstract
     */
    public function handleEvents(\Tinebase_Event_Abstract $_eventObject) {
        return $_eventObject;
    }

    /**
     * Returns settings for document app
     *
     * @return \Document_Model_Config
     */
    public function getSettings() {
        $result = new Document_Model_Config();

        $configs = array(
            'addressbook_id',
            'fundproject_id',
            'membership_id'
        );

        foreach ($configs as $setting) {
            $result->$setting = Tinebase_Config::getInstance()->getConfig($setting, $this->_applicationName, 0)->value;
        }

        return $result;
    }

    /**
     * Save settings for document app
     *
     * @param object $_settings
     * @return type
     */
    public function saveSettings($_settings) {
        foreach ($_settings->toArray() as $field => $value) {
            if ($field == 'id') {
                continue;
            } else if ($field == 'defaults') {
                parent::saveSettings($value);
            } else {
                Tinebase_Config::getInstance()->setConfigForApplication($field, $value, $this->_applicationName);
            }
        }

        return $this->getSettings();
    }

}

