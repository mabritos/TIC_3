function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

async function writeLog(gameLog) {
  gameLog.forEach(element => {
    $("gameLog").html($("gameLog").html() + element + "<br>");
    $("gameLog").scrollTop(250);
    await sleep(1000);
  });
}

$(function() {

});