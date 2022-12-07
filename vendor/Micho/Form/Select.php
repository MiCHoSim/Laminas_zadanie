<?php

namespace Micho\Form;



/**
 ** Formulárová Control/Element Select
 * Class Select
 * @package Micho\Form
 */
class Select extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME = 'name';
    const OPTIONS = 'options';
    const VALUE = 'value';
    const MULTIPLE = 'multiple';
    const ATR_CLAS_LABEL = 'atr_clas_label';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $name;
    protected $options;
    protected $value;
    protected $multiple;
    protected $atrClasLabel;

    /**
     * Select constructor.
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param array $options Pole možnosti kde klúč je názov a hodnota je Hodnota value
     * @param string $value Hodnota práve vybratého SELECTU
     * @param bool $multiple multiple - či je povoľený výber viacerých súborov
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($name, $nameDb, array $options, $value, $multiple, $form, $atrClas, $atrClasLabel, $required, $attributes)
    {
        $this->name = $name;
        $this->options = $options;
        $this->value = $value;
        $this->multiple = $multiple;
        $this->atrClasLabel = $atrClasLabel;
        parent::__construct($nameDb, $form, $atrClas, $required, $attributes);
    }

    /**
     * @return string Vytvorí HTML kontrolku
     */
    public function createControl()
    {
        $required = '';
        $form = '';
        $attributes = '';
        $multiple = '';

        if (!empty($this->form))
        {
            $form = 'form="' . $this->form . '"';
        }

        if ($this->required)
        {
            $required = ' required ';
        }
        if ($this->attributes)
        {
            $attributes = ' ' . $this->attributes . ' ';
        }
        if ($this->multiple)
        {
            $multiple = ' multiple ';
            $this->nameDb .= '[]';
        }


        $label = '<label class="' . $this->atrClasLabel . '"
                         for="' . $this->nameDb . '">
                         ' . $this->name . '
                  </label>';
        $options = '';


        if(is_array($this->value)) // kvoli multiple select
            $i = 0;

        foreach ($this->options as $name => $value)
        {
            $selected = '';

            if(isset($i) &&  isset($this->value[$i]) && $this->value[$i] == $value) // kvoli multiple select
            {
                $selected = ' selected ';
                $i++;
            }
            elseif ($this->value == $value)
                $selected = ' selected ';

            $options .= '
                    <option 
                        ' . $form . '
                        value="' . $value . '"                                             
                        ' . $selected . ' >
                        ' . $name . '
                    </option>';
        }

        $select = '<select 
                        ' . $form . '
                        class="' . $this->atrClas . '"
                        name="' . $this->nameDb . '" 
                        id="' . $this->nameDb . '"
                        ' . $required . $multiple .' ' . $attributes . '>
                        ' . $options . '  
                   </select>';

        return $label . $select;
    }
}
/*
 * Autor: MiCHo
 */
