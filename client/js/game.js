var loadedCharacter;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


async function printFight(gameLog, character) {
    for (let index = 0; index < gameLog.length; index++) {
        const element = gameLog[index];
        $("#gameLog").html($("#gameLog").html() + element + "<br>");
        $("#gameLog").scrollTop(250);
        await sleep(1000);
    }
    if (character)
        printCharacter(character);
}

function printCharacter(character) {
    $("#characterName").html(character.name);
    $("#characterXp")// TO DO
    $("#characterGold").html(character.gold).fadeIn();
    $("#characterHp").html(character.hp);
    $("#characterStrength").html(character.strength);
    $("#characterAgility").html(character.agility);
    $("#characterIntelligence").html(character.intelligence);
    $("#characterWeapon");// TO DO
    $("#characterArmor");// TO DO
}

function characterLoad(character = null) {
    if (character === null)
        $.post("/character/load", function (character) {
            printCharacter(character);
        });
    else
        printCharacter(character);

}

function fight() {
    let zone = $("#zoneSelector option:selected").text();
    if (zone != 'Choose...')
        $.post("/character/attack", { 'zone': zone }, function (data) {
            printFight(data.gameLog, data.character);
        });
    else
        printFight(["You must choose a zone in order to fight..."]);
}

$(function () {
    characterLoad();
});