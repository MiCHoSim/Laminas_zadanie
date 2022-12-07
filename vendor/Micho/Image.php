<?php


namespace Micho;

use GdImage;

/**
 ** Trieda pre prácu z obrázkom
 * Class Obrazok
 * @package Micho
 */
class Image
{
    /**
     * Obrázok typu PNG
     */
    const IMAGETYPE_PNG = IMAGETYPE_PNG;
    /**
     * Obrázok typu GIF
     */
    const IMAGETYPE_GIF = IMAGETYPE_GIF;
    /**
     * Obrázok typu JPEG
     */
    const IMAGETYPE_JPEG = IMAGETYPE_JPEG;

    /**
     * @var resource Načítaný Obrázok
     */
    private $image;
    /**
     * @var int Typ obrázka
     */
    private $imageType;
    /**
     * @var int Šírka obrázka v pixeloch
     */
    private $width;
    /**
     * @var int Výška obrázka v pixelech
     */
    private $height;
    
    /**
     * Obrazok constructor.
     * @param string $pathImage Cesta k súboru, z kterého sa má obrázok načítať
     */
    public function __construct(string $pathImage)
    {
        $imageData = explode(',', $pathImage);
        // ak je "cesta" zadana ako base 64 tak ho tak aj spracujem
        if(mb_strpos($imageData[0],'base64') !== false)
        {
            $base64 = $imageData[1]; // ziskanie čisto base64
            $image = base64_decode($base64); // dekodovanie

            $this->image = imagecreatefromstring($image); // nacitanie obrazka ako objekt GD

            $imageSize = getimagesizefromstring($image);
            $this->width = $imageSize[0];
            $this->height = $imageSize[1];
            $this->imageType = $imageSize[2];
        }
        else
        {
            $imageSize = getimagesize($pathImage);
            $this->width = $imageSize[0];
            $this->height = $imageSize[1];
            $this->imageType = $imageSize[2];

            if($this->imageType == self::IMAGETYPE_JPEG)
                $this->image = imagecreatefromjpeg($pathImage);

            elseif ($this->imageType == self::IMAGETYPE_GIF)
            {
                $image = imagecreatefromgif($pathImage);
                $this->image = $this->createBackground($this->width, $this->height, true);
                imagecopy($this->image, $image, 0,0,0,0, $this->width, $this->height);
                imagedestroy($image);
            }
            elseif ($this->imageType == self::IMAGETYPE_PNG)
            {
                $this->image = imagecreatefrompng($pathImage);
                imagealphablending($this->image,true);  // zapnutie alfakanalu
                imagesavealpha($this->image,true); // ulozenie alfakanalu
            }
        }
    }
    
    /**
     ** Zmeni rozmery obrázka
     * @param int $width Šírka obrázka
     * @param int $height Výška obrázka
     */
    public function changeSize(int $width, int $height)
    {
        $image = $this->createBackground($width, $height, true);
        imagecopyresampled($image, $this->image,0,0,0,0,$width, $height, $this->width, $this->height);
        $this->image = $image;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     ** Zmení rozmer obrázka vzhľadom na požadovanú šírku
     * @param int $width Šírka obrázka
     */
    public function changeSizeToWidth($width)
    {
        $ratio = $width / $this->width;
        $height = $this->height * $ratio;
        $this->changeSize($width, $height);
    }

    /**
     ** Uloži obrázok do súboru pre rôzne formáty
     * @param string $imageName Cesta a názov uloŽeného obrázka
     * @param int $imageType Typ obrázka
     * @param int $compression Kompresia Kvalita obrázka pre typ JPEG
     * @param bool $transparent Či chem  priesvitnosť pre typ GIF
     * @param null $permissions Možnost pridelenia práv pre nový súbor
     */
    public function save($imageName, $imageType = self::IMAGETYPE_JPEG, $compression = 85, $transparent = true, $permissions = null)
    {
        if ($imageType == self::IMAGETYPE_JPEG)
        {
            $output = $this->createBackground($this->width, $this->height, false);
            imagecopy($output, $this->image,0,0,0,0,$this->width, $this->height);
            imagejpeg($output, $imageName, $compression);
            //imagedestroy($vystup);
        }
        elseif ($imageType == self::IMAGETYPE_GIF)
        {
            $image = $this->createBackground($this->width, $this->height, true);
            if ($transparent)
            {
                $color = imagecolorallocatealpha($image,0,0,0,127);
                imagecolortransparent($image, $color);
            }
            imagecopyresampled($image, $this->image,0,0,0,0,$this->width, $this->height, $this->width, $this->height);
            imagegif($image, $imageName);
            //imagedestroy($obrazok);
        }
        elseif ($imageType == self::IMAGETYPE_PNG)
            imagepng($this->image, $imageName);
        if ($permissions != null)
            chmod($imageName, $permissions);
    }
    
    /**
     ** Vytvorí pozadie obrázka
     * @param int $width šírka pozadia
     * @param int $height výška pozadia
     * @param bool $transparent Či chem priesvitné pozadie
     * @return false|\GdImage|resource Vytvorené pozadie
     */
    private function createBackground($width, $height, $transparent = true) : GdImage
    {
        $image = imagecreatetruecolor($width, $height);
        if ($transparent)
        {
            imagealphablending($image, true);
            $color = imagecolorallocatealpha($image,0,0,0, 127);
        }
        else
            $color = imagecolorallocate($image,255,255,255);
        imagefill($image,0,0, $color);
        if ($transparent)
            imagesavealpha($image, true);
        return $image;
    }

    /**
     ** Vráti typ obrázka
     * @return mixed
     */
    public function getImageType()
    {
        return $this->imageType;
    }
}

/*
 * Autor: MiCHo
 */