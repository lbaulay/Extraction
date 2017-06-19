<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Arborescence
 *
 * @author lbaulay
 */
class Arborescence {

    /**
     * Nom du fichier/dossier
     * @var string
     */
    public $nom;

    /**
     * Liste d'objet Arborescence
     * @var array
     */
    public $listElem;

    /**
     * Boolean valant true si isDirectory est un dossier et false sinon
     * @var boolean
     */
    public $isDirectory;


    public function __construct($name, $listElem, $directory)
    {
        $this->setName($name);
        $this->setListElem($listElem);
        $this->setDirectory($directory);
    }
    public function getName()
    {

        return $this->nom;
    }
    public function getListElem()
    {
        return $this->listElem;
    }

    public function isDirectory()
    {
        return $this->isDirectory;
    }

    public function isFile()
    {
        if ($this->isDirectory()) {
            return false;
        }
        return true;
    }

    public function setName($name)
    {
        if (gettype($name)!= "string") {
            throw new InvalidArgumentException;
        }
        $this->nom = $name;
    }

    public function setDirectory($directory)
    {
        $this->isDirectory = $directory;
    }

    public function setListElem($listElem)
    {
        $this->listElem = $listElem;
    }



    /**
     * Cette fonction permet de creer un objet Arborescence representant l'arborescence du chemin $dir passé en argument, $profondeur correspond a quelle profondeur les dossiers sont affiché
     * @param string $dir
     * @param integer $profondeur
     * @return \Arborescence
     * @throws InvalidArgumentException
     */
    public static function parcoursRecursif($dir, $profondeur)
    {
        $elem = array();
        if (gettype($profondeur)!= "integer") {
               throw new InvalidArgumentException;
        }

        foreach (new DirectoryIterator($dir) as $repertoire) {

            if ($repertoire->isDot()) {
                continue;
            }
            if ($repertoire->isDir()) {
                if ($profondeur!=0) {
                    $newdir = $dir."/".$repertoire->getFilename();

                    array_push($elem, Arborescence::parcoursRecursif($newdir, $profondeur-1));
                }
            } else {
                $elemFichier = new Arborescence($repertoire->getFilename(), array(), false);
                array_push($elem, $elemFichier);
            }

        }
        sort($elem);
        $newElem = new Arborescence($dir, $elem, true);
        return $newElem; // Objet Arborescence
    }

    /**
     * Cette fonction permet de generer en HTML l'arborescence (sans le premier repertoire de l'arborescence) elle prend en argument $profondeur qui correspond jusqu'a quelle profondeur les dossiers sont affiché et extension qui permet d'afficher uniquement les fichier ayant l'extension demandé. Cette fonction est a utiliser dans une balises ul
     * @param integer $profondeur
     * @param string $extension
     * @throws InvalidArgumentException
     */
    public function generateArborescenceDOM($profondeur, $extension = "")
    {
        if (gettype($profondeur)!= "integer") {
               throw new InvalidArgumentException;
        }
        $element = $this->getListElem();
        $nbElement = count($element);
        for ($i = 0; $i<$nbElement; $i++) {
            $element[$i]->getArborescenceDOM($this->getName(), $profondeur, $extension);
        }
    }


    /**
     * Cette fonction permet de generer en HTML l'arborescence (avec premier repertoire de l'arborescence) elle prend en argument $parentDir qui correspond au chemin du dossier parent du fichier/dossier actuel, $profondeur qui correspond jusqu'a quelle profondeur les dossier sont affiché et extension qui permet d'afficher uniquement les fichier ayant l'extension demandé. Cette fonction est a utiliser dans une balises ul
     * @param string $parentDir
     * @param integer $profondeur
     * @param string $extension
     * @return void
     * @throws InvalidArgumentException
     */
    public function getArborescenceDOM($parentDir, $profondeur, $extension)
    {


        if (gettype($profondeur)!= "integer") {
               throw new InvalidArgumentException;
        }
        $nomFichier = str_replace($parentDir."/", "", $this->getName());
        $element = $this->getListElem();
        $nbElement = count($element);
        if ($this->isFile()) :
            if (($extension !="" && $extension == substr($nomFichier, -strlen($extension))) || $extension==="") :

                ?>

                <li>
                    <a class="fichier" data-parent-directory="<?= $parentDir?>" data-nom-fichier="<?= str_replace($parentDir."/", "", $nomFichier)?>"href="#">
                        <?= str_replace($parentDir."/", "", $nomFichier);?>
                    </a>
                </li>
            <?php
            endif;
            return;
        else :
            if ($profondeur!=0) :?>
                <li class="arbo <?=$profondeur?>">
                    <a href="#"><?= str_replace($parentDir."/", "", $nomFichier) ?></a>
                    <ul class="sousDossier">
            <?php
                for ($i = 0; $i<$nbElement; $i++) {

                    $element[$i]->getArborescenceDOM($parentDir."/".$nomFichier, $profondeur-1, $extension);

                }?>
                    </ul>
                </li><?php
            endif;
        endif;
        return;
    }
}