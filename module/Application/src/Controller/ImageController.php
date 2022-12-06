<?php
namespace Application\Controller;


use Application\Form\ImageForm;
use Application\Service\ImageManager;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Micho\Files\File;

/**
 * Kontrolér pre správu Obrázkov (zobrazenie, nahravanie, mazanie)
 */
class ImageController extends AbstractActionController
{
    /**
     * @var ImageManager Objekt pre pŕacu z formulárom
     */
    private ImageManager $imageManager;

    /**
     * @var object Formulár pre uloženie obrázkov
     */
    private ImageForm $imageForm;

    /**
     * Konštruktor zostavenia závislosti a formulára
     */
    public function __construct()
    {
        $this->imageManager = new ImageManager();
        $this->imageForm = new ImageForm();
    }

    /**
     ** Zobrazenie a pridanie nových fotografií
     * @return ViewModel Pohľad
     */
    public function indexAction() : viewModel
    {
        $images =  File::returnFileNames($this->imageManager->getPathToDir() . '/small');

        // Get the list of already saved files.
        // Render the view template.
        return new ViewModel([
            'form'=> $this->imageForm->getForm()->createForm(),
            'imagePath' => $this->imageManager->getPathToDir(),
            'images' => $images,
        ]);
    }

    /**
     ** Uloženie nových obrázkov
     * @return \Laminas\Http\Response
     * @throws \ErrorException
     */
    public function uploadAction() : Response
    {
        $formData = $this->imageForm->processForm(); // spracovanie formulára

        $this->imageManager->saveImages($formData); // uloženie obrázkov

        return $this->redirect()->toRoute('images'); // presmerovnaie
    }

    /**
     ** Vymazanie Obrázka
     * @return \Laminas\Http\Response
     */
    public function deleteAction() : Response
    {
        $name = $this->params()->fromRoute('name');

        $this->imageManager->deleteImage($name);
        return $this->redirect()->toRoute('images'); // presmerovnaie
    }
}

/*
 * Autor: MiCHo
 */