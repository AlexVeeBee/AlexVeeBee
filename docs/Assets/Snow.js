var canvas = document.getElementById("snowcanvas");

// create a canvas element at the top if there is no canvas element
if (canvas == null) {
    canvas = document.createElement("canvas");
    canvas.id = "snowcanvas";
    document.body.appendChild(canvas);
}

var ctx = canvas.getContext("2d");

// Set the canvas dimensions to match the window
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Set the starting position of the snowflakes
var x = 0;
var y = 0;

// Set the amount of snowflakes
var snowflakes = 100;

// Create an array to hold the snowflakes
var snowflakesArray = [];

// Create the snowflakes and add them to the array
for (var i = 0; i < snowflakes; i++) {
  snowflakesArray.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    radius: Math.random() * 4 + 1,
    speed: Math.random() * 0.5 + 0.1
  });
}

// Draw the snowflakes on the canvas
function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.fillStyle = "white";
  ctx.beginPath();

  for (var i = 0; i < snowflakesArray.length; i++) {
    var flake = snowflakesArray[i];

    ctx.moveTo(flake.x, flake.y);
    ctx.arc(flake.x, flake.y, flake.radius, 0, Math.PI * 2, true);
  }

  ctx.fill();
  move();
}

// Move the snowflakes by updating their y coordinate
// and resetting them to the top of the canvas when they reach the bottom
function move() {
  for (var i = 0; i < snowflakesArray.length; i++) {
    var flake = snowflakesArray[i];

    flake.y += flake.speed;

    if (flake.y > canvas.height) {
      flake.y = 0;
    }
  }
}

// Call the draw function every 25 milliseconds
setInterval(draw, 25);

// page on resize
window.addEventListener("resize", function() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
})