var timer = document.getElementById("timer");
var duration = 1800;

setInterval(updateTimer, 1000);
function updateTimer() {
  duration--;
  if (duration < 1) {
    window.location = "logout.php";
  } else {
    timer.innerText = duration;
  }
}

window.addEventListener("mousemove", resetTimer);

function resetTimer() {
  duration = 1800;
}
