<?php

namespace assistance\errorstrait;

class Errors
{
    use ErrorTrait;

    /**
     * Add errors from Errors object
     * @param Errors $object
     * @param bool $saveKey
     * @return $this
     */
    public function addErrorObject(Errors $object, $saveKey = false) {
        $errors = $object->getErrors(true);
        foreach ($errors as $key => $error) {
            $this->addError($error['name'], $saveKey ? $key : '', $error['data']);
        }

        return $this;
    }
}