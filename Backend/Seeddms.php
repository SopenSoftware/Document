<?php
class Document_Backend_Seeddms
{
    /**
     * backend type
     *
     * @var string
     */
    protected $_cookiefile = '/tmp/cookieFileName';

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

    function __construct($url) {
        $this->_url = $url;
    }

    function login($user, $pass) {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=".$user."&pass=".$pass);

        $buf2 = curl_exec ($ch); // execute the curl command
        $data = json_decode($buf2);
        curl_close ($ch);
        unset($ch);

        return $data;
    }

    /**
     * Create a new folder
     *
     * @param integer $parent id of parent folder
     * @param string $id unique id used for attribute 'sopenid'
     * @param string $name optional name. If not passed $id is used
     */
    function getCreateFolder($parentid, $id, $name='') {
        if(!$name)
           $name = $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/folder/".$parentid."/createfolder");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "name=".$name."&comment=&attributes[sopenid]=".$id);

        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($buf2);
        return $data;
    }

    /**
     * Get folder by attribute value of sopen attribute
     */
    function getFolderByAttr($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/searchbyattr?name=sopenid&value=".$id);

        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($buf2);
        return $data;
    }

    function getFolderChildren($name, $parentid) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/folder/".$name."?parent=".$parentid);

        $buf2 = curl_exec ($ch);
        $data = json_decode($buf2);

        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/folder/".$data->data->id."/children");

        $buf2 = curl_exec ($ch);
        $data = json_decode($buf2);

        curl_close ($ch);
        unset($ch);

        return $data;
    }

    function getDocument($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/document/".(int) $id);

        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($buf2);
        return $data;
    }

    function search($query) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/restapi/index.php/search?query=".$query);

        $buf2 = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($buf2);
        return $data;
    }

    function getPreview($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookiefile);
        curl_setopt($ch, CURLOPT_URL,$this->_url."/op/op.Preview.php?documentid=".$id."&version=0&width=60");
        $buf2 = curl_exec ($ch);
        curl_close ($ch);
        return $buf2;
    }
}

// vim: set ts=4 sw=4 expandtab
