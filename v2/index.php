<!DOCTYPE html>
<html>
    <head>
        <title>
            Page d'accueil avec Javascript
        </title>
        <link rel="stylesheet" type="text/css" href="newcss.css">
        <meta charset="UTF-8">
    </head>
    <body>

<?php
define("URL", "../date"); // On part du repertoire /date et on recherche les sous dossiers
echo '<div id="date">';
// On crée l'arborescence
if ($dossier = opendir(URL)) {
    echo '<ul class="menu">';
    $lstDir = array(); // Liste des dossiers trouvé utiliser pour afficher les dossier dans l'ordre alphabetique
    while (false !== ($fichier = readdir($dossier))) {
        if ($fichier == '.' || $fichier == '..') {
            continue;
        }
        $urlfichier = URL.'/'.$fichier;
        if (is_dir($urlfichier)) {
            $lstDir[]=$fichier;
        }
    }
    closedir($dossier);
    sort($lstDir);


    foreach ($lstDir as $fichier) { // On parcours la liste de dossier
        $urlfichier = URL.'/'.$fichier;
        echo '<li class="arbo" id="li'.$fichier.'"><a href="#" onclick=\'onClick("li'.$fichier.'")\'>'.$fichier.'</a>';
        if ($sousDossier = opendir($urlfichier)) {
            $listeFichierCSV = [];
            // On ouvre les sous dossier et on affiche les fichiers qui y sont présent
            echo '<ul class="sousDossier" id="'.$fichier.'">';
            while (false !==($fichierExtrait = readdir($sousDossier))) {
                // on ne liste que les fichiers .csv

                if (is_file($urlfichier.'/'.$fichierExtrait) && "csv" === substr($fichierExtrait, -3)) {
                    $listeFichierCSV[]=$fichierExtrait;
                }
            }
            foreach ($listeFichierCSV as $fichierCSV) {
                echo '<li><a class="fichier" id ="'.$fichier."/".$fichierCSV.'" onclick ="onClick(\''.$fichier."/".$fichierCSV.'\')">'.$fichierCSV.'</a></li>';
            }
            echo '</ul>';
            closedir($sousDossier);
        }
    }
    echo '</li>';
}
echo "</ul>";
echo '</div>';
?>

        <div class="affichageInde"><p id="titreAffichage"></p></div>


        <script type="text/javascript" src="./index.js"></script>
        <script type="text/javascript" src="../papaparse.min.js"></script>
    </body>
</html>
