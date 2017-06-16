<!DOCTYPE html>
<html>
    <head>
        <title>
            Page d'accueil avec Javascript
        </title>
        <link rel="stylesheet" type="text/css" href="newcss.css">
        <script type="text/javascript" src="../jquery-3.2.1.js"></script>
        <script type="text/javascript" src="../jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="../jquery.tablesorter.widgets.min.js"></script>
        <meta charset="UTF-8">
    </head>
    <body>

        <div id="date">
            <button class="masquerAccordeon" status="off">Masquer le menu</button>
            <ul class="menu">
<?php
define("URL", "../date"); // On part du repertoire /date et on recherche les sous dossiers
define("PROFONDEUR_DOSSIER", 3);

function debuggAlert($message)// TODO Fonction a retirer
{
    echo '<script type="text/javascript">alert("'.$message.'");</script>';
}
class Element {
    public $nom;
    public $listElem;
    public $directory;
    function __construct($name, $listElem, $directory)
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
        return $this->directory;
    }
    public function isFile()
    {
        if($this->isDirectory()) {
            return false;
        }
        return true;
    }
    public function setName($name)
    {
        $this->nom = $name;
    }
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }
    public function setListElem($listElem)
    {
        $this->listElem = $listElem;
    }




    public static function parcoursRecursif($dir, $profondeur)
    {
        $elem = array();

        foreach (new DirectoryIterator($dir) as $repertoire) {

            if ($repertoire->isDot()) {
                continue;
            }
            if ($repertoire->isDir()) {
                if ($profondeur>0) {
                    $newdir = $dir."/".$repertoire->getFilename();

                    array_push($elem, Element::parcoursRecursif($newdir, $profondeur-1));
                }
            } else {
                $elemFichier = new Element($repertoire->getFilename(), array(), false);
                array_push($elem, $elemFichier);
            }

        }
        sort($elem);
        $newElem = new Element($dir, $elem, true);
        return $newElem;

        /* l'arborescence a la forme suivante :
         * [
         *      FileName,   [ (vide si FileName ne correspond pas a un fichier
         *                      [subFileName,  []],
         *                      [subFileName2, [
         *                                          [subSubFileName,    []]
         *                                     ]
         *                      ]
         * ]
         * l'arborescence est une liste de longueur deux, où le premier élément correspond au nom du fichier/dossier
         * et le deuxieme élément correspond à une liste d'arborescence, si celle ci est de longueur 0, alors l'élément est un dossier
         */
    }
    public function generateArborescenceDOM($profondeur, $extension = "") // Prend la racine de l'arborescence
    {
        $element = $this->getListElem();
        $nbElement = count($element);
        for ($i = 0; $i<$nbElement; $i++) {
            $element[$i]->getArborescenceDOM($this->getName(), $profondeur, $extension);
        }
    }
    public function getArborescenceDOM($parentDir, $profondeur, $extension) // affiche l'arborescence sans la racine
    {


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
            if ($profondeur>=0) :?>
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


$arborescence = Element::parcoursRecursif(URL, PROFONDEUR_DOSSIER);
//$arborescence->generateArborescenceDOM(PROFONDEUR_DOSSIER); // Permet de creer une arborescence de profondeur = PROFONDEUR_DOSSIER a partir de la racine
$arborescence->generateArborescenceDOM(PROFONDEUR_DOSSIER, ".csv"); //Permet de ne lister que les fichiers .csv et les repertoires de profondeur PROFONDEUR_DOSSIER a partir de la racine
?>



            </ul>
        </div>
        <div class="affichageInde"><p id="titreAffichage"></p></div>


        <script type="text/javascript" src="./index.js"></script>

        <script type="text/javascript" src="../papaparse.min.js"></script>
    </body>
</html>
