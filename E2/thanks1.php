 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thank you!</title>
<style>
#ex2_container { text-align:center; font-size:120%;}
</style>
</head>

 
<body>
<div id="ex2_container">

<h1>Bad Request</h1>
</div>

<script>
var r = 10;
var theend = 0;
var uid =  dodgyuser;
var validreq = 0;
var ruler = 0;
 
function makeRuler() {
  var s = " <table align='center' style='width:absolute;widthoat:center'><tr>";
  var min_score = 0;
  var max_score = 10;

  var td2 = "";
  step = 1; 

  for (curstep=min_score; curstep< 10; curstep += step) {
     s += "<td bgcolor='#";
     if (r == curstep ) {
       s += "ff0000'><img src='agent.png' style='width:50px;height:50px;display: block;'>";
     } else {
       s += "ffffff'>" + "<img src='mug.png' style='width:50px;height:50px;display: block;'>"//curstep;
     }
     td2 += "<td>" + order + "</td>";
     s +=  "</td>";
  }
  
  s += "</tr>" + "<tr>" + td2 + "</tr></table>";
  
  return s;
}

if ( ruler == 1) {
         ruler = "<h2>Based on past data, this is how well you did.<br>We will calculate your final bonus after we finish the experiment.</h2>"  +
                  "<p><font size = '4'>Please paste the following code into the original HIT before submitting it:" +  uid + "</font></p>" +  
                   "Your rank reflects the difference between the smallest number of steps in which the mazes can be solved" + 
                   " by rational decision-making and the number of steps you took. <br><br> " +  
                   makeRuler(); 
         boilerplate = "<p><small> If you are interested in receiving more information regarding the results of this study" + 
                       " please please contact Tomer Ullman at tomeru@mit.edu.</small></p>";

         document.getElementById("ex2_container").innerHTML = "<br>" + 
                 ruler +
                 "The experiment is over, but if you want to learn more about it, read on. In this experiment we try to assess how people solve a simple task that can require planning and thinking ahead. We plan to compare how people make decisions (for this maze task) with how other people evaluated these decisions." + 
                 boilerplate;
}

function validateForm() {
	document.forms["frm"]["UID"].value = uid;
        var x = document.forms["frm"]["decision"].value;
        document.forms["frm"]["rank"].value = r;
        if (x == null || x == "" || x == 0) {
          alert("Please answer the question to proceed." + x);
          return false;
       }
       return true;
}
</script>
</body>
</html>

