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
            <button class="masquerAccordeon" status="off">Masquer le menu</button>
<?php
define("URL", "../date"); // On part du repertoire /date et on recherche les sous dossiers
define("PROFONDEUR_DOSSIER", 3);

function debuggAlert($message)// TODO Fonction a retirer
{
    echo '<script type="text/javascript">alert("'.$message.'");</script>';
}
function parcoursRecursif($dir, $profondeur)
{
    $elem = array();
    $lstReturn = array();

    foreach (new DirectoryIterator($dir) as $repertoire) {

        if ($repertoire->isDot()) {
            continue;
        }
        if ($repertoire->isDir()) {
            array_push($elem, parcoursRecursif($dir."/".$repertoire->getFilename(), $profondeur-1));
        } else {
            array_push($elem, $repertoire->getFilename());
        }

    }
    sort($elem);
    array_merge($lstReturn, $elem);
    return $lstReturn;
}

$arborescence = parcoursRecursif(URL, PROFONDEUR_DOSSIER);


?>
        </div>
        <div class="affichageInde"><p id="titreAffichage"></p></div>


        <script type="text/javascript" src="./index.js"></script>

        <script type="text/javascript" src="../papaparse.min.js"></script>
    </body>
</html>
