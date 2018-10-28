<!DOCTYPE html>
<html>
<head>
<title>
Experiment</title>
<style>
table, tr, th, td {
    border:1px solid black;
    border-collapse: collapse;
    width:absolute;
    height:absolute;
}
h1 {
    text-align: center;
}

h2{
    text-align: center;
}

#ex1_container { align:center; text-align: center;}

</style>
</head>
<body onload="loadEventHandler()">

<?php

// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

// define variables and set to empty values
$dir = "att-test";
$steps = 0; 
$randomisedMazeNo = 0;
$age = $gender = $UID =  "";
$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 0;
$mazeno = 0;

if (!is_writable($dir)) {
    echo 'The directory is not writable ' . $dir . '<br>';
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("Unable to open file!" . $s);
	fwrite($f, $ip . " ". $date . " " . $browser . " GET request to test_attrib.php \n");
        fclose($f);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validrequest = 1;
   if (!empty($_POST["UID"])) {  
      $UID = test_input($_POST["UID"]);
      if ( !strcmp($UID, "dodgyuser") ) {
        $validrequest = 0;
      } 
   } else {
      $validrequest = 0;
      $UID = "dodgyuser"; 
   }

   if (!empty($_POST["mazeno"])) {
     $mazeno = test_input($_POST["mazeno"]);
   }
   
   $s = $dir . "/" . $UID . ".txt";
   $f = fopen($s, "a") or die("101 Unable to open file!" . $s);

   if( !empty($_POST["quizAnswer"]) ) {
      $mazeno = 0;

      $quiz1 = test_input($_POST["quizAnswer"]);
      $txt = $ip . " " . $date . " " . $browser .  " " . $UID .  " solvingquiz: " . $quiz1 .  "\n";
      fwrite($f, $txt);
      fclose($f);

      // the first trial; generate a random sequence of 32 for this user

      $m = array();
      for ($x = 3; $x < 35; $x++) {
        $m[] = $x;
      }

      // permute randomly
      for ($x = 0; $x < count($m); $x++) {
        $pickone = rand(0, count($m)-1);
        if ($pickone <> $x) { 
    		$temp = $m[$x];
        	$m[$x] = $m[$pickone];
        	$m[$pickone] = $temp;
        }
      } 

      // save to file

      $f = fopen($dir . "/" . $UID . "A_sequence.txt", "a") or die("Unable to open file!" . $UID . "sequence");
      for ($x = 0; $x < count($m); $x++) {
         fwrite($f, $m[$x] . "\n");
      }
      fclose($f);
      $randomisedMazeNo = 0;

   }
   else {
        $mazeid = test_input($_POST["mazeID"]);
        $time = test_input($_POST["time"]);
        $maze = test_input($_POST["name"]);
        $rating = test_input($_POST["rating"]);
 
        fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $mazeno . " " .  $maze . " " . $time . " " . $rating . "\n");
        fclose($f);
        advanceMazeNo();
   }
}

$mfile = ($randomisedMazeNo+1) . ".mp4";

if ($validrequest == 1) {
  if ($mazeno < 3) {
    $txt = $mazeno+1 . " of 3";
    $txt = "Practice " . $txt;
    echo "<p  align='left'>You will first see 3 practice trials.<br><br>";
    echo "It is important to view each video at least once.<br><br>";
    echo "In some videos you will see only a part of the path.<br><br>";
  } else {
    //echo "<h1>Experiment</h1>";
    $txt = $mazeno - 2 . " of 32";
    if ($randomisedMazeNo >=27) $txt = $txt . "  <font color='red'>In this video you will see only a part of the path that the person took.</font>";
    //echo "<p align='center'>Please evaluate another solution.</p>";
  }

  echo "<h2>$txt</h2>\n";
} else {
  echo "<h2>Err: Invalid Request</h2>\n";
}

function advanceMazeNo() {
    global $mazeno, $randomisedMazeNo, $UID, $dir;
    $mazeno = $mazeno + 1;
    $randomisedMazeNo = $mazeno;

    // is this a practice or a real trial?
    if ($mazeno > 2) {
         $s = $dir . "/" . $UID . "A_sequence.txt";
         $f = fopen($s, "r") or die("102: Unable to open file! " . $s);
         for ($x = 0; $x <= $mazeno-3; $x++) {
           $randomisedMazeNo = intval(fgets($f));
//           echo "$randomisedMazeNo <br>";
         }
         fclose($f);
   }
}

?>

<script>

var mn = <?php global $mazeno; echo $mazeno; ?>;
var rmn = <?php global $randomisedMazeNo; echo $randomisedMazeNo; ?>;
var mf = "<?php global $mfile;  echo  $mfile; ?>";
var u_id = "<?php global  $UID; echo  $UID; ?>";
var savedtime = "";

 
function playPause() { 
    var v = document.getElementById("video");
    if (v.paused) {
        v.play();
    } 
    else { 
        v.pause();
    }
   
//    onEnded(); // uncommend to enable the Submit button
}

function onEnded() {
  document.getElementById("sub").disabled = false;
  document.getElementById("r1").disabled = false;
  document.getElementById("r2").disabled = false;
  document.getElementById("r3").disabled = false;
  document.getElementById("r4").disabled = false;
  document.getElementById("r5").disabled = false;
}

function generate_table() {
   var s = "<video id='video' onended='onEnded()' " + 
             "style=' background: url(loading.gif) no-repeat center center' " + 
             //" controls = 'false' " +
             " preload='metadata' " +
            //" preload='none' " + 
             ">" +
             "<source src='movies/" + mf + "' type='video/mp4;' >" + 
           // "<source src='movies/" + mf + "' type='video/mp4; codecs=h.264' >" + // does not help, makes chrome not load at all 
           "</video>";

   var b = "<button onclick='playPause()'><font size='4'> >>&nbsp Play/Pause</font></button><br>";
   var f = "<br><form name='frm' action='";

   mn = <?php global $mazeno; echo $mazeno; ?>;
   
   if (mn==2) {
     f = f + "finished_practice.php";
   } else if (mn <34 ) {
     f = f + "test_attrib.php?x=" + Math.random();
   } else {
     f = f + "thanks.php";
   }

   f = f + "' method='post' onsubmit='return submitForm()'>" +
        "<fieldset style='border:0'>" +
            "(less intelligent)<input type='radio' name='rating' id='r1' value='1' disabled/>&nbsp; 1</>"+
            "<input type='radio' name='rating' id='r2' value='2' disabled/>2</>"+
            "<input type='radio' name='rating' id='r3' value='3' disabled/>3</>"+
            "<input type='radio' name='rating' id='r4' value='4' disabled/>4</>"+
            "<input type='radio' name='rating' id='r5' value='5' disabled/>5 &nbsp; (more intelligent) </><br><br>"+
            "<input type='text' name='mazeno' hidden><input type='text' name='UID' hidden>" +
            "<input type='text' name='name' hidden>" +
            "<input type='text' name='time' hidden><input type='text' name='mazeID' hidden>" +
            "<input type='submit' id = 'sub' value='Submit' disabled/>" +
            //"<input type='submit' id = 'sub' value='Submit' />" +
        "</fieldset>" +
      "</form>";
   return b + s + f;
}


function loadEventHandler() {
   //alert("loadform");
   document.getElementById("ex1_container").innerHTML = generate_table(); 

   var now= new Date(),
   h= now.getHours(),
   m= now.getMinutes(),
   s= now.getSeconds();
   ms = now.getMilliseconds();

   var times = "t(" + h + "," + m + "," + s + "," + ms + ");";
   savedtime += times;
   var v = document.getElementById("video"); 
   //v.load();
   //v.play();
}


function submitForm() {
       var now= new Date(),
       h= now.getHours(),
       m= now.getMinutes(),
       s= now.getSeconds();
       ms = now.getMilliseconds();

       times = "t(" + h + "," + m + "," + s + "," + ms + ");";
       savedtime += times;

       var m = <?php global $mazeno;  echo "$mazeno" ?>;
       var mnr = <?php global $randomisedMazeNo;  echo "$randomisedMazeNo" ?>;

       var x = null;
        if (document.getElementById('r1').checked) {
         x="1";
       } else if (document.getElementById('r2').checked) {
         x="2";
       } else if (document.getElementById('r3').checked) {
         x="3";
       } else if (document.getElementById('r4').checked) {
         x="4";
       } else if (document.getElementById('r5').checked) {
         x="5";
       }

       if ( x == null || x == "" ) {
         alert("Please answer the question.");
         return false;
       }

       document.forms["frm"]["UID"].value = u_id;
       document.forms["frm"]["mazeno"].value = m;
       document.forms["frm"]["mazeID"].value = mnr;
       document.forms["frm"]["time"].value = savedtime;
       //document.forms["frm"]["rating"].value = x;
       document.forms["frm"]["name"].value = mf;

       //document.forms["frm"].submit(); 
       //alert("OK submit pressed.");
       return true;
}

</script>

<div id="ex1_container">
</div>

</body>
</html> 
