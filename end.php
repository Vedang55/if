<?php
    require 'timeset.php';
    define('dbloc', './handlers/abc/d/test', true);

    class MyDB extends SQLite3 {
        function __construct() {
            $this->open(dbloc);
            $this->exec('PRAGMA journal_mode = wal;');
            $this->exec('pragma temp_store=FILE;');
            $this->busyTimeout(5000);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>The End</title>

    <style>
        body {
            background: url('./images/bckg.jpg');
            font-family: "Trebuchet MS", Helvetica, sans-serif;
        }

        .container {
            max-width:40rem;
            margin :0 auto 0 auto;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            min-height:100vh;
        }

        table {
            font-size : 2rem;
            border : 2px solid white;
            background-color:rgba(255,255,255,0.5);
        }
        th{
            border : 2px solid white; 
            padding:0.2rem 1rem 0.2rem 1rem;

        }
        
        td {
            border : 2px solid white; 
            text-align:center;
            padding:0.2rem 1rem 0.2rem 1rem;
            
        }


    </style>
    
  </head>
  <body>
    <div class='container'>

        
        <?php
        if(!isset($_SESSION['finaltime'])){
            $_SESSION['finaltime'] =  time() - $_SESSION['time'];
        }    
        
        $db = new MyDB();
        if(!$db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Opened database successfully<br>";
            $sql = "SELECT * from questions where type='". $_SESSION['type'] ."'";

            $ret = $db->query($sql);
            $correct = 0;
            
            while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                if(isset($_POST[$row['questionId']])){
                    if($_POST[$row['questionId']] == $row['answer']){
                        $correct++;
                    }
                }    
            }
            $db->close();
        }
        


            echo "<table align='center'>
                    <tr>
                        <th>Username</th>
                        <th>Type</th>
                        <th>time</th>
                        <th>Score</th>
                    </tr>";
            

                echo "<tr>
                        <td>". $_SESSION['user'] ."</td>
                        <td>". $_SESSION['type'] ."</td>
                        <td>". $_SESSION['finaltime'] . " seconds " ."</td>
                        <td>". $correct ."</td>
                        </tr>";
                         
            echo "</table>";




            

        ?>

    </div>



       
  </body>

  </html>
  