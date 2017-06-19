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
define("PROFONDEUR_DOSSIER", 2);
include_once './Arborescence.php';

function debuggAlert($message)// TODO Fonction a retirer
{
    echo '<script type="text/javascript">alert("'.$message.'");</script>';
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
