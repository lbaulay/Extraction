/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function clickDate(liID){
    var ul = document.getElementById(liID.slice(2,liID.length));
    console.log(ul.style.display);
    if(ul.style.display == "block"){
        ul.style.display = "none";
    } else {
        ul.style.display = "block";
    }
}