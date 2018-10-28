<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Instructions</title>
<style>
#ex2_container { text-align:center; font-size:120%;}
</style>
</head>
 
<body>
<div id="ex2_container">
<br><br><br>
Welcome to our study!<br><br>
To participate in this study you need a desktop computer or a laptop, not a mobile device.<br><br>
This study has two parts. <br><br> In the first part you will look for an exit in a maze. <br><br> 
In the second part you will view and evaluate the solution of other participants.<br><br>
The study takes approximately 20 minutes. <br><br>
Thanks for participating!<br>

<br>
<br>
<form name="frm" action="test.php" method="post" onsubmit="return validateForm()">
  Your age: <input type="text" name="age"><br>
  Your gender: <input type="text" name="gender"><br>
  <input type="text" name="UID" hidden><input type="text" name="block" hidden><input type="text" name="trial" hidden><br>
  
<p><font size='2'>
Informed Consent <br>
By answering the following questions, you are participating in a study performed by cognitive scientists in the MIT Department of Brain and Cognitive Science. If you have questions
about this research, please contact Tomer Ullman at tomeru@mit.edu. Your participation in this research is voluntary. You may decline to answer any or all of the following questions. You may decline further participation, at any time, without adverse consequences. Your anonymity is assured; the researchers who have requested your participation will not receive
 any personal identifying information about you. By clicking 'I agree' you indicate your consent to participate in this study.
</font></p> <br>

  <input type="submit" value="I agree"/>
</form>

</div>
</body>

<script>
function validateForm() {
    var UID = "S";
    UID = UID+Math.floor((Math.random() * 1000000) + 1); 

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
    return true;  
}

</script>
</html>
