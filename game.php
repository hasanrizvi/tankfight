<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Game</title>
  </head>
  <body>
    <script>

      var canvas = document.createElement("canvas");
      var ctx = canvas.getContext("2d");
      canvas.width = 612;
      canvas.height = 612;
      document.body.appendChild(canvas);

      // Background Image
      var bgReady = false;
      var bgImage = new Image();
      bgImage.onload = function()
      {
        bgReady = true;
      };
      bgImage.src  = "img/bg.jpg";

      // Hero Image
      var heroReady = false;
      var heroImage = new Image();
      heroImage.onload = function()
      {
        heroReady = true;
      };
      heroImage.src = "img/tank.png";

      // Enemy Image
      var enemyReady = false;
      var enemyImage = new Image();
      enemyImage.onload = function()
      {
        enemyReady = true;
      };
      enemyImage.src = "img/tank2.png";

      // Fire Image
      var fireReady = false;
      var fireImage = new Image();
      fireImage.onload = function()
      {
        fireReady = true;
      };
      fireImage.src = "img/fire.png";

      // Game objects
      var hero = {
        speed: 192, // Movement in pixels per second
        x:264.0,
        y:528
      };

      var enemy = {
        x:getRandomInt(25,503),
        y:getRandomInt(57,345)
      };

      var fire = {
        speed:320,
        x:hero.x,
        y:hero.y
      };

      var enemyDestroy = 0;
      var isFired = false;

      // Handle keyboard controls
      var keysDown = {};
      addEventListener("keydown", function (e) { keysDown[e.keyCode] = true; }, false);
      addEventListener("keyup", function (e) { delete keysDown[e.keyCode]; }, false);

      // Function for generating Random Numbers
      function getRandomInt(min, max)
      {
        return Math.floor(Math.random() * (max - min + 1)) + min;
      }

      // Reset the game when the hero destroys an enemy
      var reset = function()
      {
        // Place the enemy somewhere on the screen randomly
        enemy.x = getRandomInt(25,503);
        enemy.y = getRandomInt(57,345);

        if((hero.x <= (enemy.x + 62) && enemy.x  <= (hero.x + 62) && hero.y <= (enemy.y + 75) && enemy.y <= (hero.y + 75)))
        {
          reset();
        }
      };

      var reset2 = function()
      {
        fire.x = hero.x;
        fire.y = hero.y;
        ctx.drawImage(fireImage, hero.x, hero.y);
        ctx.drawImage(heroImage, hero.x, hero.y);
      };

      // Update game objects
      var update = function (modifier)
      {
        if(38 in keysDown)  // Player holding up
        {
          isFired = true;
          ctx.drawImage(fireImage, hero.x, hero.y);
        }
        if(40 in keysDown)  // Player holding down
        {

        }
        if(37 in keysDown)  // Player holding left
        {
          if(hero.x > 25.0)
          {
            hero.x -= hero.speed * modifier;
            if(!isFired)
            {
              fire.x -= hero.speed * modifier;
            }
          }
        }
        if(39 in keysDown)  // Player holding right
        {
          if(hero.x < 503.00)
          {
            hero.x += hero.speed * modifier;
            if(!isFired)
            {
              fire.x += hero.speed * modifier;
            }
          }
        }
        if(isFired)
        {
          fire.y -= fire.speed * modifier;
          ctx.drawImage(fireImage, fire.x, fire.y);
          if (fire.y <= 20.0)
          {
            isFired = false;
            reset2();
          }
        }

        // Are they colliding?
        if((fire.x <= (enemy.x + 45) && enemy.x  <= (fire.x + 45) && fire.y <= (enemy.y + 55) && enemy.y <= (fire.y + 55)))
        {
          ++enemyDestroy;
          isFired = false;
          reset2();
          reset();
        }
      };

      // Draw everything
      var render = function()
      {
        if(bgReady)
        {
          ctx.drawImage(bgImage, 0, 0);
        }
        if(heroReady)
        {
          ctx.drawImage(fireImage, fire.x, fire.y);
          ctx.drawImage(heroImage, hero.x, hero.y);
        }
        if(enemyReady)
        {
          ctx.drawImage(enemyImage, enemy.x, enemy.y)
        }


        // Score
        ctx.fillStyle = "rgb(256,256,256)";
        ctx.font = "24px Helvetica";
        ctx.textAlign = "center";
        ctx.textBaseline = "top";
        ctx.fillText("Enemy Destroyed: " + enemyDestroy, 306,15);
      };

      // The main game loop
      var main = function()
      {
        var now = Date.now();
        var delta = now - then;
        var x = delta/1000;

        update(x);
        render();

        then = now;

        // Requeest to do this again
        requestAnimationFrame(main);
      };

      // Cross-browser support for requestAnimationFrame
      var w = window;
      requestAnimationFrame = w.requestAnimationFrame || w.webkitRequestAnimationFrame || w.msRequestAnimationFrame || w.mozRequestAnimationFrame;

      // Let's play this game!
      var then = Date.now();
      setInterval(function(){reset()},2000);
      main();
    </script>

  </body>
</html>
