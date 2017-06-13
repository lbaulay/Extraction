/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const URL = "../date";


function onClick(id){
    var elem = document.getElementById(id);

    if (elem.className == "arbo") { // Permet d'afficher ou masquer le contenu du dossier
        var ul = document.getElementById(id.slice(2,id.length)); // l'id de elem est le mot li accoler a l'id de la liste. pour obtenir l'id de la liste il faut supprimer le mots li de l'id
        ul.style.display == "block" ? ul.style.display = "none" : ul.style.display = "block";
    }
    
    else if (elem.className == "fichier") {
        /* Si tabCSV existe deja on le supprime avant d'afficher une nouvelle table*/
        if (document.getElementsByClassName("tabCSV").length >0) {
            document.getElementsByClassName("affichageInde")[0].removeChild(document.getElementsByClassName("tabCSV")[0]);
        }
        
        /* On creer le tableau de previsualisation */
        var parent = elem.parentNode.parentNode.id;
        Papa.parse(URL+"/"+id, {
            download: true,
            complete: function(results) {
                var div = document.getElementsByClassName("affichageInde")[0];
                var nomFichier = id.split("/")[1];
                var texte = 'Ouvrir le fichier <a href="'+URL+"/"+id+'">' + parent+' - '+nomFichier+'</a></p>';
                document.getElementById("titreAffichage").innerHTML = texte;
                var table = ArrayToTab(results.data);
                /* On ajoute le tableau au DOM, en enfant de <div affichageInde */
                
                div.appendChild(table);
            }
        });
        
    }
}


function ArrayToTab(arrayData) {
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