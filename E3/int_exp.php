<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Instructions</title>
<style>
#ex2_container { text-align:left; font-size:120%;}
#ex1_container { text-align:left; font-size:120%;}
</style>
</head>

<?php

$browser = $_SERVER['HTTP_USER_AGENT'];
$validbrowser = 1;

if ( strpos($browser, "Safari") ) {
	$validbrowser = 2;
}

if ( strpos($browser, "Chrome") ) {
      $validbrowser = 1;
}
?>
 
<body onload="loadEventHandler()">
<div id="ex2_container">
<br><br><br>
Welcome to our study!<br><br>
<font color='red'><b>IMPORTANT!</b> This study runs best in Firefox, on a desktop/laptop.<br><br> 
The study will <b>NOT </b> run on Safari, or a mobile device.</font><br><br>

<b>In this study: </b> 
This study has two tasks. <br><br> 
In the Maze task you will look for an exit in a maze. <br><br> 
In the Evaluation task you will rate the plans of other people.<br><br>
After the two tasks, you will be asked 3 unrelated reasoning questions.<br><br>
The study is expected to take 25 minutes.<br><br>
Thanks for participating!
<br><br>

<p><font size='2'>
Informed Consent <br>
By answering the following questions, you are participating in a study performed by cognitive scientists in the MIT Department of Brain and Cognitive Science. If you have questions
about this research, please contact Tomer Ullman at tomeru@mit.edu. Your participation in this research is voluntary. You may decline to answer any or all of the following questions. You may decline further participation, at any time, without adverse consequences. Your anonymity is assured; the researchers who have requested your participation will not receive
 any personal identifying information about you. By clicking 'I agree' you indicate your consent to participate in this study.
</font></p> <br>

</div>

<div id="ex1_container">
<form name="frm" action="" method="post" onsubmit="return validateForm()">
  Your age: <input type="text" name="age"><br>
  Your gender: <input type="text" name="gender"><br>
  <input type="text" name="UID" hidden><input type="text" name="task" hidden><input type="text" name="showEndNext" hidden><br>
  <input type="submit" value="I agree"/>
</form>

</div>

<script>

var task;
var browserok = <?php global  $validbrowser; echo  $validbrowser; ?>;
var bs = "<?php global  $browser; echo  $browser; ?>";

function loadEventHandler() {
 
  if ( Math.random() > 0.5 ) {
        document.forms["frm"].action = "int_exp1.php";
        task = "task1";
  } else {
        document.forms["frm"].action = "int_exp2.php";
        task = "task2";
  }

  if (browserok == 2) {
      s  = document.getElementById("ex2_container").innerHTML;
      document.getElementById("ex1_container").innerHTML = "<br><br><font color='red'>UNFORTUNATELY YOUR BROWSER IS NOT SUPPORTED.</font>";
  }

}


function validateForm() {
    if (browserok == 2) {
       alert("Unfortunately your browser is not supported.");
       return false;
     }

    var UID = "S";
    UID = UID+Math.floor((Math.random() * 100000000) + 1); 

    // generate subject ID
    document.forms["frm"]["UID"].value = UID;
    var x = document.forms["frm"]["age"].value;
    if (x == null || x == "" || x == 0) {
        alert("Please enter your age.");
        return false;
    }

    x = document.forms["frm"]["gender"].value;
    if (x == null || x == "" || x == 0) {
        alert("Please enter your gender.");
        return false;
    }
    
    document.forms["frm"]["task"].value = task;
    document.forms["frm"]["showEndNext"].value = "false";
    return true;  
}

</script>
</body>
</html>
