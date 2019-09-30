<?php
    require 'timeset.php';

    //logout
    if(isset($_GET['logout'])){                   
        session_destroy();
        $_SESSION = [];
    }

    if(isset($_SESSION['finaltime'])){
        header("Location: ./end.php");
    }

    //check start time
    if(((date("H")> $_SESSION['countdown'][0]) || (date("H")== $_SESSION['countdown'][0]  && date('i') >= $_SESSION['countdown'][1])) 
    && isset($_SESSION["user"])){  //check time
        
    }
    else{
        header("Location: ./index.php");
    }

 

    //db location
    define('dbloc', './handlers/abc/d/test', true);

   
    if(!isset($_SESSION['totalq'])){         
        $sql =  "SELECT COUNT(*) FROM questions where type='". $_SESSION['type'] ."'";
        $db = new MyDB();
        if(!$db) {
            echo $db->lastErrorMsg();
        } else {
            $ret = $db->query($sql);
            
            if($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                $_SESSION['totalq'] = $row['COUNT(*)'];
            }             
                    
            $db->close();
            
        }
    }

 

    if(!isset($_SESSION['time'])){
        $_SESSION['time'] = time();
    }
 


    class MyDB extends SQLite3 {
        function __construct() {
            $this->open(dbloc);
            $this->exec('PRAGMA journal_mode = wal;
            pragma temp_store=FILE;');
            $this->busyTimeout(10000);
        }
    }
?>





<!-- html starts -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="app.css" />
  </head>
  <body>

  <div class='header'>
        
        <h1 class='questionNo timer' id='timer'>00:40:24</h1>

        <?php
            
            echo "<h1 class='questionNo timer'>Type: ".$_SESSION['type']."</h1>";
            echo "<h1 class='questionNo timer' id='timer'>Team: ".$_SESSION['user']."</h1>";
        ?>

    </div>

    
<div class="qcontainer">





    <?php
    

        $db = new MyDB();
        if(!$db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Opened database successfully<br>";
            $sql = "SELECT * from questions where type='". $_SESSION['type'] ."'";

            $ret = $db->query($sql);
            $row_no = 1;
            
            while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                if($row['questionId'] == 12){
                    echo "<div class = 'qbox'>";
                    echo " <video controls>
                    <source src='./videos/video.mp4' type='video/mp4'>
                  </video>
                    ";
                    echo "</div>";
                }


                echo "<div class = 'qbox'>";
                $a = $row['questionId'];
                echo "<h1 class='questionNo'>Question : ". $row_no ."/".$_SESSION['totalq'] ."</h1>";
                
                echo "<h2 align=center class='question'>";
                echo $row['question'];
                echo "</h2>";  
                
                if($row['imageUrl'] != NULL){
                    echo "<img src='". $row['imageUrl'] ."' class='questionImg'>";
                }

                
                echo "<div class='options'>";


                    echo "
                    <div>
                        <input type='radio' id='contactChoice1".$row['questionId']."'
                        name='". $row['questionId'] ."' value='a' form='mainform'>
                        <label for='contactChoice1".$row['questionId']."' class='choice'>". $row['a'] ."</label>
                    </div>
                    
                    <div>
                        <input type='radio' id='contactChoice2".$row['questionId']."'
                        name='".$row['questionId']."' value='b' form='mainform'>
                        <label for='contactChoice2".$row['questionId']."' class='choice'>". $row['b'] ."</label>
                    </div>
                    
                    <div>
                        <input type='radio' id='contactChoice3".$row['questionId']."'
                        name='".$row['questionId']."' value='c' form='mainform'>
                        <label for='contactChoice3".$row['questionId']."' class='choice'>". $row['c'] ."</label>
                    </div>

                    <div>
                        <input type='radio' id='contactChoice4".$row['questionId']."'
                        name='".$row['questionId']."' value='d' form='mainform'>
                        <label for='contactChoice4".$row['questionId']."' class='choice'>". $row['d'] ."</label>
                    </div>
                        ";


                    



                echo "</div>"; //options




                echo "</div>";  // qbox

          
                         
                $row_no++;
            }             
            
            
            $db->close();

        }




    ?>





<form id='mainform' method='post' action='end.php'>
        <button type="submit" value="Submit" class='submitAnswerButton' id='submitbutton' name='submitForm'>Submit</button>
<form>
  

</div>


<!-- js -->
<script src="game.js"></script>

  </body>
</html>