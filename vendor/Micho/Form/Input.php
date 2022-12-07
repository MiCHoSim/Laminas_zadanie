<?php

namespace Micho\Form;


/**
 ** Formulárová Control/Element Input
 * Class Input
 * @package Micho\Form
 */
class Input extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME = 'name';
    const TYPE = 'type';
        const TYPE_PASSWORD = 'password';
        const TYPE_HIDDEN = 'hidden';
        const TYPE_TEXT = 'text';
        const TYPE_EMAIL = 'email';
        const TYPE_TEL = 'tel';
        const TYPE_DATE = 'date';
        const TYPE_TIME = 'time';
        const TYPE_COLOR = 'color';
        const TYPE_NUMBER = 'number';


    const VALUE = 'value';
    const ATR_CLAS_LABEL = 'atr_clas_label';
    const PATTERN = 'pattern';
    const DISABLED = 'disabled';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $name;
    protected $type;
    protected $value;
    protected $atrClasLabel;
    protected $pattern;
    protected $disabled;


    /**
     ** Input constructor.
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $type type - O aky typ kontrolky sa jedna text/number/email/tel,...
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú
     * @param string $from form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param false|array $pattern pattern - Patern/Vzor/Pravidlo pre kontrolku array(popis,pattern)
     * @param false $disabled Či sa dá meniť hodnota prvku
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($name, $nameDb, $type, $value, $from, $atrClas, $atrClasLabel, $required, $pattern, $disabled, $attributes)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->atrClasLabel = $atrClasLabel;
        $this->pattern = $pattern;
        $this->disabled = $disabled;

        $required = $this->type === self::TYPE_HIDDEN ? false : $required; // pre Input type Hidden nieje podporovaný required

        parent::__construct($nameDb, $from, $atrClas, $required, $attributes);
    }

    /**
     * @return string Vytvorí HTML kontrolku
     */
    public function createControl()
    {
        $required = '';
        $title = '';
        $pattern = '';
        $disabled = '';
        $form = '';
        $attributes = '';
        $placeholder = '';
        $id = '';

        $label = '';

        if (!empty($this->form))
        {
            $form = 'form="' . $this->form . '"';
        }
        if ($this->required)
        {
            $required = ' required ';
        }
        if ($this->pattern)
        {
            $title = 'title = "' . $this->pattern['description'] . '"';
            $pattern = 'pattern = "' . $this->pattern['pattern'] . '"';
        }
        if ($this->disabled)
        {
            $disabled = ' disabled ';
        }
        if ($this->attributes)
        {
            $attributes = ' ' . $this->attributes . ' ';
        }

        if($this->type !== self::TYPE_HIDDEN) // pre Input type Hidden nieje podporovaný Placeholder
            $placeholder = 'placeholder="' . $this->name . '"';

        if($this->nameDb !== 'csrf') // pre Input s názvom csrf sa nebude generovať id ktoré je rovnaké ako Name, kvôli duplicitným ID a kedže nieje ID tak negenerujem ani label lebo ho neviem prideliť bez ID
        {
            $id = 'id="' . $this->nameDb . '"';
            $label = '<label
                        class="' . $this->atrClasLabel . '"
                        for="' . $this->nameDb . '">
                        ' . $this->name . '
                  </label>';
        }
        $input = '<input
                        ' . $form . '
                        value="' . $this->value . '"
                        class="' . $this->atrClas . '"
                        type="' . $this->type . '"
                        name="' . $this->nameDb . '"
                        ' . $id . '
                        ' . $placeholder . '
                        ' . $title . '
                        ' . $pattern . '
                        ' . $required . $disabled . ' ' . $attributes . '
                 />';
        return $label . $input;
    }

    /**
     **Vráti Vzor/Pattern podľa ktorej sa kontroluje správnosť údajov vo formulári
     * @return string vzor/pattern
     */
    public function returnPattern()
    {
        if ($this->pattern)
            return $this->pattern['pattern'];
        return false;
    }
}
/*
 * Autor: MiCHo
 */
