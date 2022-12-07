<?php

namespace Micho\Files;


/**
 ** Tríeda služiaca na správu Súborov
 * Class File
 * @package Micho
 */
class File
{
    /**
     ** Zisti názvy súborov a vráti ich
     * @param string $path Cesta k súboru
     * @return array|false Načitané názvy súborov
     */
    public static function returnFileNames(string $path) : array|false
    {
        $folder = scandir($path);
        array_shift($folder);
        array_shift($folder);
        return $folder;
    }

    /**
     ** Vymaže súbor
     * @param string $pathToFile Cesta k súboru
     * @return void
     */
    public static function deleteFile(string $pathToFile)
    {
        if (file_exists($pathToFile))
            unlink($pathToFile);
    }
}
/*
 * Autor: MiCHo
 */