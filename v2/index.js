/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const URL = "../date";

window.onload = function(){ // lors de l'ouverture de la page, ouvre le premier volet de l'accordeon    
    if($(".arbo").length>0){
        $(".arbo:first>.sousDossier")[0].style.display = "block";
    }
};


$(".arbo>a").on("click", function () { // Role d'accordeon   
    var profondeur =$(this).parent().attr("class").split(' ')[1];
    reduireAll(profondeur); // On reduit le(s) onglets deplié
    var elem = $(this).parent().children('.sousDossier')[0];
    elem.style.display = "block"; // On deplie l'onglet selectionné
});

function reduireAll(profondeur){
    for (var i=0;i<$(".arbo."+profondeur).length;i++){
        var onglet = $(".arbo."+profondeur).children('.sousDossier');
        onglet[i].style.display = "none";
    }
}
$(".fichier").on("click", function () {// Role d'affichage du fichier csv selectionné
    var elem = $(this);
    var nomFichier = elem.data("nom-fichier");
    var parentDir = elem.data("parent-directory");
    if (document.getElementsByClassName("tabCSV").length > 0) {
        document.getElementsByClassName("affichageInde")[0].removeChild(document.getElementsByClassName("tabCSV")[0]);
    }

    /* On creer le tableau de previsualisation */
    Papa.parse(parentDir + "/" + nomFichier, {
        download: true,
        complete: function (results) {
            var div = document.getElementsByClassName("affichageInde")[0];
            var texte = 'Ouvrir le fichier <a href="' +parentDir + "/" + nomFichier + '">' + parentDir + ' - ' + nomFichier + '</a></p>';
            document.getElementById("titreAffichage").innerHTML = texte;
            var table = ArrayToTab(results.data);
            /* On ajoute le tableau au DOM, en enfant de <div affichageInde */
            div.appendChild(table);
            $(".tabCSV").tablesorter({
               theme : 'blue',
               sortInitialOrder: 'desc',
               headerTemplate : '{content}{icon}',
               
            }); 
            
        }
    });
});



$(".masquerAccordeon").on("click", function () { // Role d'affichage du menu si le bouton est cliqué
    if ($(this).attr("status") === "off") {// Si le menu est affiché, on augmente la taille du div contenant le tableau de visualisation CSV, on masque le menu et on change le titre du bouton
        fullTab();
        $(".menu").css('display', "none");
        $(this).attr("status", "on");
        this.innerText = "Afficher le menu";
    } else { // Si le menu est masqué, on le ré-affiche, on réduit la taille du div contenant le tableau de visualisation CSV et on change le titre du bouton
        reductionTab();
        $(".menu").css('display', "block");
        $(this).attr("status", "off");
        this.innerText = "Masquer le menu";
    }
});


function fullTab() {
    $(".affichageInde").css("width", "auto");
}
function reductionTab() {
    $(".affichageInde").css("width", "70%");
}



/**
 * 
 * @param {array} arrayData
 * @returns {Element|ArrayToTab.table}
 */
function ArrayToTab(arrayData) { // Prend en argument un Array avec les valeurs CSV et le convertie en tableau HTML
    var table = document.createElement("table");
    table.setAttribute("class", "tabCSV tablesorter");
    var line = '<thead><tr id="ligne1">';
    var isHeader = true;// si on traite la premiere ligne
    var numid = 1; // Pour alterner l'ID de la ligne, et donc alterner les couleurs
    var i;
    for (var i = 0; i < arrayData.length - 1; i++) {
        for (var j = 0; j < arrayData[i].length; j++) {
            isHeader ? line += "<th>" + arrayData[i][j] + "</th>" : line += "<td>" + arrayData[i][j] + "</td>"; // Ecriture des cellules
        }
        numid = (numid + 1) % 2;
        if (isHeader)
        {
            i < arrayData.length - 2 ? line += "</tr></thead><tbody><tr id=ligne" + numid + ">" : line += "</tr></thead>";
            isHeader = false;
        } else {
            i < arrayData.length - 2 ? line += "</tr><tr id=ligne" + numid + ">" : line += "</tr></tbody>";
        }
    }
    table.innerHTML += line;
    return table;
}

