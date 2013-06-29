<?php

session_start();

$numbers = empty($_SESSION['numbers']) ? array() : $_SESSION['numbers'];
$letters = empty($_SESSION['letters']) ? array() : $_SESSION['letters'];

$keyboard = array(
  array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'),
  array('a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'),
  array('z', 'x', 'c', 'v', 'b', 'n', 'm'),
);

?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Countdown Solver</title>

  <!-- Included CSS Files -->
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/app.css">

  <!--[if lt IE 9]>
    <link rel="stylesheet" href="stylesheets/ie.css">
  <![endif]-->

  <script src="javascripts/modernizr.foundation.js"></script>

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>
<body>

  <!-- container -->
  <div class="container">

    <div class="row">
      <div class="twelve columns panel">
        <h1>Countdown Solver</h1>
      </div>
    </div>

    <div class="row">
      <div class="twelve columns panel">

        <h2>Numbers game</h2>
        <h3>Pick your numbers</h3>
        <form action="number.php" method="post">
          <p>
            <?php for ($i = 1; $i <= 10; $i++): ?>
              <input type="submit" name="add" class="nice radius medium blue button" value="<?php echo $i ?>"<?php if (count($numbers) >= 6) echo ' disabled' ?> />
            <?php endfor ?>
          </p>
          <p>
            <?php for ($i = 25; $i <= 100; $i += 25): ?>
              <input type="submit" name="add" class="nice radius medium blue button" value="<?php echo $i ?>"<?php if (count($numbers) >= 6) echo ' disabled' ?> />
            <?php endfor ?>
            <input type="submit" name="clear" class="nice radius medium red button" value="Clear" />
          </p>
        </form>

        <?php if (count($numbers) > 0): ?>
          <?php if (count($numbers) >= 2): ?>
            <form action="rpn.php" method="post" class="calculate" target="response">
              <p>
                <input type="number" min="100" max="999" name="target" class="small input-text" value="<?php echo rand(100, 999) ?>" />
                <input type="submit" name="go" class="nice radius medium red button" value="Go" />
              </p>
            </form>
          <?php endif ?>
          <h3>Numbers picked:</h3>
          <form action="number.php" method="post">
            <p>
              <?php foreach ($numbers as $key => $value): ?>
                <button name="del" class="nice radius medium green button" value="<?php echo $key ?>" >
                  <?php echo $value ?>
                </button>
              <?php endforeach ?>
            </p>
          </form>
        <?php endif ?>

      </div>
    </div>

    <div class="row" id="letters">
      <div class="twelve columns panel">

        <h2>Letters game</h2>
        <h3>Pick your letters</h3>
        <form action="letter.php" method="post" id="keyboard">
          <?php foreach($keyboard as $key => $row): ?>
            <p>
              <?php foreach($row as $letter): ?>
                <input type="submit" name="add" class="nice radius medium blue button" value="<?php echo $letter ?>"<?php if (count($letters) >= 9) echo ' disabled' ?> />
              <?php endforeach ?>
              <?php if ($key == 2): ?>
                <input type="submit" name="clear" class="nice radius medium red button" value="Clear" />
              <?php endif ?>
            </p>
          <?php endforeach ?>
        </form>

        <?php if (count($letters) > 0): ?>
          <?php if (count($letters) >= 1): ?>
            <form action="dict.php" method="post" class="calculate" target="response">
              <p><input type="submit" name="go" class="nice radius medium red button" value="Go" /></p>
            </form>
          <?php endif ?>
          <h3>Letters picked:</h3>
          <form action="letter.php" method="post">
            <p>
              <?php foreach ($letters as $key => $value): ?>
                <button name="del" class="nice radius medium green button" value="<?php echo $key ?>" >
                  <?php echo $value ?>
                </button>
              <?php endforeach ?>
            </p>
          </form>
        <?php endif ?>

        <iframe id="response" name="response" style="display: none;"></iframe>
      </div>
    </div>

    <div class="row" id="results">
      <div class="twelve columns panel">
        <h1>Results</h1>
        <div class="content"></div>
      </div>
    </div>

  </div>
  <!-- container -->

  <div id="spinner"></div>


  <!-- Included JS Files -->
  <script src="javascripts/jquery.min.js"></script>
  <script src="javascripts/foundation.js"></script>
  <script src="javascripts/spin.min.js"></script>
  <script src="javascripts/app.js"></script>

</body>
</html>
