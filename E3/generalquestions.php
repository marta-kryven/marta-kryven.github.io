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
$validrequest = 0;
$ruler = 0;
$showEndNext = "false";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";

        $f = fopen($s, "a") or die("102: Unable to open file!" . $s);
        fwrite($f, $ip . " ". $date . " " . $browser . "Get request to generalquestions.php\n");
        fclose($f);

        $validrequest = 0;
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


    if (!empty($_POST["showEndNext"])) {
          $showEndNext = test_input($_POST["showEndNext"]);
    }

    if ( !strcmp($UID, "dodgyuser") ) {
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");
       fwrite($f, $ip . " ". $date . " " . $browser . " Malformed POST request to generalquestions.php with bad uid\n");
       fclose($f);
    } else {  
       
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");

       if (!empty($_POST["comment"]) ) {
            $comment = test_input($_POST["comment"]); 
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " comment2 " . $comment . "\n");
       } 
          
       $decision = test_input($_POST["decision"]);

       if (!empty($_POST["decision"]) ) {
            $decision = test_input($_POST["decision"]);
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " decision2 " . $decision . "\n");
       } 
 
       if (!empty( $_POST["CRT1"] )) {
            $CRT1 = test_input($_POST["CRT1"]);
            $showend = 1;
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " CRT1 " . $CRT1 . "\n");
       }
 
       if (!empty( $_POST["CRT2"] ) ) {
            $CRT2 = test_input($_POST["CRT2"]); 
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " CRT2 " . $CRT2 . "\n");
       }
 
       if (!empty( $_POST["CRT3"] ) ) {
            $CRT3 = test_input($_POST["CRT3"]);
            fwrite($f,  $ip . " ". $date . " " . $browser . " " . $UID . " CRT3 " . $CRT3 . "\n");
       } 

       fclose($f);
     }
  }

?>
 
<body onload="loadEventHandler()">
<div id="ex2_container">

<?php
if ($validrequest && !$showend) {
   echo("<br>Great! We're nearly done! This is the last page of questions.<br><br>" . 
        "<h2>Please answer the following questions:</h2><br>" . 
        "<form name='frm' action='generalquestions.php' method='post' onsubmit='return validateForm()'>" .
           
           "It takes 10 people 10 hours to knit 10 scarfs. How long does it take 100 people to knit 100 scarfs?<br><br>" .
           "<textarea name='CRT2' rows='5' cols='50'></textarea><br><br>" .
           
           "A slime mold doubles in size every 2 hours. One gram of slime mold can fill a container in 8 hours.<br>" . 
           " How long will it take to fill half of the same container?<br><br>"  .
           "<textarea name='CRT1' rows='5' cols='50'></textarea><br><br>" .
           
           "A coffee and a sandwich cost $12. A sandwich costs $10 more than a coffee. How much does a coffee cost?<br><br>" .
           "<textarea name='CRT3' rows='5' cols='50'></textarea><br><br>" .
           
           "OPTIONAL: Please leave any comments about the study here, we welcome any feedback." .
           "<br><br><textarea name='comment' rows='5' cols='50'></textarea><br><br>" .
           
           "<input type='text' name='UID' hidden><br>" . 
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
var maze = "<?php global $maze; echo $maze; ?>;" 
var showEndNext = "<?php global $showEndNext; echo $showEndNext; ?>";

function loadEventHandler() {
}

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
         ruler = "<p><font size = '4'>Please paste the following code into the original HIT before submitting it:" +  uid + "</font></p>";   
         boilerplate = "<p><small> If you are interested in more information about of this study, " + 
                       " please contact Tomer Ullman at tomeru@mit.edu.</small></p>";

         document.getElementById("ex2_container").innerHTML = "<br>" + 
                 ruler +
                 "<br>The experiment is over and you can now safely navigate away.<br>" + 
                 "If you want to learn more about it, read on.<br>" + 
                 "In this experiment, we try to assess how people evaluate the intelligence of others, " +
                 "using a simple task that requires planning and thinking ahead." + 
                 " We plan to compare how people make decisions (solving the maze task) " + 
                  "with how other people evaluated their decisions.<br>"
                 boilerplate;
}

function validateForm() {
      document.forms["frm"]["UID"].value = uid;
      var x = document.forms["frm"]["decision"].value;
      if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
          return false;
      }
     
      x = document.forms["frm"]["CRT1"].value;
      if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
          return false;
      }

      x = document.forms["frm"]["CRT2"].value;
      if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
          return false;
      }

      x = document.forms["frm"]["CRT3"].value;
      if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
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
