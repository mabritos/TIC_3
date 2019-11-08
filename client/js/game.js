function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


async function printFight(gameLog, character, enemy, loot) {
    for (let index = 0; index < gameLog.length; index++) {
        const element = gameLog[index];
        $("#gameLog").html($("#gameLog").html() + element + "<br>");
        $("#gameLog").scrollTop(250);
        await sleep(1000);
    }
    if (character)
        printCharacter(character);
    if (enemy)
        $("#gameImage").attr("src", "/client/img/enemies/"+enemy.icon);
    if (loot)
        printLoot(loot);
    $("#fightButton").prop('disabled', false);
}

function printLoot(loot) {
    //TO DO
}

function equipItem(){
    //get itemId from selector
    let itemId;
    $.post("/character/getItem",{"itemId": itemId}, function(item){
        $.post('/character/equip', {'item': item}, function(character){
            if(item.type == 'A')
                printCharacter(character,{'armor': item, 'weapon': null});
            else
                printCharacter(character,{'armor': null, 'weapon': item});
        });
    });
}

var xpLevels = [0, 100, 250, 500, 1000, 2500];

function printXp(xp) {
    let xpTop = getXpTop(xp);
    current_progress = (xp - xpLevels[xpTop - 1]) / xpLevels[xpTop] * 100;
    $(".progress-bar").css("width", current_progress + "%").attr("aria-valuenow", current_progress);
    $(".progress").notify(xp + '/' + xpLevels[xpTop], {
      style: 'rpg', className: "xp", position: "right"
    });
    $("#lvl").html(xpTop);
}

function getXpTop(xp) {
    for (i = 0; i < xpLevels.length; i++)
        if (xp < xpLevels[i])
            return i;
    return 0;
}

function printCharacter(character, items) {
    $("#characterName").html(character.name);
    $("#characterGold").html(character.gold).fadeIn();
    $("#characterHp").html(character.hp);
    $("#characterStrength").html(character.strength);
    $("#characterAgility").html(character.agility);
    $("#characterIntelligence").html(character.intelligence);
    printXp(character.xp);
    if(items) {
        if(items.armor){
            $("#characterArmor").attr("src", "/client/img/items/" + items.armor.icon);
            $("#characterArmorName").html(items.armor.name);
        }if(items.weapon){
            $("#characterWeapon").attr("src", "/client/img/items/" + items.weapon.icon);
            $("#characterWeaponName").html(items.weapon.name);
        }
    }
}

function characterLoad(character = null) {
    if (character === null)
        $.post("/character/load", function (character) {
            $.post('/character/getItems', {"character": character}, function(items){
                printCharacter(character, items);
            });
        });
    else {
        $.post('/character/getItems', {"character": character}, function(items){
            printCharacter(character, items);
        });
    }
}

function fight() {
    let zone = $("#zoneSelector option:selected").text();
    if (zone != 'Choose...') {
        $("#fightButton").prop('disabled', true);
        $.post("/character/attack", { 'zone': zone }, function (data) {
            printFight(data.gameLog, data.character, data.enemy, data.loot);
        });
    } else
        printFight(["You must choose a zone in order to fight..."]);
}

$(function () {
    characterLoad();
});