

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


async function printFight(gameLog, character, enemy, loot) {
    $("#gameImage").attr("src", "/client/img/enemies/"+enemy.icon);

    for (let index = 0; index < gameLog.length; index++) {
        const element = gameLog[index];
        $("#gameLog").html($("#gameLog").html() + element + "<br>");
        $("#gameLog").scrollTop(250);
        await sleep(1000);
    }
    if (character)
        printCharacter(character);
    if (loot)
        printLoot(loot);
}

function printLoot(loot) {
    //TO DO
}

function printXp(xp) {
    let xpTop = getXpTop(xp);
    current_progress = xp/xpTop*100;
    $(".progress-bar").css("width", current_progress + "%").attr("aria-valuenow", current_progress)
    $(".progress").notify(xp + '/' + xpTop, {
      style: 'rpg', className: "xp", position: "right"
    });
}

function getXpTop(xp) {
    let xpLevels = [100, 250, 500, 1000, 2500]
    xpLevels.forEach(xpTop => {
        console.log(xp < xpTop)
        if (xp < xpTop) {
            return xpTop;
        }
    });
}

function printCharacter(character, items) {
    $("#characterName").html(character.name);
    $("#characterXp")// TO DO
    $("#characterGold").html(character.gold).fadeIn();
    $("#characterHp").html(character.hp);
    $("#characterStrength").html(character.strength);
    $("#characterAgility").html(character.agility);
    $("#characterIntelligence").html(character.intelligence);
    $("#characterArmor").attr("src", "/client/img/items/"+items.armor.icon);
    $("#characterWeapon").attr("src", "/client/img/items/"+items.weapon.icon);
    $("#characterArmorName").html(items.armor.name);
    $("#characterWeaponName").html(items.weapon.name);
    /*
    $.post("/character/getItem", function(items) {
        $("#characterWeaponName").html(items.Armor.name);
        $("#characterArmorName").html(items.Weapon.name);
        $("#characterArmor").attr("src","/client/img/items/" + items.Armor.icon); //no estoy seguro que se pueda escribir la url de la img asi
        $("#characterWeapon").attr("src","/client/img/items/" + items.Weapon.icon);
    });
     */
}

function characterLoad(character = null) {
    if (character === null)
        $.post("/character/load", function (character) {
            $.post('/character/getItems',{"character": character} , function(items){
                printCharacter(character,items);
            });

        });
    else {
        $.post('/character/getItems',{"character": character} , function(items){
            printCharacter(character,items);
        });
    }
}

function fight() {
    let zone = $("#zoneSelector option:selected").text();
    if (zone != 'Choose...')
        $.post("/character/attack", { 'zone': zone }, function (data) {
            printFight(data.gameLog, data.character, data.enemy, data.loot);
        });
    else
        printFight(["You must choose a zone in order to fight..."]);
}

$(function () {

    characterLoad();
});