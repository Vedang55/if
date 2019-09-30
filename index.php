<?php
require 'timeset.php';
define('dbloc', './handlers/abc/d/test', true);

class MyDB extends SQLite3 {
  function __construct() {
    $this->open(dbloc);
    $this->exec('PRAGMA journal_mode = wal;
    pragma temp_store=FILE;');
    $this->busyTimeout(10000);
  }
}


if (isset($_SESSION["user"])){
  header("Location: ./game.php");
}

if(isset($_POST['submit'])){
    if((date("H")> $_SESSION['countdown'][0]) || (date("H")== $_SESSION['countdown'][0]  && date('i') >= $_SESSION['countdown'][1])){
        $submitbutton= $_POST['submit'];
        if ($submitbutton){
            
            $_SESSION["user"] = str_replace(' ','',$_POST['username']);
            $_SESSION['type'] = $submitbutton;
            header("Location: ./game.php");
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Quick Quiz</title>
    <link rel="stylesheet" href="app.css" />
  </head>
  <body>
  <p style='display:none;' id='countdown'>
    <?php
      echo $_SESSION['countdown'][0].":". $_SESSION['countdown'][1] .":". $_SESSION['countdown'][2] ;
    ?>
  </p>

    <div class="container">
        
        <div id="home" class="flex-center flex-column">

            <h1 style='color:white;'>Important : Using another tab besides the current will lead to direct disqualification</h1><br>
            <p style="font-size:4rem; font-family:cursive;color:yellow;" id='si'>Starts in</p>
            <h1 id='time'>
            <?php
              $info = getdate();
              $hour = date('h');
              $min = $info['minutes'];
              $sec = $info['seconds'];
              echo $hour.":".$min.":".$sec;
            ?>
            </h1>
            <form method="POST" class='homeform' style='display:none' id='homeform'>
              <input type='text' placeholder='Team Name' id='usernameField' name='username' required pattern="[a-zA-Z]+[ a-zA-Z]*" maxlength="20" autocomplete='off' autofocus>
              <br>
              <input type="submit" class="btn" value="Environment" name='submit' id='play'>
              <input type="submit" class="btn" value="Infinity" name='submit' id='play'>
            </form>
        </div>
    </div>

    <script src="index.js"></script>

  </body>
</html>
