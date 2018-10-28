<!DOCTYPE html>
<html>
<title>
Instructions quiz</title>
<style>
h2{
    text-align: center;
}
#ex1_container { align:center; text-align: center;}
</style>
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
$showEndNext = "false";
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
   }

   if (!empty($_POST["showEndNext"])) {
       $showEndNext = test_input($_POST["showEndNext"]);
   }
}

?>

<div id="ex1_container">

<p  align='center'> 
Please answer the questions to proceed with the experiment. <br>
</p>


<p  align='center'> 
<font color='red' size=4>Question 1: The person&#39;s task is to .. </font>
<form name="frm" style='border:0' action="test.php" method="post" onsubmit="return validateForm()">
        <fieldset style='border:0'>
            <input style='border:0' type='radio' id = 'id2' name='objective' value='2' /> visit every room in the maze.</><br>
            <input style='border:0' type='radio' id = 'id3' name='objective' value='3' /> find a secret trap-door.</><br>
            <input style='border:0' type='radio' id = 'id1' name='objective' value='1' /> get to the exit in as few steps as possible.</><br>
            <input style='border:0' type='radio' id = 'id4' name='objective' value='4' /> it is very open-ended.</><br><br>
        </fieldset>
            <br>
            <br><font color='red' size=4>Question 2: My task is to ...</font><br>
        <br> 
        <fieldset style='border:0'>
            <input style='border:0' type='radio' id = 'b1' name='bonus' value='1' /> finish the task in least time.</><br>
            <input style='border:0' type='radio' id = 'b2' name='bonus' value='2' /> rate each person&apos;s intelligence.</><br> 
            <input style='border:0' type='radio' id = 'b3' name='bonus' value='3' /> carefuly track each person&apos;s movements.</><br>
            <input style='border:0' type='radio' id = 'b4' name='bonus' value='4' /> rate each person&apos;s political beliefs.</>
        </fieldset> 
        <br>
        <br>
        <fieldset style='border:0'> 
            <input type='text' name='UID' hidden><input type='text' name='firsttrial' hidden>
            <input type='text' name='quizAnswer' hidden>
            <input type='text' name='showEndNext' hidden>           
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
           document.forms["frm"].action="int_exp2.php";
        } else {
           document.forms["frm"].action="test_attrib.php";
        }

        var showend = "<?php global  $showEndNext; echo  $showEndNext; ?>";
        document.forms["frm"]["showEndNext"].value = showend;

        document.forms["frm"]["quizAnswer"].value = "Q1:" + x + "_Q2:" + b;
        document.forms["frm"]["UID"].value = u_id;
        document.forms["frm"]["firsttrial"].value = "true";
        //alert(document.forms["frm"]["quizAnswer"].value);
        return true;
    }

return true;
}


function validateForm() {
    return (validInput==1);
}

</script>
</html>
