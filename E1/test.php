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
$age = $gender = $UID =  "";
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
	fwrite($f, $ip . "\t". $date . "\t" . $browser . "Invalid request to test.php\n");
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
 
   if (!empty($_POST["age"])) {
      $age = test_input($_POST["age"]);
   } 
   
   if (!empty($_POST["gender"])) {
      $gender = test_input($_POST["gender"]);
   }

   if (!empty($_POST["quizAnswer"])) {
      $quiz1 = test_input($_POST["quizAnswer"]);
   } 
 
   $s = $dir . "/" . $UID . ".txt";
   $f = fopen($s, "a") or die("101 Unable to open file!" . $s);

   if(!empty($age)) {

      $txt = $ip . " " . $date . " " . $browser . " Age: " . $age . "\tGender: " . $gender .  "\n";
      fwrite($f, $txt);
      fclose($f);

      // the first trial; generate a random sequence of 12 for this user
      $m = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);

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

      $f = fopen($dir . "/" . $UID . "sequence.txt", "a") or die("Unable to open file!" . $UID . "sequence");
      for ($x = 0; $x < count($m); $x++) {
         fwrite($f, $m[$x] . "\n");
      }
      fclose($f);
      $randomisedMazeNo = 0;

   }
   else if (!empty($quiz1)) {
        $txt = $ip . " " . $date . " " . $browser .  " " . $UID .  " solvingquiz: " . $quiz1 .  "\n";
        fwrite($f, $txt);
        fclose($f);
        $mazeno = test_input($_POST["mazeno"]);
        advanceMazeNo();
   } 
   else {
        if (!empty($_POST["mazeno"])) {
          $mazeno = test_input($_POST["mazeno"]);
        } else {
          $mazeno = 0;
          $randomisedMazeNo = 0;
        } 
        
        $mazeid = test_input($_POST["mazeID"]);
        $path = test_input($_POST["path"]);
        $time = test_input($_POST["time"]);
        $maze = test_input($_POST["name"]);
        $steps = test_input($_POST["steps"]);
   
        fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $maze . " " . $steps . " " . $path . " " . $time . "\n");
        fclose($f);
        advanceMazeNo();
   }
}

// agent location
$agent_x = 0;
$agent_y = 0;

$mazefile = array("tunnel", "shortandlong", "whichway", "courtyard", "cathedral", "bunker3", "labyrinth3",  "library", "tworooms", "roomG", "garden", "cubicles", "lab", "ikea", "museum" );
if (!empty($randomisedMazeNo)) {
        $mfile = $mazefile[$randomisedMazeNo];
//        echo "maze $randomisedMazeNo is $mfile <br>";

} else {
  $randomisedMazeNo = 0;
  $mfile = $mazefile[$randomisedMazeNo];
  $mazeno = 0;
  $txt = "1 of 3";
  $mfile = "tunnel";
}

if ($validrequest == 1) {

  if ($mazeno < 3) {
    // echo "<p align='center'><font size='5' color='red'>Practice Maze</font></p>";
    $txt = $mazeno+1 . " of 3";
    $txt = "Practice Maze " . $txt;
    echo "<p  align='center'><b>PLEASE READ THE FOLLOWING INSTRUCTIONS CAREFULLY.</b><br>\n<br>";
    echo "Your task is to <font color='red'><b>exit</b></font> the maze by reaching the <font color='red'><b>red square</b></font> in as few steps as possible.<br><br>";
    echo "You can move one square at a time, by clicking on the white squares near your character.<br><br>";
    echo "<font color='#868caa'><b>Blue squares</b></font> are walls. You cannot see through the walls, so the squares you cannot see yet are <b>black</b>.";
    echo "<br><br>The <font color='red'><b>exit</b></font> is equally like to be hidden behind any of the <b>black squares</b>.<br><br>";
    echo "<font color='red'><b>In the end of the experiment we will add all steps you took and show you how you did compared to previous results.</b></font>";
    echo "<table align='center' style='widthoat:center; border:0px solid white;'><tr style=' border:none'>";
    echo "<td style=' border:none'><font color='red'><b>You have an opportinity to earn a bonus for completing the mazes in fewer steps.</b></font></td>";
    echo "<td style=' border:none'><img src='bonus.jpg' alt='bonus' style='width:200px;height:60px;'></tr></table>";
    echo "";

  } else {
    //echo "<h1>Experiment</h1>";
    $txt = $mazeno - 2 . " of 12";
    echo "<p align='center'>Find the <font color='red'><b>exit</b></font> in as few steps as possible. <br><br>The  <font color='red'><b>exit</b></font> is equally like to be hidden behind any of the <b>black squares</b>.</p>";
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
         $s = $dir . "/" . $UID . "sequence.txt";
         $f = fopen($s, "r") or die("102: Unable to open file! " . $s);
         for ($x = 0; $x <= $mazeno-3; $x++) {
           $randomisedMazeNo = intval(fgets($f));
//           echo "$randomisedMazeNo <br>";
         }
         fclose($f);
   }
}

function readWorld($fname) {
        global $worldWidth, $worldHeight, $worldmap;
        $world = fopen("webfile/" . $fname . ".txt", "r") or die("Unable to open file!" . $fname);
        $worldWidth = fgets($world);
        $worldHeight = fgets($world);

        // create a world array
        $worldmap = array();

        for ($y = 0; $y < $worldHeight; $y++) {
                $mazeLine = fgets($world);
                $line = str_split($mazeLine);
                $worldmap[$y] = array(); 
                
                for ($x = 0; $x < $worldWidth; $x++) {
                        $worldmap[$y][$x] =  $line[$x];
                }
        }
        fclose($world);
}

readWorld($mfile);

?>

<script>
var ax = <?php global $agent_x;  echo "$agent_x" ?>;
var ay = <?php global $agent_y;  echo "$agent_y" ?>;
var height = <?php global $worldHeight; echo "$worldHeight"?>;
var width = <?php global $worldWidth;  echo "$worldWidth"?>;
var warr = <?php global $worldmap; echo json_encode($worldmap); ?>;

var mn = <?php global $randomisedMazeNo; echo $randomisedMazeNo; ?>;
var mf = "<?php global $mfile;  echo  $mfile; ?>";
var u_id = "<?php global  $UID; echo  $UID; ?>";
var savedpath = "p(0,0);";
var savedtime = "";
var valid = "<?php global $validrequest;  echo $validrequest; ?>";
 
jssteps = <?php global $steps; echo $steps;?>;
progress_step = 1;

// this does work
// alert(u_id);

var seen = new Array(height);
var visible = new Array(height);

// allocate seen array
for (y = 0; y < height; y++) {
   seen[y] = new Array(width);
   visible[y] = new Array(width);
   for (x = 0; x < width; x++) {
        seen[y][x] = 0;
        visible[y][x] = 0;
   }
}

calculate_seen();

function calculate_seen() {
  for (y = 0; y < height; y++) {
    for (x = 0; x < width; x++) {
        if (( Math.abs(ax-x) + Math.abs(ay-y)) <= 1){  
          seen[y][x] = 1;
        }
    } 
  }

  for (i = 5; i > 0; i--) {
      isovistLevel(i);
  }

  for (y = 0; y < height; y++) {
    for (x = 0; x < width; x++) {
        if (visible[y][x] == 1 )  {
           seen[y][x] = 1;
        }
    }
  } 
}

function isovistLevel(level) {
    for (px = ax-level; px <= ax+level; px++) {
      addvisible(px, ay-level, level); // top row
      addvisible(px, ay+level, level); // bottom row
    }

    for (py = ay-level; py < ay+level; py++) {
      addvisible(ax+level, py, level);  // right row
      addvisible(ax-level, py, level);  // left row
    }
}

function addvisible(px, py, level) {
    if (px >= 0 && py >=  0 &&  px < width &&  py < height) {
        visible[py][px] = 0;
        var b = true;
        if (level > 1 || (Math.abs(ax-px) + Math.abs(ay-py) > 1) ) {
                if (b) b = checkline(ax+0.1, ay+0.1, px+0.1, py+0.1, level*45.0);
                if (b) b = checkline(ax+0.9, ay+0.1, px+0.9, py+0.1, level*45.0);
                if (b) b = checkline(ax+0.9, ay+0.9, px+0.9, py+0.9, level*45.0);
                if (b) b = checkline(ax+0.1, ay+0.9, px+0.1, py+0.9, level*45.0);
                if (b) visible[py][px] = 1;
        }
        if (b) visible[py][px] = 1; 
    }
}

function checkline(x1, y1, px, py, step) {
    var dx = (x1-px)/step;
    var dy = (y1-py)/step;

    var fx = px+dx;
    var fy = py+dy;
    var x = Math.floor(fx);
    var y = Math.floor(fy);

    do {
      if (px < 0 || py < 0 || px >= width || py >= height ) {
        return true;
      }

      var w = parseInt(warr[y][x]);
      if (w == 3) return false;

      fx +=dx; 
      fy +=dy;      
      x = Math.floor(fx);  
      y = Math.floor(fy);
    } while (x !=Math.floor(x1) || y !=Math.floor(y1));
    return true;
}


function generate_table(wagent) {
    // generate progress

    var progress = "<p><font size='6' color='#ff0000'>Steps: " +  parseInt(progress_step-1) + "</font></p>";
    
    //alert("generate table");
    var s = progress + " <table align='center' style='width:absolute;widthoat:center'>";

    calculate_seen(); 
    for (y = 0; y < height; y++) {
      s = s + "<tr>";
      for (x = 0; x < width; x++) {
        
        var w = parseInt(warr[y][x]);
        if (w == 3) {
                s = s + "<td bgcolor='#868caa' width=45px height=45px>";
        } else {
                bkc = "#ffffff";
                sclick = "tableClickedVoid";
                // if(w == 2) bkc = "#ff0000"; // i think this does nothing 

                sclick = "tableClickedVoid";
        
                if ( wagent !=2 ) {        
                  if (seen[y][x] == 1) {
                        if (x == ax-1 && y == ay) {
                                sclick = "tableClickedXminus";  bkc = "#ffffff";
                        } else if (x == ax+1 && y == ay) {
                                sclick = "tableClickedXplus"; bkc = "#ffffff";
                        } else if (y == ay-1 && x == ax) {
                                sclick = "tableClickedYminus"; bkc = "#ffffff";
                        } else if (y == ay+1 && x == ax) {
                                sclick = "tableClickedYplus"; bkc = "#ffffff";
                        } else if (x == ax && y== ay) {
                                bkc = "#ffffff"; 
                        }

                        if(w == 2) bkc = "#ff0000";
                  } else {
                         bkc = "#000000";
                  }
                }

                s = s + "<td bgcolor='";
                s = s + bkc; 
                s = s + "' width=45px  height=45px onclick='";
                s = s + sclick;
                s = s + "();'>";

        }
        
        if (x == ax && y== ay) {
                s = s + "<img src='webfile/agent.png' style='width:45px;height:45px;display: block;'>";
        } 
        s = s + "</td>"; 
      }
      s = s + "</tr>";
    }

    s = s + "</table>";
    return s; 
}


function loadEventHandler() { 
  if (valid == 1) {
	s = generate_table();
	document.getElementById("ex1_container").innerHTML = s;
  }
        
  var now= new Date(), 
  h= now.getHours(), 
  m= now.getMinutes(), 
  s= now.getSeconds();
  ms = now.getMilliseconds();

  var times = "t(" + h + "," + m + "," + s + "," + ms + ");";
  savedtime += times;  
}

function tableClickedVoid() {

}

function tableClickedXminus() {
 ax = ax-1;
 tableClicked();
}

function tableClickedXplus() {
 ax = ax+1;
 tableClicked();
}

function tableClickedYminus() {
 ay = ay-1;
 tableClicked();
}

function tableClickedYplus() {
 ay = ay+1;
 tableClicked();
}


function tableClicked() {
        progress_step = progress_step + 1;
	jssteps = jssteps+1;
        savedpath += "p(" + ax + "," + ay + ");";

        var now= new Date(), 
        h= now.getHours(), 
        m= now.getMinutes(), 
        s= now.getSeconds();
        ms = now.getMilliseconds();

        times = "t(" + h + "," + m + "," + s + "," + ms + ");";
        savedtime += times;  

	var w = parseInt(warr[ay][ax]);

        document.getElementById("ex1_container").innerHTML = "<p  align='center'>" + generate_table(w) + "</p>";
        // if reached goal state.
	
        if (w == 2) {
		mn = <?php global $mazeno; echo $mazeno; ?>;    
		if (mn >= 14) {
  			document.getElementById("ex1_container").innerHTML += "<p align='center'><form name='frm' action='thanks_solve.php' method='post' onsubmit='submitThanks()'>" + 
                           "<input type='text' name='name' hidden><input type='text' name='steps' hidden><input type=text' name='mazeno' hidden><input type='text' name='UID' hidden>" +      
                           "<input type='text' name='path' hidden><input type='text' name='time' hidden><input type='text' name='mazeID' hidden>" +
                           //"<input type='submit' value='Submit'/>"
                           "</form></p>";
		} else if (mn==2) {
                        document.getElementById("ex1_container").innerHTML += "<p align='center'><form name='frm' action='quiz.php' method='post' onsubmit='submitForm()'>" +
                           "<input type='text' name='name' hidden><input type='text' name='steps' hidden><input type=text' name='mazeno' hidden><input type='text' name='UID' hidden>" +
                           "<input type='text' name='path' hidden><input type='text' name='time' hidden><input type='text' name='mazeID' hidden>" +
                           // <input type='submit' value='Submit'/>
                           "</form></p>";
                
                } else {
			document.getElementById("ex1_container").innerHTML += "<p align='center'><form name='frm' action='test.php' method='post' onsubmit='submitForm()'>" +
                           "<input type='text' name='name' hidden><input type='text' name='steps' hidden><input type=text' name='mazeno' hidden><input type='text' name='UID' hidden>" + 
			   "<input type='text' name='path' hidden><input type='text' name='time' hidden><input type='text' name='mazeID' hidden>"+
                           //<input type='submit' value='Submit'/>
                           "</form></p>";	
		}
                  
                submitForm();
                document.forms["frm"].submit();  
	} else {
              //  document.getElementById("ex1_container").innerHTML += "<p  align='center'> <button type=\"button\" disabled>Submit</button></p> ";
        }
}


function submitThanks() {
        var m = <?php global $mazeno; echo $mazeno; ?>;
        var mnr = <?php global $randomisedMazeNo;  echo "$randomisedMazeNo" ?>;

        document.forms["frm"]["UID"].value = u_id;
        document.forms["frm"]["mazeno"].value = m;
        document.forms["frm"]["path"].value = savedpath;
        document.forms["frm"]["time"].value = savedtime;
        document.forms["frm"]["name"].value = mf;
        document.forms["frm"]["mazeID"].value = mnr;
        document.forms["frm"]["steps"].value = jssteps;
}


function submitForm() {
        var m = <?php global $mazeno;  echo "$mazeno" ?>;
        var mnr = <?php global $randomisedMazeNo;  echo "$randomisedMazeNo" ?>;

       // if ( m == 2) {
 //          alert("Great, you have finished the practice mazes!");
   //     }

        document.forms["frm"]["steps"].value = jssteps;       
        document.forms["frm"]["UID"].value = u_id;
        document.forms["frm"]["mazeno"].value = m;
        document.forms["frm"]["mazeID"].value = mnr;
        document.forms["frm"]["path"].value = savedpath;
        document.forms["frm"]["time"].value = savedtime;
        document.forms["frm"]["name"].value = mf; 
}
</script>

<div id="ex1_container">
</div>

</body>
</html> 
