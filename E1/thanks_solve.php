<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thank you!</title>
<style>
#ex2_container { text-align:center; font-size:120%;}
</style>
</head>

<?php
// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validRequest = 0;

// set this to one when comment submitted
$showend = 0;
$rank = 9;
$dir = "att-test";
$vallidRequest=0;

// do not allow GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
     $vallidRequest=0;
     $s = $dir . "/" . $UID . ".txt";        
     $f = fopen($s, "a") or die("Unable to open file!" . $s);
     fwrite($f, $ip . "\t". $date . "\t" . $browser . "\tthabksGETREQUEST\n");
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

       $mazeno = test_input($_POST["mazeno"]);
       $path = test_input($_POST["path"]);
       $time = test_input($_POST["time"]);
       $maze = test_input($_POST["name"]);
       $UID = test_input($_POST["UID"]);
       $steps = test_input($_POST["steps"]);
       $mazeid = test_input($_POST["mazeID"]);
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");

       if (!empty($path)) {
          fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $maze . " " . $steps . " " . $path . " " . $time . "\n");

          $difference = $steps-166;
          $showend = 1;

          $rank=floor(abs($difference)/7);
          if ($difference < 0 && $difference > -15 ) {
            $rank=0; 
          } else if ($difference <= -15 ) {
            $rank=9;
          }

          if ($rank>9) $rank=9;
       }
       fclose($f);
}

?>
 
<body>
<div id="ex2_container">
<br><br><br>
Thank you for completing the first half of the experiment!<br><br>
Please answer the question below to move on to the second half.
<br><br>
<form name="frm" action="test_attrib.php" method="post" onsubmit="return validateForm()">
  How did you make your decisions about which way to go?<br> 
  <textarea name="decision" rows="5" cols="70"></textarea><br><br>
  This is optional, but we would love to hear any comments that you may have.<br>
  <textarea name="comment" rows="5" cols="50"></textarea>
  <input type="text" name="steps" hidden> <input type="text" name="UID" hidden><br>
  <input type="text" name="mazeno" hidden><input type="text" name="rank" hidden><input type="submit" value="Submit"/>
</form>
</div>


<script>
var r = <?php global $rank; echo $rank; ?>;

function validateForm() {
	document.forms["frm"]["UID"].value = "<?php global $UID; echo $UID; ?>";
        document.forms["frm"]["steps"].value = "<?php global $steps; echo $steps; ?>";
        var x = document.forms["frm"]["decision"].value;
        document.forms["frm"]["mazeno"].value = 0;
        if (x == null || x == "" || x == 0) {
          alert("Please answer the question to proceed." + x);
          return false;
       }
       document.forms["frm"]["rank"].value = r; 
       return true;
}
</script>

</body>
</html>
