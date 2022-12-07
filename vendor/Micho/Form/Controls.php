<?php

namespace Micho\Form;

use App\BaseModul\System\Controller\RouterController;

/**
 ** Kontrolkový predok pre kontorlky
 * Class Controls
 * @package Micho\Form
 */
abstract class Controls
{
    /**
     ** Zakladné názvy konttrolky na úpravu
     */
    const NAME_DB = 'nameDb';
    const FORM = 'form';
    const ATR_CLAS = 'atrClas';
    const REQUIRED = 'required';

    /**
     * @var string Parametre potrebné pre zostavenie Kontrolky
     */
    protected $nameDb;
    protected $form;
    protected $atrClas;
    protected $required;
    protected $attributes;

    /**
     * Kontrolky constructor.
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function __construct($nameDb, $form, $atrClas, $required, $attributes)
    {
        $this->nameDb = $nameDb;
        $this->form = $form;
        $this->atrClas = $atrClas;
        $this->required = $required;
        $this->attributes = $attributes;
    }

    /**
     * @return string Vytvorí HTML kontrolku
     */
    public abstract function createControl();

    /**
     ** Upravý parametre kontrolky
     * @param array $parameters Pole kde klúče su názvy parametrov a hodnoty sú nové hodnoty parametrov
     */
    public function editParameters(array $parameters)
    {
        $routerController = new RouterController();
        foreach ($parameters as $parameter => $value)
        {
            $this->$parameter = $routerController->checkData($value);
        }
    }

    /**
     ** Vráti Hodnotu požadovaného parametru
     * @return string Názov kontrolky
     */
    public function returnParameter($parameter)
    {
        return $this->$parameter;
    }
}
/*
 * Autor: MiCHo
 */
