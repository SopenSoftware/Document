<?php


/**
 * This class handles all Http requests for the Document application
 *
 * @package     Document
 * @subpackage  Frontend
 */
class Document_Frontend_Http extends Tinebase_Frontend_Http_Abstract
{
    protected $_applicationName = 'Document';

    /**
     * Returns all JS files which must be included for this app
     *
     * @return array Array of filenames
     */
    public function getJsFilesToInclude()
    {
        return array(
          'Document/js/Models.js',
          'Document/js/Backend.js',
          'Document/js/DocumentsPanel.js',
          'Document/js/AdminPanel.js'
        );
    }

    public function getCssFilesToInclude()
    {
        return array(
//            'DocManager/css/DocManager.css'
        );
    }

    public function content($docid)
    {
        $config = \Tinebase_Config::getInstance()->getConfig('seeddms', NULL, TRUE)->value;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookieFileName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL,$config['url']."/restapi/index.php/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=".$config['user']."&pass=".$config['pass']);

        $buf2 = curl_exec ($ch); // execute the curl command
        $data = json_decode($buf2);
        curl_close ($ch);
        unset($ch);

        if(!$data->success)
            return ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookieFileName");
        curl_setopt($ch, CURLOPT_URL,$config['url']."/restapi/index.php/document/".$docid);

        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($buf2);

        if(!$data->success)
            return ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookieFileName");
        curl_setopt($ch, CURLOPT_URL,$config['url']."/op/op.Download.php?documentid=".$docid.".&version=".$data->data->version);
        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        header('Content-Type: '.$data->data->mimetype);

        echo $buf2;
    }

    public function preview($docid)
    {
        $config = \Tinebase_Config::getInstance()->getConfig('seeddms', NULL, TRUE)->value;

        $seeddms = new Document_Backend_Seeddms($config['url']);
        $data = $seeddms->login($config['user'], $config['pass']);

        if(!$data->success)
            return;

        $data = $seeddms->getDocument($docid);
        $previewdata = $seeddms->getPreview($docid);
        header('Content-Type: image/png');

        echo $previewdata;
    }

}

// vim: set ts=4 sw=4 expandtab
