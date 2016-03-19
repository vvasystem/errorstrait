<?php

namespace Assistance\ErrorsTrait;

trait ErrorTrait
{
    /** @var string  Name of session key for errors container */
    protected static $_session_container_key = '_e_r_r_o_r_s_';

    /** @var string  current key for session */
    protected $_current_session_key = '';

    /** @var array  Arrray of errors */
    protected $_errors = [];

    /**
     * Add error
     * @param string $name
     * @param string $key
     * @param array $data
     * @return $this
     */
    public function addError($name, $key = '', array $data = []) {
        $error = ['name' => $name, 'data' => $data];

        if ('' === $key) {
            $this->_errors[] = $error;
        } else {
            $this->_errors[$key] = $error;
        }

        return $this;
    }

    /**
     * Get errors
     * @param bool $withData - [['name' => string, 'data' => array], ... ]
     * @return array
     */
    public function getErrors($withData = false) {
        if ($withData) {
            return $this->_errors;
        }

        $result = [];
        foreach ($this->_errors as $key => $error) {
            $result[$key] = $error['name'];
        }

        return $result;
    }

    /**
     * Clear session
     * @return $this
     */
    private function clearSession() {
        if (array_key_exists(static::$_session_container_key, $_SESSION) && array_key_exists($this->_current_session_key, $_SESSION[static::$_session_container_key])) {
            unset($_SESSION[static::$_session_container_key][$this->_current_session_key]);
            if (0 === count($_SESSION[static::$_session_container_key])) {
                unset($_SESSION[static::$_session_container_key]);
            }
        }

        return $this;
    }

    /**
     * Clear errors
     * @return $this
     */
    public function clearErrors() {
        $this->_errors = [];

        $this->clearSession();

        $this->_current_session_key = '';

        return $this;
    }

    /**
     * Save errors to session
     * @param string $key
     * @return $this
     */
    public function errorsToSession($key = 'errors') {
        if ($this->hasErrors()) {

            if (!array_key_exists(static::$_session_container_key, $_SESSION) || !array_key_exists($key, $_SESSION[static::$_session_container_key])) {
                $_SESSION[static::$_session_container_key][$key] = $this->getErrors(true);
            } else {
                $_SESSION[static::$_session_container_key][$key] = array_merge($_SESSION[static::$_session_container_key][$key], $this->getErrors(true));
            }

            $this->_current_session_key = $key;
        }

        return $this;
    }

    /**
     * Load errors from session
     * @param string $key
     * @return bool
     */
    public function loadFromSession($key = 'errors') {
        if (!array_key_exists(static::$_session_container_key, $_SESSION) || !array_key_exists($key, $_SESSION[static::$_session_container_key])) {
            return false;
        }

        foreach($_SESSION[static::$_session_container_key][$key] as $errorKey => $error) {
            $this->addError($error['name'], $errorKey, $error['data']);
        }

        $this->_current_session_key = $key;

        return true;
    }

    /**
     *  Check errors
     * @return bool
     */
    public function hasErrors() {
        return 0 !== count($this->_errors);
    }
}