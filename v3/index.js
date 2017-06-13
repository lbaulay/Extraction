const URL = "../date";

$( function() {
    $("#accordion").accordion({
        header : '.arbo>a',
        heightStyle:"content",
        collapsible : true,
        active : false,
    });
});

$(".fichier").on("click",function(){
    var elem = $(this);
    var nomFichier = elem.data("nom-fichier");
    /* Si tabCSV existe deja on le supprime avant d'afficher une nouvelle table*/
    if (document.getElementsByClassName("tabCSV").length >0) {
        document.getElementsByClassName("affichageInde")[0].removeChild(document.getElementsByClassName("tabCSV")[0]);
    }
    /* On creer le tableau de previsualisation */
    var date = elem.attr("data-date");
    
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
function ArrayToTab(arrayData) {
    var table = document.createElement("table");
    table.setAttribute("class","tabCSV");
    var line = '<tr id="ligne1">';
    var numid = 1; // Pour alterner l'ID de la ligne, et donc alterner les couleurs
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

