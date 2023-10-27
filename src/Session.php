<?php

namespace Aeforge\Session;

/** class Session
 * @author AEForge <https://github.com/aeforge>
 */
class Session
{
    protected $session_id = "__session_id__";
    protected $session_expire = "__session_expire__";
    protected $session_start = "__session_start__";
    protected $session_flash = "__session_flash__";
    protected $session_data = "__session_data__";
    /** 
     * Starts a new session instance
     */
    function __construct()
    {
        session_start();
    }
    /**
     * Creates a UNIX time stamp from current time
     * @access protected
     * @return int
     */
    protected function timeStamp()
    {
        $currentYear = date("Y");
        $currentMonth = date("m");
        $currentDay = date("d");
        $currentHour = date("H");
        $currentMinute = date("i");
        $currentSecond = date("s");
        return mktime(
            $currentHour,
            $currentMinute,
            $currentSecond,
            $currentYear,
            $currentMonth,
            $currentDay
        );
    }
    /**
     * Create a new UNIX time stamp with added values
     * @access protected
     * @param int $minutes [Optional | Default : 0]
     * @return int
     * @throws \InvalidArgumentException
     */
    protected function addTimeStamp($minutes = 0)
    {
        if (!is_int($minutes)) throw new \InvalidArgumentException("Expected \$minutes to be of type integer. Type is : " . gettype($minutes));

        $currentYear = date("Y");
        $currentMonth = date("m");
        $currentDay = date("d");
        $currentHour = date("H");
        $currentMinute = intval(date("i")) + $minutes;
        $currentSecond = date("s");

        return mktime(
            $currentHour,
            $currentMinute,
            $currentSecond,
            $currentYear,
            $currentMonth,
            $currentDay
        );
    }
    /**
     * Register a new session token
     * @access public
     * @param integer $minutes
     * @return void
     */
    public function register($minutes = 0)
    {
        $_SESSION[$this->session_expire] = $this->addTimeStamp($minutes);
        $_SESSION[$this->session_id] = session_id();
        $_SESSION[$this->session_start] = $this->timeStamp();
    }
    /**
     * Regenerate session id
     * @access public
     * @return bool
     */
    public function regenerate()
    {
        $regenerated = session_regenerate_id();
        if ($regenerated) $_SESSION[$this->session_id] = session_id();
        return $regenerated;
    }
    /**
     * Check if session expired
     * @access public
     * @return boolean
     */
    public function isExpired()
    {
        return (bool) $_SESSION[$this->session_expire] < $this->timeStamp();
    }
    /**
     * Adds a data to the flashing storage. Useful for (e.g. form errors)
     * @access public
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws \InvalidArgumentException
     */
    public function flash($key, $value)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        $_SESSION[$this->session_flash][$key] = $value;
    }
    /**
     * Empty the flash array from session
     * @access public
     * @return void
     */
    public function clearFlashed()
    {
        $_SESSION[$this->session_flash] = [];
    }
    /**
     * Empty the data array from session
     * @access public
     * @return void
     */
    public function clearData()
    {
        $_SESSION[$this->session_data] = [];
    }
    /**
     * Empty both the flash and data array from session
     * @access public
     * @return void
     */
    public function clear()
    {
        $_SESSION[$this->session_flash] = [];
        $_SESSION[$this->session_data] = [];
    }
    /**
     * Destroyes the session
     * @access public
     * @return void
     */
    public function destroy()
    {
        session_destroy();
        $_SESSION = [];
        unset($_SESSION);
    }
    /**
     * Get a session data from data array using key.
     * If key is not found, $default will be used instead
     * @access public
     * @param string $key
     * @param mixed $default
     * @return void
     * @throws \InvalidArgumentException
     */
    public function get($key, $default = "")
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        return $_SESSION[$this->session_data][$key] ?? $default;
    }
    /**
     * Set a session data in the data array using a key
     * @access public
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        $_SESSION[$this->session_data][$key] = $value;
    }
    /**
     * Get a session data from data array using key.
     * If key is not found, $default will be used instead
     * @access public
     * @param string $key
     * @param mixed $default
     * @param bool $removeOnRetrieve [Default : True] If true, the flashed item will be removed from the session
     * @return void
     * @throws \InvalidArgumentException
     */
    public function getFlashed($key, $default = "", $removeOnRetrieve = true)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        $flash = $_SESSION[$this->session_flash][$key] ?? $default;

        if($removeOnRetrieve && $this->isFlashed($key)) $this->removeFlashed($key);

        return $flash;
    }
    /**
     * Check if a key is stored in the flash session 
     * @access public
     * @param string $key
     * @return boolean
     */
    public function isFlashed($key)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        return (bool) isset($_SESSION[$this->session_flash][$key]);
    }
    /**
     * Removes a session item from the data session array
     * @access public
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        if(isset($_SESSION[$this->session_data][$key])) unset($_SESSION[$this->session_data][$key]);
    }
    /**
     * Removes a session item from the flash session array
     * @access public
     * @param string $key
     * @return void
     */
    public function removeFlashed($key)
    {
        if (!is_string($key)) throw new \InvalidArgumentException("Expected \$key to be of type string. Type is : " . gettype($key));

        if(isset($_SESSION[$this->session_flash][$key])) unset($_SESSION[$this->session_flash][$key]);
    }

}
