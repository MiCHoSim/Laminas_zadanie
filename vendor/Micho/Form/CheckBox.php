<?php

namespace Micho\Form;


/**
 ** Formulárová Control/Element CheckBox
 * Class CheckBox
 * @package Micho\Form
 */
class CheckBox extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME = 'name';
    const CHECKED = 'checked';
    const DESCRIPTION = 'description';
    const ATR_CLAS_LABEL = 'atr_clas_label';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $name;
    protected $value;
    public $checked;
    protected $title;
    protected $atrClasLabel;

    /**
     * CheckBox constructor.
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú
     * @param false $checked checked - Či ma byť zaškrtnutý
     * @param string $title title - Popisok pre kontrolku
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($name, $nameDb, $value, $checked, $title, $form, $atrClas, $atrClasLabel, $required, $attributes)
    {
        $this->name = $name;
        $this->value = $value;
        $this->checked = $checked;
        $this->title = $title;
        $this->atrClasLabel = $atrClasLabel;
        parent::__construct($nameDb, $form, $atrClas, $required, $attributes);
    }

    /**
     * @return string Vytvorí HTML kontrolku
     */
    public function createControl()
    {
        $required = '';
        $checked = '';
        $form = '';
        $attributes = '';

        if (!empty($this->form))
        {
            $form = 'form="' . $this->form . '"';
        }
        if ($this->required)
        {
            $required = ' required ';
        }
        if ($this->checked)
        {
            $checked = ' checked ';
        }
        if ($this->attributes)
        {
            $attributes = ' ' . $this->attributes . ' ';
        }

        return '<label 
                            class="' . $this->atrClasLabel . '" 
                            for="' . $this->nameDb . '" 
                            title="' . $this->title . '">
                           <input 
                                ' . $form . '
                                class="' . $this->atrClas . '" 
                                type="checkbox" 
                                name="' . $this->nameDb . '" 
                                value="' . $this->value . '" 
                                id="' . $this->nameDb . '" 
                                ' . $required . $checked . ' ' . $attributes . '/>
                           ' . $this->name . '                   
                      </label>';
    }

    /**
     ** Zaskrtne kontrolku
     */
    public function checked()
    {
        $this->checked = true;

    }
}
/*
 * Autor: MiCHo
 */
