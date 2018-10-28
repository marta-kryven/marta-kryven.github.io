<!DOCTYPE html>
<html>
<title>
Instructions quiz</title>
<head>
<style>
h2{
    text-align: center;
}
#ex1_container { align:center; text-align: center;}
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

$UID =  "";
$instructionsOK = 0; 
$dir = "att-test";

$UID = "dodgyuser";
$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s');
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);

$steps=0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {

     $s = $dir . "/" . $UID . ".txt";        
     $f = fopen($s, "a") or die("Unable to open file!" . $s);
     fwrite($f, $ip . "\t". $date . "\t" . $browser . "\tQUIZGETREQUEST\n");
     fclose($f);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $UID = test_input($_POST["UID"]);

   if(empty($UID)) {
        $UID = "dodgyuser";
   } else {
        if (!is_writable($dir)) {
          echo 'The directory is not writable ' . $dir . '<br>';
        }
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("101: Unable to open file!" . $s);

        $mazeno = test_input($_POST["mazeno"]);
        $path = test_input($_POST["path"]);
        $time = test_input($_POST["time"]);
        $maze = test_input($_POST["name"]);
        $steps = test_input($_POST["steps"]);
        
        fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $maze . " " . $steps . " " . $path . " " . $time . "\n");

        fclose($f);
   }

}

?>

<div id="ex1_container">
<h2>Great, you have finished <b>practice</b>!</h2><br>

<p  align='center'> 
Please answer the instructions quiz below to proceed with the experiment. <br>
</p>


<p  align='center'> 
<font color='red' size=4>Question 1: My task is to .. </font>
<form name="frm" style='border:0' action="test.php" method="post" onsubmit="return validateForm()">
        <fieldset style='border:0'>
            <input style='border:0' type='radio' id = 'id2' name='objective' value='2' /> visit every square in the maze.</><br>
            <input style='border:0' type='radio' id = 'id3' name='objective' value='3' /> finish in as little time as possible.</><br>
            <input style='border:0' type='radio' id = 'id1' name='objective' value='1' /> solve the mazes in as few steps as possible.</><br>
            <input style='border:0' type='radio' id = 'id4' name='objective' value='4' /> click as fast as possible.</><br><br>
        </fieldset>
            <br>
            <br><font color='red' size=4>Question 2: My bonus will be bigger if I ...</font><br>
        <br> 
        <fieldset style='border:0'>
            <input style='border:0' type='radio' id = 'b1' name='bonus' value='1' /> finish the mazes in less time.</><br>
            <input style='border:0' type='radio' id = 'b2' name='bonus' value='2' /> finish the mazes in fewer steps.</><br> 
            <input style='border:0' type='radio' id = 'b3' name='bonus' value='3' /> am lucky at guessing.</><br>
            <input style='border:0' type='radio' id = 'b4' name='bonus' value='4' /> The bonus will be given at random.</>
        </fieldset> 
        <br>
        <br>
        <fieldset style='border:0'> 
            <input type='text' name='UID' hidden>
            <input type='text' name='quizAnswer' hidden>
            <input type='text' name='mazeno' hidden>
            <input type="submit" value="Submit" onclick='submitQuizClicked()'/>
        </fieldset>
</form>
 </p>

</div>
</body>

<script>

var validInput=0;

function submitQuizClicked() {

    var x = null;
    if (document.getElementById('id1').checked) {
      x="1";
    } else if (document.getElementById('id2').checked) {
      x="2";
    } else if (document.getElementById('id3').checked) {
      x="3";
    } else if (document.getElementById('id4').checked) {
      x="4";
    }

    var b = null;

    if (document.getElementById('b1').checked) {
      b="1";
    } else if (document.getElementById('b2').checked) {
      b="2";  
    } if (document.getElementById('b3').checked) {
      b="3"; 
    } else if (document.getElementById('b4').checked) {
      b="4";
    }

 
    if (x == null || x == "" || b ==null ) {
        alert("Please answer all questions.");
        validInput = 0;
        return false;
    } else {
        validInput = 1;
        var u_id = "<?php global $UID;  echo $UID ?>";
 
        if (x!="1" || b!="2" ) {
           alert("Incorrect, please read the instructions carefully.");
           document.forms["frm"].action="test.php";
           document.forms["frm"]["mazeno"].value = 0;
        } else {
           document.forms["frm"].action="test.php";
           document.forms["frm"]["mazeno"].value = 2;
        }

        document.forms["frm"]["quizAnswer"].value = "Q1:" + x + "_Q2:" + b;
        document.forms["frm"]["UID"].value = u_id;

        // select block 1 or 2 at random
//        if (Math.random() > 0.5) {
//        	document.forms["frm"]["hidingcondition"].value = 1;
//	} else {
//		document.forms["frm"]["hidingcondition"].value = 2;
//	}
        return true;
    }

//alert(document.forms["frm"].action);
return true;
}

function practiceClicked() {
    //alert("clicked practice");
    var u_id = "<?php global $UID;  echo $UID ?>";
    document.forms["frm0"]["quizAnswer"].value = -1; 
    document.forms["frm0"]["UID"].value = u_id;
    document.forms["frm0"]["mazeno"].value = 0;
    return true;
}

function validateForm() {
    return (validInput==1);
}

</script>
</html>
