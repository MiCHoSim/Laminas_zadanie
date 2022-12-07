<?php

namespace Micho\Exception;

use Exception;
use Throwable;

/**
 ** Výnimka, zachytavajuca Chybu pri validovani
 * Class ChybaValidacie
 * @package Micho
 */
class ValidationException extends Exception
{
    /**
     ** uloženie poľa chýb ktore mi generuje Validator
     * @var array|mixed
     */
    private $errors = array();

    /**
     * @param array $errors Moja hodnota Slúžiaca na poslanie chýb
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, $errors = array())
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     ** Vráti uložené chyby
     * @return array|mixed Pole chýb
     */
    public function getMessages()
    {
        return $this->errors;
    }

}

/*
 * Autor: MiCHo
 */