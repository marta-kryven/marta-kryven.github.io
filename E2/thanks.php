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

// set this to one when comment submitted
$showend = 0;
$rank = 9;
$rankBonusCutoff = 3;
$dir = "att-test";

$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 1;
$ruler = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";

        $f = fopen($s, "a") or die("102: Unable to open file!" . $s);
        fwrite($f, $ip . " ". $date . " " . $browser . "\n");
        fclose($f);
        $validrequest = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mazeno = test_input($_POST["mazeno"]);
    $time = test_input($_POST["time"]);
    $maze = test_input($_POST["name"]);
    $UID = test_input($_POST["UID"]);
    $rank = test_input($_POST["rank"]);
    // $mazeid = test_input($_POST["mazeID"]);
    $rating = test_input($_POST["rating"]);

    if ($rank > 9) $rank = 9;

    if(empty($UID)) {
       $UID = "dodgyuser";
       $validrequest = 0;
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");
       fwrite($f, $ip . " ". $date . " " . $browser . "\n");
       fclose($f);
       $validrequest = 0;
    } else {  
       
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");

       if (!empty($maze)) {
          fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $mazeno . " " .  $maze . " " . $time . " " . $rating . "\n");
       } else {
          // it must be the comment
          $comment = test_input($_POST["comment"]);
          $monst = test_input($_POST["beliefs"]);
          $edu = test_input($_POST["education"]);        
 
          if (!empty($comment) ) {
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " " . $comment . "\n");
          } else {
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " " . "\n");
          }
          
          $decision = test_input($_POST["decision"]);

          if (!empty($decision) ) {
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " " . $decision . "\n");
          } else {
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " " . "\n");
          }
 
          fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " beliefs:" . $monst . " education:" . $edu . "\n");
          $showend = 1;

       }
       fclose($f);
     }
}

?>
 
<body>
<div id="ex2_container">

<?php
if ($validrequest && !$showend) {
   echo("<br><br><br>" . 
        "Thank you for completing the second half of the experiment!<br>" . 
        "<br>We are almost done!<br><br>" . 
        "<form name='frm' action='thanks.php' method='post' onsubmit='return validateForm()'>" .
           "How did you make your evaluations of intelligence?<br>" . 
           " <textarea name='decision' rows='5' cols='70'></textarea><br><br><br>" .
           "How do you see yourself on the political spectrum?<br><br>" .
           "more conservative <input type='range' onclick='validateRange()' name ='beliefs' id='beliefs' value='50' style='width:300px; height:12px'> more liberal" . 
           "<br><br><br>" .
           "What is the highest degree or education you have completed?<br>" . 
           "<select name='education'>" . 
           " <option value='0'>--------</option>" . 
           "<option value='1'>Some high school, no diploma</option>" .
           "<option value='2'>High school graduate</option>" .
           "<option value='3'>Some college credit, no degree</option>" .
           "<option value='4'>Vocational training</option>" .
           "<option value='5'>Associate degree</option>" .
           "<option value='6'>Bachelor's degree</option>" .
           "<option value='7'>Master's degree</option>" .
           "<option value='8'>Professional degree</option>" .
           "<option value='9'>Doctorate degree</select></option></select><br><br>" . 
           "<br>This is optional, but we would love to hear any comments that you may have.<br>" . 
           "<textarea name='comment' rows='5' cols='50'></textarea>" .
            "<input type='text' name='rank' hidden><input type='text' name='UID' hidden><br>" . 
           "<input type='submit' value='Submit'/>" .
           "</form>");

} else if (!$validrequest) {
  echo("<h1>Bad Request</h1>");
} else if ( $validrequest && $showend ) {
  $ruler = 1;
} else  {
  echo("<h1>Bad Request...</h1>");
}

?>

</div>

<script>
var r = <?php global $rank; echo $rank; ?>;
var uid =  "<?php global $UID; echo $UID; ?>";
var ruler = <?php global $ruler; echo $ruler; ?>;
var rangeIsValid = 0;

function makeRuler() {
  var s = " <table align='center' style='width:absolute;widthoat:center'><tr>";

  var td2 = "";
  step = 1; 

  for (curstep=1; curstep<= 10; curstep += step) {
     s += "<td bgcolor='#";
     if (r+1 == curstep ) {
       s += "ff0000'><img src='webfile/agent.png' style='width:50px;height:50px;display: block;'>";
     } else {
       s += "ffffff'>" + "<img src='webfile/mug.png' style='width:50px;height:50px;display: block;'>"//curstep;
     }
     td2 += "<td>" + curstep + "</td>";
     s +=  "</td>";
  }
  
  s += "</tr>" + "<tr>" + td2 + "</tr></table>";
  
  return s;
}

if ( ruler == 1) {
         ruler = "<h3>Based on past data, this is how well you did compared to other participants.<br>We will calculate your final bonus after we finish the experiment.</h3>"  +
                  "<p><font size = '4'>Please paste the following code into the original HIT before submitting it:" +  uid + "</font></p>" +  
                   "Your rank reflects the difference between the smallest number of steps in which the mazes can be solved<br>" + 
                   " by rational decision-making and the number of steps you took. <br><br> " +  
                   makeRuler(); 
         boilerplate = "<p><small> If you are interested in receiving more information regarding the results of this study" + 
                       " please please contact Tomer Ullman at tomeru@mit.edu.</small></p>";

         document.getElementById("ex2_container").innerHTML = "<br>" + 
                 ruler +
                 "<br>The experiment is over, but if you want to learn more about it, read on. In this experiment we try to assess how people solve a simple task that can require planning and thinking ahead. We plan to compare how people make decisions (for this maze task) with how other people evaluated these decisions." + 
                 boilerplate;
}

function validateForm() {
	document.forms["frm"]["UID"].value = uid;
        var x = document.forms["frm"]["decision"].value;
        document.forms["frm"]["rank"].value = r;
        if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
          return false;
       }
       
       if ( rangeIsValid == 0) {
          alert("Please select your political views." );
          return false;
       }

       x = document.forms["frm"]["education"].value;
       if (x == null || x == "" || x == 0) {
          alert("Please select your education." );
          return false;
       }
       return true;
}

function validateRange() {
   rangeIsValid = 1;
}
</script>
</body>
</html>
