<!-- Attribution task -->

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Instructions</title>
<style>
#ex2_container { text-align:left; font-size:120%;}
</style>
</head>
 
<body>

<?php

// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

$dir = "att-test";
$age = $gender = $UID =  "";
$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); 
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 0;
$quiz = "";
$showEndNext = "false";

if (!is_writable($dir)) {
    echo 'The directory is not writable ' . $dir . '<br>';
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
        echo 'Err: get request received. <br>';

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("Unable to open file!" . $s);
        fwrite($f, $ip . " ". $date . " " . $browser . "Err: GET request \n");
        fclose($f);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validrequest = 1;
   if (!empty($_POST["UID"])) {
      $UID = test_input($_POST["UID"]);
//      echo 'UID is not empty!' . $UID . '<br>';
      if ( !strcmp($UID, "dodgyuser") ) {
        $validrequest = 0;
      }
   } else {
      echo 'UID is empty!' . $UID . '<br>';
      $validrequest = 0;
      $UID = "dodgyuser";
   }

 //  echo 'UID is ' . $UID . '<br>';  
   if (!empty($_POST["gender"]) && !empty($_POST["age"])) {
      $gender = test_input($_POST["gender"]);
      $age = test_input($_POST["age"]);
      $s = $dir . "/" . $UID . ".txt";
      $f = fopen($s, "a") or die("101 Unable to open file!" . $s);
      $txt = $ip . " " . $date . " " . $browser . " Age: " . $age . "\tGender: " . $gender .  "\n";
      fwrite($f, $txt);
      fclose($f);
      $showEndNext = "false";
   }

   $s = $dir . "/" . $UID . ".txt";
   if (!empty($_POST["decision"]) ) {
        $f = fopen($s, "a") or die("101 Unable to open file!" . $s);
        $decision = test_input($_POST["decision"]);
  //      echo $decision . "<br>";
        fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " decision2 " . $decision . "\n");
        fclose($f);
   }

   if (!empty($_POST["comment"]) ) {
        $f = fopen($s, "a") or die("101 Unable to open file!" . $s);
        $decision = test_input($_POST["comment"]);
        fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " comment2 " . $decision . "\n");
        fclose($f);
   }

   if (!empty($_POST["quizAnswer"])) {
      $quiz1 = test_input($_POST["quizAnswer"]);
      $s = $dir . "/" . $UID . ".txt";
      $f = fopen($s, "a") or die("101 Unable to open file!" . $s);
      $txt = $ip . " " . $date . " " . $browser .  " " . $UID .  " attributionquiz: " . $quiz1 .  "\n";
      fwrite($f, $txt);
      fclose($f);
   }

    if (!empty($_POST["showEndNext"])) {
          $showEndNext = test_input($_POST["showEndNext"]);
    }
}

?>

<div id="ex2_container">
<br>Now we will do the Evaluation Task <br><br>
<br><b>INSTRUCTIONS (PLEASE READ CAREFULLY)</b><br><br>
You will see videos of <b>other people</b> playing a Maze Game, and rate their intelligence.<br><br>
<b>Explanation of the Maze Game:</b> 
People are trying to find an exit (a red circle).<br><br>
People know the layout of the maze (where the walls and rooms are).<br><br>
People know the exit is equally likely to be behind any of the black squares.<br><br>
People want to find the exit in as few moves as possible.<br><br>
  <br>
 <img src='webfile/example.png' width='350' height='270' > 
 <br>

<b>YOUR TASK:</b><br><br>

After you watch each video, please evaluate the intelligence of the person in the video. 
<br><br>
You will use a scale from 1 (less intelligent) to 5 (more intelligent).
<br><br>
<b>NOTE:</b><br><br>
Each video shows a different person<br><br>
Walls and floor tiles change from trial to trial.<br><br> 
Some videos show only part of a person's solution. 
<br><br>
It is important to view each video at least once.
<br><br>
If a video does not load after a few seconds, try reloading the page.<br><br>

<form name="frm" action="quiz.php" method="post" onsubmit="return validateForm()">
  <input type="text" name="UID" hidden><input type="text" name="showEndNext" hidden><input type="text" name="firsttrial" hidden><br>
  <input type="submit" value="Continue"/>
</form>

</div>


<script>
var quiz = "<?php global  $quiz; echo  $quiz; ?>";

function validateForm() {
    var u_id = "<?php global  $UID; echo  $UID; ?>";
    var showend = "<?php global  $showEndNext; echo  $showEndNext; ?>";
    document.forms["frm"]["UID"].value = u_id;
    document.forms["frm"]["showEndNext"].value = showend;
    document.forms["frm"]["firsttrial"].value = "true";
    return true;  
}

</script>
</body>
</html>
