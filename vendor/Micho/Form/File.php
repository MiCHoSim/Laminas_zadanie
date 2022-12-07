<?php

namespace Micho\Form;


/**
 ** Formulárová Control/Element Input
 * Class Input
 * @package Micho\Form
 */
class File extends Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME = 'name';
    const ATR_CLAS_LABEL = 'atr_clas_label';
    const MULTIPLE = 'multiple';
    const ACCEPT = 'accept';
            const AUDIO = 'audio/*';
            const VIDEO = 'video/*';
            const IMAGE = 'image/*';
            const PNG = 'image/png';
            const JPG = 'image/jpeg';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $name;
    protected $atrClasLabel;
    protected $multiple;
    protected $accept;

    /**
     ** File constructor.
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param bool $multiple multiple - či je povoľený výber viacerých súborov
     * @param string $accept accept - Povoľené formáty z možnosti Konštant
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($name, $nameDb, $form, $atrClas, $atrClasLabel, $required, $multiple, $accept, $attributes)
    {
        $this->name = $name;
        $this->atrClasLabel = $atrClasLabel;
        $this->pozadovany = $required;
        $this->multiple = $multiple;
        $this->accept = $accept;
        parent::__construct($nameDb, $form, $atrClas, $required, $attributes);
    }

    /**
     * @return string Vytvorí HTML kontrolku
     */
    public function createControl()
    {
        $required = '';
        $form = '';
        $multiple = '';
        $accept = '';
        $attributes = '';

        if (!empty($this->form))
        {
            $form = ' form="' . $this->form . '" ';
        }
        if ($this->required)
        {
            $required = ' required ';
        }
        if ($this->multiple)
        {
            $multiple = ' multiple ';
            $this->nameDb .= '[]';
        }
        if ($this->accept)
        {
            $accept = ' accept="' . $this->accept . '" ';
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
        $file = '<input 
                        ' . $form . '
                        class="' . $this->atrClas . '" 
                        type="file" 
                        name="' . $this->nameDb . '" 
                        id="' . $this->nameDb . '" 
                        ' . $required . $multiple . $accept . ' ' . $attributes . '
                 />';
        return $label . $file;
    }
}
/*
 * Autor: MiCHo
 */
