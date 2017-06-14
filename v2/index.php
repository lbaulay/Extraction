<!DOCTYPE html>
<html>
    <head>
        <title>
            Page d'accueil avec Javascript
        </title>
        <link rel="stylesheet" type="text/css" href="newcss.css">
        <script type="text/javascript" src="../jquery-3.2.1.js"></script>
        <meta charset="UTF-8">
    </head>
    <body>

        <div id="date">
<?php
define("URL", "../date"); // On part du repertoire /date et on recherche les sous dossiers
// On crée l'arborescence
if ($dossier = opendir(URL)) :?>
    <ul class="menu">
<?php
    $lstDir = array(); // Liste des dossiers trouvé utiliser pour afficher les dossier dans l'ordre alphabetique
    while (false !== ($fichier = readdir($dossier))) {
        if ($fichier == '.' || $fichier == '..') {
            continue;
        }
        $urlfichier = URL . '/' . $fichier;
        if (is_dir($urlfichier)) {
            $lstDir[] = $fichier;
        }
    }
    closedir($dossier);
    sort($lstDir);


    foreach ($lstDir as $dossierDate) : // On parcours la liste de dossier
        $urlfichier = URL . '/' . $dossierDate;
?>
        <li class="arbo"><a href="#"><?=$dossierDate?></a>
<?php
        if ($sousDossier = opendir($urlfichier)) :
            $listeFichierCSV = [];
            // On ouvre les sous dossier et on affiche les fichiers qui y sont présent
            ?>
            <ul class="sousDossier">
<?php
            while (false !== ($fichierExtrait = readdir($sousDossier))) {
                // on ne liste que les fichiers .csv
                if (is_file($urlfichier . '/' . $fichierExtrait) && "csv" === substr($fichierExtrait, -3)) {
                    $listeFichierCSV[] = $fichierExtrait;
                }
            }
            sort($listeFichierCSV);
            foreach ($listeFichierCSV as $fichierCSV) : ?>
                <li><a class="fichier" data-date="<?=$dossierDate?>" data-nom-fichier="<?=$fichierCSV?>"><?=$fichierCSV?></a></li>
<?php
            endforeach;
?>
            </ul>
<?php
            closedir($sousDossier);
        endif;
    endforeach;
?>
        </li>
<?php
endif;
echo "</ul>";
?>
        </div>
        <div class="affichageInde"><p id="titreAffichage"></p></div>


        <script type="text/javascript" src="./index.js"></script>

        <script type="text/javascript" src="../papaparse.min.js"></script>
    </body>
</html>
