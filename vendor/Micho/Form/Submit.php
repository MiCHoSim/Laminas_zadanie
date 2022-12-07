<?php

namespace Micho\Form;


/**
 ** Formulárová Control/Element submit
 * Class Submit
 * @package Micho\Form
 */
class Submit extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const VALUE = 'value';

    /**
     * @var string Parametre potrebné pre zostavenie Tlačídla
     */
    protected $value;

    /**
     * Submit constructor.
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú -> Názov Tlačidla
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($value, $nameDb, $form = '', $atrClas = 'btn btn-lg btn-outline-danger btn-block', $attributes)
    {
        $this->value = $value;
        parent::__construct($nameDb, $form, $atrClas, $requered = true, $attributes);
    }

    /**
     * @return string Vytvorí HTML Tlačidlo
     */
    public function createControl()
    {
        $form = '';
        $attributes = '';

        if(!empty($this->form))
        {
            $form = 'form="' . $this->form . '"';
        }
        if ($this->attributes)
        {
            $attributes = ' ' . $this->attributes . ' ';
        }

        return '<input 
                            ' . $form . '
                            class="' . $this->atrClas . '" 
                            type="submit" 
                            name="' . $this->nameDb . '" 
                            value="' . $this->value . '" 
                            id="' . $this->nameDb . '" ' . $attributes . '
                       />';
    }
}
/*
 * Autor: MiCHo
 */
