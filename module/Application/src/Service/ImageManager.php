<?php
namespace Application\Service;

use Micho\Files\File;
use Micho\Image;

/**
 * Manažer správy obrázkov
 */
class ImageManager
{
    /**
     * @var string Cesta k uloženiu obrázka
     */
    private $saveToDir = './data/image/';

    /**
     ** Uloženie obrázkov
     * @param array $formData Formularové dáta obrázkov
     * @return void
     */
    public function saveImages(array $formData)
    {
        foreach ($formData['tmp_name'] as $key => $imagePath)
        {
            $image = new Image($imagePath);
            $image->save($this->saveToDir . 'big/' . $formData['name'][$key],$image->getImageType());

            $image->changeSizeToWidth(250);
            $image->save($this->saveToDir . 'small/' . $formData['name'][$key],$image->getImageType());
        }
    }

    /**
     ** Vymaže obrázok
     * @param string $imageName Názov obrázka, ktorý vymazujem
     * @return void
     */
    public function deleteImage(string $imageName)
    {
        $path = $this->getPathToDir() . 'big/' . $imageName;
        File::deleteFile($path);

        $path = $this->getPathToDir() . 'small/' . $imageName;
        File::deleteFile($path);
    }

    /**
     ** Vráti cestu k uloženým obrázkom
     * @return string cesta k obrázkom
     */
    public function getPathToDir()
    {
        return $this->saveToDir;
    }
}

/*
 * Autor: MiCHo
 */