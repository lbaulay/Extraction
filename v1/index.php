<!DOCTYPE html>
<html>
    <head>
        <title>
<?php
if (isset($_GET["file"])) {
    $URL = explode("/", $_GET['file']);
    $size = count($URL);
    $nomFichier = $URL[$size-2]." - ".$URL[$size-1];
    echo "Fichier '".$nomFichier."'";
} else {
    echo "Page d'accueil avec uniquement php";
}
?>

        </title>
        <link rel="stylesheet" type="text/css" href="newcss.css">
        <meta charset="UTF-8">
    </head>
    <body>

<?php

echo '<div id="date">';
// On crée l'arborescence
$URL = "../date"; // On part du repertoire /date et on recherche les sous dossiers
if ($dossier = opendir($URL)) {
    echo '<ul class="menu">';
    $lstDir = array(); // Liste des dossiers trouvé utiliser pour afficher les dossier dans l'ordre alphabetique
    while (false !== ($fichier = readdir($dossier))) {
        if ($fichier == '.' || $fichier == '..') {
            continue;
        }
        $urlfichier = $URL.'/'.$fichier;
        if (is_dir($urlfichier)) {
            $lstDir[]=$fichier;
        }
    }
    closedir($dossier);
    sort($lstDir);


    foreach ($lstDir as $fichier) { // On parcours la liste de dossier
        $urlfichier = $URL.'/'.$fichier;
        echo '<li class="arbo" id="li'.$fichier.'"><a href="#" onclick=\'clickDate("li'.$fichier.'")\'>'.$fichier.'</a>';
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
                echo '<li><a href="?file='.$urlfichier.'/'.$fichierCSV.
                        '">'.$fichierCSV.'</a></li>';
            }
            echo '</ul>';
            closedir($sousDossier);
        }
    }
    echo '</li>';
}
echo "</ul>";
echo '</div>';

// Partie visualisation
if (isset($_GET["file"])) {
    if (file_exists($_GET["file"])) {

        echo '<div class="affichageInde">';
        $fichier = $_GET["file"];
        $URL = explode("/", $fichier);
        $size = count($URL);
        $nomFichier = $URL[$size-2]." - ".$URL[$size-1];
        echo "<p> Ouvrir le fichier <a href='".$fichier."'>".$nomFichier."</a> </p>";
        // Lien permettant de télécharger le fichier
        $file = new SplFileObject($fichier, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        echo "<table class='tabCSV'>";
        $i = 0;
        $compteur = 0;
        foreach ($file as $ligne) {
            if (count($ligne)<2) { // Permet de supprimer la derniere ligne vide
                continue;
            }
            $i = ($i + 1)%2; // Permet d'alterner la couleur des lignes
            echo "<tr id='ligne".$i."'>";

            foreach ($ligne as $cellule) {
                echo "<td>".$cellule."</td>";
            }
            echo "</tr>";

        }
        echo "</table>";
        echo "</div>";

    } else {
        echo "<p>Ce fichier n'existe pas ou vous n'avez pas les droits d'acces.</p>";
    }
}

?>

    <script type="text/javascript" src="./extraction.js"></script>
    </body>





</html>
