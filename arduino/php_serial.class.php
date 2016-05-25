<?php
/**
* Serial port control class
*
* @author Rémy Sanchez <thenux@gmail.com>
* @copyright under GPL 2 licence
*/
class phpSerial
{
    var $_device = null;
    var $_dHandle = null;
    var $_buffer = "";
    
    /**
     * This var says if buffer should be flushed by sendMessage (true) or manualy (false)
     *
     * @var bool
     */
    var $autoflush = true;
    
    /**
     * Constructor. Perform some checks about the OS and setserial
     *
     * @return bool
     */
    function phpSerial ()
    {
        setlocale(LC_ALL, "en_US");
        
        $sysname = php_uname();
        
        if (substr($sysname, 0, 5) === "Linux")
        {
            $version = exec("setserial -V 2>&1");
            if (substr($version, 0, 19) === "setserial version 2")
            {
                register_shutdown_function(array($this, "freeDevice"));
                return true;
            }
            else
            {
                trigger_error("Invalid (!= 2) or inexistant setserial detected", E_USER_ERROR);
                exit();
                return false;
            }
        }
        else
        {
            trigger_error("Host OS is not linux, unable tu run.", E_USER_ERROR);
            exit();
            return false;
        }
    }
    
    /**
     * Sets the serial device (something like /dev/ttyS0), tests if valid and open it.
     *
     * @param string $device address of the device
     * @return bool
     */
    function setDevice ($device)
    {
        $test = exec("setserial " . $device . " 2>&1");
        
        if (substr($test, 0, strlen($device)) === $device)
        {
            $this->_device = $device;
            $this->_dHandle = fopen($this->_device, "wb");
            return true;
        }
        else
        {
            trigger_error("Specified serial port not valid", E_USER_WARNING);
            return false;
        }
    }
    
    /**
     * Closes the device file
     *
     * @return bool
     */
    function freeDevice ()
    {
        if ($this->_device !== null)
        {
            if (fclose ($this->_dHandle))
            {
                $this->_dHandle = null;
                $this->_device = null;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sets a setserial parameter (cf man setserial)
     *
     * @param string $param parameter name
     * @param string $arg parameter value
     * @return bool
     */
    function setParam ($param, $arg = "")
    {
        if ($this->_device === null)
        {
            trigger_error("Device must be specified", E_USER_WARNING);
            return false;
        }
        
        $return = exec ("setserial " . $this->_device . " " . $param . " " . $arg . " 2>&1");
        
        if ($return{0} === "I")
        {
            trigger_error("setserial: Invalid flag", E_USER_WARNING);
            return false;
        }
        elseif ($return{0} === "/")
        {
            trigger_error("setserial: Error with device file", E_USER_WARNING);
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Sends a string to the device
     *
     * @param string $str string to be sent to the device
     */
    function sendMessage ($str)
    {
        $this->_buffer .= $str;
        
        if ($this->autoflush === true) $this->flush();
    }
    
    /**
     * Flushes the buffer
     *
     * @return bool
     */
    function flush ()
    {
        if ($this->_device === null)
        {
            trigger_error("Device must be specified", E_USER_WARNING);
            return false;
        }

        if (fwrite($this->_dHandle, $this->_buffer) !== false)
        {
            $this->_buffer = "";
            return true;
        }
        else
        {
            $this->_buffer = "";
            trigger_error("Error while sending message", E_USER_WARNING);
            return false;
        }
    }
}
?> 
