var timer = document.getElementById("timer");
var duration = 90;

setInterval(updateTimer, 1000);
function updateTimer() {
  duration--;
  if (duration < 1) {
    window.location = "index.php";
  } else {
    var result = parseInt(duration / 60) + ":" + (duration % 60);
    timer.innerText = result;
  }
}
