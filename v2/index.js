/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const URL = "../date";

$(".arbo>a").on("click",function(){ // Role d'acoordeon
    var elem = $(this).parent().children('.sousDossier')[0];
    elem.style.display == "block" ? elem.style.display = "none" : elem.style.display = "block";
});

$(".fichier").on("click",function(){// Role d'affichage du fichier csv selectionné
    var elem = $(this);
    var nomFichier = elem.data("nom-fichier");
    var date = elem.data("date");
    if (document.getElementsByClassName("tabCSV").length >0) {
        document.getElementsByClassName("affichageInde")[0].removeChild(document.getElementsByClassName("tabCSV")[0]);
    }

    /* On creer le tableau de previsualisation */
    Papa.parse(URL+"/"+date+"/"+nomFichier, {
        download: true,
        complete: function(results) {
            var div = document.getElementsByClassName("affichageInde")[0];
            var texte = 'Ouvrir le fichier <a href="'+URL+"/"+date+"/"+nomFichier+'">' + date+' - '+nomFichier+'</a></p>';
            document.getElementById("titreAffichage").innerHTML = texte;
            var table = ArrayToTab(results.data);
            /* On ajoute le tableau au DOM, en enfant de <div affichageInde */

            div.appendChild(table);
        }
    });
});

$(".masquerAccordeon").on("click",function(){
    if($(this).attr("status")==="off"){
        $(".menu").css('display', "none");
        $(this).attr("status","on");
        $(this).innerHTML = ">>";
    } else {
        console.log("ok");
        $(".menu").css('display', "block");
        $(this).attr("status","off");
        $(this).innerHTML = ">>";
    }
});


function ArrayToTab(arrayData) { // Prend en argument un Array avec les valeurs CSV et le convertie en tableau HTML
    var table = document.createElement("table");
    table.setAttribute("class","tabCSV");
    var line = '<tr id="ligne1">';
    var numid = 1; // POur alterner l'ID de la ligne, et donc alterner les couleurs
    var i;
    for(var i=0;i<arrayData.length-1;i++){
        for(var j=0; j<arrayData[i].length;j++){
            line += "<td>"+arrayData[i][j]+"</td>"; // Ecriture des cellules
        }
        numid = (numid+1)%2;
        if (i<arrayData.length-2) { // si ce n'est pas la derniere ligne
            line += "</tr><tr id=ligne"+numid+">";
        } else {
            line += "</tr>";
        }
    }
    table.innerHTML += line;
    return table;
    
}