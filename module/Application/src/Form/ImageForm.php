<?php
namespace Application\Form;

use Micho\Exception\ValidationException;
use Micho\Form\File;
use Micho\Form\Form;

class ImageForm
{
    private $form;

    /**
     * Vytvorenie formulára pre uloženie obrázka
     */
    public function __construct()
    {
        $this->form = new Form('upload-image');
        $this->form->addFile('Uloženie nových fotografií','image','','form-control-file w-auto','font-weight-bolder m-2','',true, File::IMAGE);
        $this->form->addSubmit('Save','save-button','','btn btn-sm btn-outline-primary ml-5');
    }

    /**
     ** Spracuje údaje fomulára
     * @return array|void Pole ošetrených dát
     * @throws \ErrorException
     */
    public function processForm() : array
    {
        if($this->form->dataProcesing())
        {
            $formData = array();
            try
            {
                $formData = $this->form->getData('image'); // získanie dát
                $this->form->validate($formData); // validácia
            }
            catch (ValidationException $error)
            {
                foreach ($error->getMessages() as $message)
                {
                    echo($message) . '<br>';
                }
                die;
            }
        }
        return $formData['image'];
    }

    /**
     * @return Form Getter formulára
     */
    public function getForm()
    {
        return $this->form;
    }
}

/*
 * Autor: MiCHo
 */

