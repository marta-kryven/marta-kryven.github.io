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
<div id="ex2_container">
<br><br><br>
Welcome to our study!<br><br>
<b>IMPORTANT!</b> This study runs best in Firefox, on a desktop/laptop.<br><br> 
The study will <b>NOT </b> run on Safari, or a mobile device.<br><br>

<b>In this study: </b> you will see 35 videos of people playing a maze game.<br><br>
Your goal is to judge the intelligence of the players.<br><br> 
 After the videos, you will be asked 3 unrelated reasoning questions.<br><br>
The study is expected to take 15-20 minutes.<br><br>
Thanks for participating!
<br><br>

<form name="frm" action="int_exp2.php" method="post" onsubmit="return validateForm()">
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
    return true;  
}

</script>
</html>
