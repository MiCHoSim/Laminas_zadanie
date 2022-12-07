<?php

namespace Micho\Form;

/**
 ** Trieda služiaca na validáciu dát
 * Class Validator
 */
class Validator
{
    /**
     * Predefinované konštanty
     */
    const PATTERN_EMAIL = array('description' => 'Enter the email in the format ____@___.___',
                                'pattern' => '[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$'); // Pattern pre email

    const PATTERN_STRING = array('description' => 'Enter a string of at least 2 characters',
                                          'pattern' => '[A-Ža-ž]{2,}'); // Pattern pre meno a priezvisko

    const PATTERN_TEL = array('description' => 'Enter the telephone number in the classic format 09...',
                              'pattern' => '[0]{1}[9]{1}[0-9]{8}'); // Pattern pre telefonné číslo

    const PATTERN_REGISTER_NUMBER = array('description' => 'Enter your register number',
                              'pattern' => '[0-9]{1,}'); // Pattern pre súpisné číslo

    const PATTERN_POSTCODE = array('description' => 'Enter your postal code.',
                              'pattern' => '\d{3}[ ]?\d{2}'); // Pattern pre PSC

    const PATTERN_ANTISPAM_YEAR = array('description' => 'Enter the current year in 4 digits.',
                                       'pattern' => '[0-9]{4,4}'); // Pattern pre Antispam

    const PASSWORD_LENGTH = 8; // Dĺžka hesla
    const PATTERN_PASSWORD = array('description' =>' The password must contain at least ' . self::PASSWORD_LENGTH . ' characters, one number, one upper and lower case letter.',
                                'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{' . self::PASSWORD_LENGTH . ',}') ; // Pattern pre heslo, ktoré musí obsahovať 8 alebo viac znakov, ktoré majú aspoň jedno číslo a jedno veľké a malé písmeno

    const PATTERN_PIN = array('description' => 'Enter your registration pin. Pin will be provided by Čim Fit',
         'pattern' => '[0-9]{4,4}'); // Pattern pre Antispam
}
/*
 * Autor: MiCHo
 */