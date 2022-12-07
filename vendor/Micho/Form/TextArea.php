<?php

namespace Micho\Form;


/**
 ** Formulárová Control/Element TextArea
 * Class TextArea
 * @package Micho\Formular
 */
class TextArea extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME = 'name';
    const PLACEHOLDER = 'placeholder';
    const VALUE = 'value';
    const ROWS = 'rows';
    const ATR_CLAS_LABEL = 'atr_clas_label';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $name;
    protected $placeholder;
    protected $value;
    protected $rows;
    protected $atrClasLabel;

    /**
     * TextArea constructor.
     * @param string $name label - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $placeholder placeholder - Placeholder pre Kontrolku
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param int $rows rows - Počet riadkov Kontrolky
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($name, $nameDb, $placeholder, $value, $form, $rows, $atrClas, $atrClasLabel, $required, $attributes)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->rows = $rows;
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

        $label = '<label
                        class="' . $this->atrClasLabel . '"
                        for="' . $this->nameDb . '">
                        ' . $this->name . '
                  </label>';
        $input = '<textarea
                        ' . $form . '
                        class="' . $this->atrClas . '"
                        name="' . $this->nameDb . '"
                        id="' . $this->nameDb . '"
                        placeholder="' . $this->placeholder . '"
                        ' . $required . '
                        wrap="hard"
                        rows="' . $this->rows . '" cols="1" ' . $attributes . '>' . $this->value . '</textarea>';
        return  $label . $input;
    }
}
/*
 * Autor: MiCHo
 */
