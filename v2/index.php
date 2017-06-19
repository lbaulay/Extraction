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
define("PROFONDEUR_DOSSIER", -1);

function debuggAlert($message)// TODO Fonction a retirer
{
    echo '<script type="text/javascript">alert("'.$message.'");</script>';
}
class Arborescence {
    public $nom;

    /**
     *
     * @var array
     */
    public $listElem;
    public $isDirectory;
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
        return $this->isDirectory;
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
        if (gettype($name)!= "string"){
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
        return $newElem;

        /* l'arborescence a la forme suivante :
         * [
         *      FileName,   [ (vide si FileName ne correspond pas a un dossier
         *                      [subFileName,  []],
         *                      [subFileName2, [
         *                                          [subSubFileName,    []]
         *                                     ]
         *                      ]
         * ]
         * l'arborescence est un objet "Element" ayant pour attribut, un string nom, une liste d'autre Element et un boolean disant si c'est un dossier (true) ou un fichier(false), où le premier élément correspond au nom du fichier/dossier
         * On peut utiliser la methode generateArborescence afin de la créer en HTML
         */
    }

    /**
     * Cette fonction permet de generer en HTML l'arborescence (sans le premier repertoire de l'arborescence) elle prend en argument $profondeur qui correspond jusqu'a quelle profondeur les dossiers sont affiché et extension qui permet d'afficher uniquement les fichier ayant l'extension demandé.
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
     * Cette fonction permet de generer en HTML l'arborescence (avec premier repertoire de l'arborescence) elle prend en argument $parentDir qui correspond au chemin du dossier parent du fichier/dossier actuel, $profondeur qui correspond jusqu'a quelle profondeur les dossier sont affiché et extension qui permet d'afficher uniquement les fichier ayant l'extension demandé.
     * @param string $parentDir
     * @param integer $profondeur
     * @param string $extension
     * @return void
     * @throws InvalidArgumentException
     */
    public function getArborescenceDOM($parentDir, $profondeur, $extension)
    {


        if (gettype($profondeur)!= "integer"){
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


$arborescence = Arborescence::parcoursRecursif(URL, PROFONDEUR_DOSSIER);
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
