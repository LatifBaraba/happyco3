<?php

Class Validator {

    public $finalResult;
    public $lastError;
    public $firstError;
    public $negating;
    public $isValid;

    public function __construct()
    {
        $this->finalResult = true;
        $this->isValid = true;
        $this->negating = false;
        $this->lastError = '';
        $this->firstError = '';
    }

    private function appendMsg($msg = '')
    {
        if ($this->finalResult)
        {
            if ($this->isValid)
                $this->finalResult = true;
            else
            {
                if ($this->firstError == '')
                    $this->firstError = $msg;

                $this->lastError = $msg;
                $this->finalResult = false;
            }
        }

        $this->negating = false;
    }

    public function setErrorMsg($msg)
    {
        $this->finalResult = false;
        $this->firstError = $msg;
    }

    public function not()
    {
        $this->negating = true;
        return $this;
    }

    public function isEmail($var, $msg = '')
    {
        $var = trim($var);

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if (filter_var($var, FILTER_VALIDATE_EMAIL))
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if (filter_var($var, FILTER_VALIDATE_EMAIL))
                    $this->isValid = true;
                else 
                    $this->isValid = false;
            }
        }
        
        $this->appendMsg($msg);
        
        return $this;
    }

    public function isContain($var, $contain, $msg = '')
    {
        $var = trim($var);

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if (strpos($var, $contain) !== false)
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if (strpos($var, $contain) !== false)
                    $this->isValid = true;
                else 
                    $this->isValid = false;
            }
        }
        
        $this->appendMsg($msg);
        
        return $this;
    }

    public function isEqual($var1, $var2, $msg = '')
    {
        $var1 = trim($var1);
        $var2 = trim($var2);

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if ($var1 == $var2 || $var1 === $var2)
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if ($var1 == $var2 || $var1 === $var2)
                    $this->isValid = true;
                else 
                    $this->isValid = false;
            }
        }
        
        $this->appendMsg($msg);
        
        return $this;
    }

    public function isNumberLessThan($var, $max, $msg = '')
    {
        $var = (int) $var;
        $max = (int) $max;

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if ($var < $max)
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if ($var < $max)
                    $this->isValid = true;
                else
                    $this->isValid = false;
            }
        }

        $this->appendMsg($msg);

        return $this;
    }

    public function isNumberMoreThan($var, $max, $msg = '')
    {
        $var = (int) $var;
        $max = (int) $max;

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if ($var > $max)
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if ($var > $max)
                    $this->isValid = true;
                else
                    $this->isValid = false;
            }
        }

        $this->appendMsg($msg);

        return $this;
    }

    public function isNullOrEmpty($var, $msg = '')
    {
        if (is_string($var))
            $var = trim($var);

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if ($var === '' || $var == '' || is_null($var) || empty($var))
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if ($var === '' || $var == '' || is_null($var) || empty($var))
                    $this->isValid = true;
                else
                    $this->isValid = false;
            }
        }

        $this->appendMsg($msg);

        return $this;
    }

    public function isNullOrZero($var, $msg = '')
    {
        $var = (int) $var;

        if ($this->finalResult)
        {
            if (!$this->negating)
            {
                if ($var === 0 || $var == 0 || is_null($var))
                    $this->isValid = false;
                else 
                    $this->isValid = true;
            }
            else
            {
                if ($var === 0 || $var == 0 || is_null($var))
                    $this->isValid = true;
                else
                    $this->isValid = false;
            }
        }

        $this->appendMsg($msg);

        return $this;
    }

    public function isValid()
    {
        return $this->isValid;
    }
}

$appValidator = new Validator();

return $appValidator;