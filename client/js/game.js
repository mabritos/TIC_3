function strUp() {
    var fuerza = document.getElementById("fue").innerHTML;
    document.getElementById("fue").innerHTML = parseInt(fuerza) + 2;
}

function agiUp() {
    var agilidad = document.getElementById("agi").innerHTML;
    document.getElementById("agi").innerHTML = parseInt(agilidad) + 2;
}

function intUp() {
    var inteligencia = document.getElementById("int").innerHTML;
    document.getElementById("int").innerHTML = parseInt(inteligencia) + 2;
}

function hpUp() {
    var vida = document.getElementById("pv").innerHTML;
    document.getElementById("pv").innerHTML = parseInt(vida) + 10;
}

function loadChar(){
    $.post( "/character/load", function(character) {
        console.log(character);
        $("#char-name").html(character.name);
    });
}

$(document).ready(function() {
    loadChar();
});