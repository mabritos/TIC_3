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

$(function() {
  characterLoad();
});