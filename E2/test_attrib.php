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

$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 0;

if (!is_writable($dir)) {
    echo 'The directory is not writable ' . $dir . '<br>';
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("Unable to open file!" . $s);
	fwrite($f, $ip . " ". $date . " " . $browser . "\n");
        fclose($f);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validrequest = 1;  
   $UID = test_input($_POST["UID"]);
   $rank = test_input($_POST["rank"]);
   $mazeno = test_input($_POST["mazeno"]);
   $comment = test_input($_POST["comment"]);
   $decision = test_input($_POST["decision"]);

   if(empty($UID))  $UID = "dodgyuser"; 

   $s = $dir . "/" . $UID . ".txt";
   $f = fopen($s, "a") or die("101 Unable to open file!" . $s);

   if( !empty($comment) || !empty($decision)) {

      if (!empty($comment) ) {
            fwrite($f,  $ip . " ". $date . " " . $browser . $UID . "\t" . $comment . "\n");
      } 

      $decision = test_input($_POST["decision"]);

      if (!empty($decision) ) {
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . "\t" . $decision . "\n");
      } 

      fwrite($f, $txt);
      fclose($f);

      // the first trial; generate a random sequence of 32 for this user
      $m = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34);

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
    $txt = "Practice Maze " . $txt;
    echo "<p  align='center'><b>PLEASE READ THE FOLLOWING INSTRUCTIONS CAREFULLY.</b><br>\n<br>";
    echo "Your task is to <b>evaluate</b> another person's solution on the scale 1 (less intelligent) to 5 (more intelligent).<br><br>";
    echo "The person wants to get to the exit <b>in fewest moves</b> and knows that the exit is <b>equally likely</b> to be behind any of the black squares.<br><br>";
    echo "In some videos you will see only a part of the path.<br><br>";
    echo "Please take a moment to review each maze before viewing the solution, then press &#34; >> Play/Pause&#34; to start the video.<br><br> "; 
    echo "Each video shows a different person. It is important to view each video at least once.";

  } else {
    //echo "<h1>Experiment</h1>";
    $txt = $mazeno - 2 . " of 32";
    if ($randomisedMazeNo >=27) $txt = $txt . " You will see only a part of this solution";
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

var mn = <?php global $randomisedMazeNo; echo $randomisedMazeNo; ?>;
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
   
    onEnded();
}

function onEnded() {
  document.getElementById("sub").disabled = false;
  document.getElementById("r1").disabled = false;
  document.getElementById("r2").disabled = false;
  document.getElementById("r3").disabled = false;
  document.getElementById("r4").disabled = false;
  document.getElementById("r5").disabled = false;
}

function loadStart() {
   var v = document.getElementById("video");
 //  v.play(); 
   //v.pause();
   alert("loadvid"); 
} 

function onWaiting() {
  alert("waiting");
 // v.pause(); // useless
}

function onPlay() {
  alert("play");
  // v.pause(); // no use
}

function OnPlaying() {
  alert("playing");
  v.pause(); // useless because the video is not playing yet
}

function onloadedData() {
 alert("loaded data");
 //v.play(); 
 v.pause();
}

function onSuspend() {
  alert("suspend");  //v.play();
}

function onProgress() {
  alert("progress"); v.pause();
}

function onStalled() {
  alert("stalled");
}

function generate_table() {
   var s = "<video id='video' onended='onEnded()' style=' background: url(loading.gif) no-repeat center center' >" + 
             "<source src='movies/" + mf + "' type='video/mp4'>  " + 
           "</video>";  
         // " preload='true'" +
          //" autobuffer='true' " +
     
   var b = "<button onclick='playPause()'><font size='4'> >>&nbsp Play/Pause</font></button><br>"; 
   var f = "<br><form name='frm' action='";

   mn = <?php global $mazeno; echo $mazeno; ?>;
   if (mn <34 ) {
     f = f + "test_attrib.php";
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
            "<input type='text' name='name' hidden> <input type='text' name='rank' hidden>" + 
            "<input type='text' name='time' hidden><input type='text' name='mazeID' hidden>" + 
            "<input type='submit' id = 'sub' value='Submit' disabled/>" +
        "</fieldset>" +
      "</form>";
   return b + s + f;
}


function loadEventHandler() {
	s = generate_table();
	document.getElementById("ex1_container").innerHTML = s;

        var now= new Date(), 
        h= now.getHours(), 
        m= now.getMinutes(), 
        s= now.getSeconds();
        ms = now.getMilliseconds();

        var times = "t(" + h + "," + m + "," + s + "," + ms + ");";
        savedtime += times;
        var v = document.getElementById("video");
        v.load(); 
        // alert("loadform"); 
        //v.play();
        //alert("i" + v.duration()); 
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
       var r = <?php global $rank;  echo "$rank" ?>;
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

       if ( m == 2) {
           alert("Great, you have finished practice!");
       }

       document.forms["frm"]["UID"].value = u_id;
       document.forms["frm"]["mazeno"].value = m;
       document.forms["frm"]["mazeID"].value = mnr;
       document.forms["frm"]["time"].value = savedtime;
       document.forms["frm"]["rank"].value = r;
       document.forms["frm"]["name"].value = mf; 
       return true;
}
</script>

<div id="ex1_container">
</div>

</body>
</html> 
