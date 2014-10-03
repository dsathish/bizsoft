<?php

/************************************************************************
 Implemented by : G.M.Sundar

On 02/28/200702:42:30 PM

description:
************************************************************************/


require_once ('spread_sheet/PPS.php');

/**
* Class for creating File PPS's for OLE containers
* @package  OLE
*/
class OLE_PPS_File extends OLE_PPS
{
    /**
    * The temporary dir for storing the OLE file
    * @var string
    */
    var $_tmp_dir;

    /**
    * The constructor
    *
    * @access public
    * @param string $name The name of the file (in Unicode)
    * @see OLE::Asc2Ucs()
    */
    function OLE_PPS_File($name)
    {
        $this->_tmp_dir = '';
        $this->OLE_PPS(
            null, 
            $name,
            OLE_PPS_TYPE_FILE,
            null,
            null,
            null,
            null,
            null,
            '',
            array());
    }

    /**
    * Sets the temp dir used for storing the OLE file
    *
    * @access public
    * @param string $dir The dir to be used as temp dir
    * @return true if given dir is valid, false otherwise
    */
    function setTempDir($dir)
    {
        if (is_dir($dir)) {
            $this->_tmp_dir = $dir;
            return true;
        }
        return false;
    }

    /**
    * Initialization method. Has to be called right after OLE_PPS_File().
    *
    * @access public
    * @return mixed true on success. PEAR_Error on failure
    */
    function init()
    {
        $this->_tmp_filename = tempnam($this->_tmp_dir, "OLE_PPS_File");
        $fh = @fopen($this->_tmp_filename, "w+b");
        if ($fh == false) {
            return $this->raiseError("Can't create temporary file");
        }
        $this->_PPS_FILE = $fh;
        if ($this->_PPS_FILE) {
            fseek($this->_PPS_FILE, 0);
        }
    }
    
    /**
    * Append data to PPS
    *
    * @access public
    * @param string $data The data to append
    */
    function append($data)
    {
        if ($this->_PPS_FILE) {
            fwrite($this->_PPS_FILE, $data);
        }
        else {
            $this->_data .= $data;
        }
    }
}
?>
