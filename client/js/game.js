var loadedCharacter;

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}


async function writeLog(gameLog) {
  for (let index = 0; index < gameLog.length; index++) {
    const element = gameLog[index];
    $("#gameLog").html($("#gameLog").html() + element + "<br>");
    $("#gameLog").scrollTop(250);
    await sleep(1000);
  }
}

function characterLoad(){
  $.post( "/character/load", function(character) {
      loadedCharacter = character;
      $("#characterName").html(character.name);
      $("#characterXp")// TO DO
      $("#characterGold").html(character.gold);
      $("#characterHp").html(character.hp);
      $("#characterStrength").html(character.strength);
      $("#characterAgility").html(character.agility);
      $("#characterIntelligence").html(character.intelligence);
      $("#characterWeapon");// TO DO
      $("#characterArmor");// TO DO
  });
}
function fight(){
   $.post("/character/attack",{"characterId": loadedCharacter.id, "zone": $( "#zoneSelector option:selected" ).text()}, function(data){
       writeLog(data.gameLog);
       characterLoad();

       }
   );
}

$(function() {
  characterLoad();
});