<html>
    <head>
        <link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.min.css">
        <link rel="stylesheet" href="./index.css">
        <script src="../jquery-ui-1.12.1/external/jquery/jquery.js"></script>
        <script src="../jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <title>Page d'accueil avec Jquery UI</title>
    </head>
    <body>
        <div id="date">
            <?php
            define("URL", "../date"); // On part du repertoire /date et on recherche les sous dossiers
// On crée l'arborescence
            if ($dossier = opendir(URL)) :
                ?>
                <ul id="accordion">
                <?php
                    $lstDir = array(); // Liste des dossiers trouvé utiliser pour afficher les dossier dans l'ordre alphabetique
                    while (false !== ($fichier = readdir($dossier))) :
                        if ($fichier == '.' || $fichier == '..') {
                            continue;
                        }
                        $urlfichier = URL . '/' . $fichier;
                        if (is_dir($urlfichier)) {
                            $lstDir[] = $fichier;
                        }
                    endwhile;
                    closedir($dossier);
                    sort($lstDir);

                    foreach ($lstDir as $RepDate) : // On parcours la liste de dossier
                        $urlfichier = URL . '/' . $RepDate;
                        ?>
                        <li class="arbo" id="li<?= $RepDate ?>"><a href="#"><?= $RepDate ?></a>
                            <?php
                            if ($sousDossier = opendir($urlfichier)) :
                                $listeFichierCSV = []; // On ouvre les sous dossier et on affiche les fichiers qui y sont présent
                                ?>


                                <ul class="sousDossier" id="<?= $RepDate ?>">
                                    <?php
                                    while (false !== ($fichierExtrait = readdir($sousDossier))) {
                                        // on ne liste que les fichiers .csv

                                        if (is_file($urlfichier . '/' . $fichierExtrait) && "csv" === substr($fichierExtrait, -3)) {
                                            $listeFichierCSV[] = $fichierExtrait;
                                        }
                                    }
                                    foreach ($listeFichierCSV as $fichierCSV) :
                                        ?>

                                        <li><a class="fichier" data-date="<?=$RepDate?>" data-nom-fichier="<?=$fichierCSV?>"><?= $fichierCSV ?></a></li>
                                <?php endforeach; ?>
                                </ul>
            <?php
            closedir($sousDossier);
        endif;
    endforeach;
    ?>

                    </li>
<?php endif; ?>

            </ul>
        </div>
        <div class="affichageInde"><p id="titreAffichage"></p></div>
        <script src="./index.js"></script>
        <script type="text/javascript" src="../papaparse.min.js"></script>
    </body>
</html>