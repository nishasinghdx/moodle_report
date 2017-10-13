<?php
require_once(dirname(__FILE__) . '/../../config.php');
include("lib.php");
defined('MOODLE_INTERNAL') || die;
echo $OUTPUT->header();
//first graph representing number of users enrolled in different courses
$results=first_graph();

foreach ($results as $result) {
    $coursearr[] = $result->fullname;
    $resultsarr[]=$result->enrolled;
}
?>

<div class="row">
<center>
  <div class="col-sm-6">
    <u><h3>Number Of Users Enrolled In Different Courses</h3></u>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<div style="height:400px;width:400px;">
 <canvas id="myChart" width="400" height="400"></canvas>
 </div>
 <script>
 var ctx = document.getElementById("myChart");
 var myChart = new Chart(ctx, {
     type: 'pie',
     data: {
         labels: [<?php echo '"'.implode('","', $coursearr).'"' ?>],
         datasets: [{
             label: 'Enrolled Students',
             //data: [12, 19, 3, 5, 2, 3],
             data:[<?php echo '"'.implode('","', $resultsarr).'"' ?>],
             backgroundColor: [
                 'rgba(255, 99, 132, 0.2)',
                 'rgba(54, 162, 235, 0.2)',
                 'rgba(255, 206, 86, 0.2)',
                 'rgba(75, 192, 192, 0.2)',
                 'rgba(153, 102, 255, 0.2)',
                 'rgba(255, 159, 64, 0.2)'
             ],
             borderColor: [
                 'rgba(255,99,132,1)',
                 'rgba(54, 162, 235, 1)',
                 'rgba(255, 206, 86, 1)',
                 'rgba(75, 192, 192, 1)',
                 'rgba(153, 102, 255, 1)',
                 'rgba(255, 159, 64, 1)'
             ],
             borderWidth: 1
         }]
     },
     options: {
         scales: {
             yAxes: [{
                 ticks: {
                     beginAtZero:true
                 }
             }]
         }
     }
 });
 </script>
</div>
</center>
<!-- end of col sm 6 div -->










<!-- second graph representing number of attempts of each quiz -->
<?php
echo "</br></br></br></br>";
$countattempts=second_graph();
foreach($countattempts as $countattempt){
  $attempts[] = $countattempt->totalattempts;
  $quiz_name[] = $countattempt->name;
  $avg_grade[] = number_format($countattempt->grade, 2);
}
 ?>
<center>
<div class="col-sm-6">
  <u><h3>Number Of Attempts -vs- Quiz</h3></u>
 <div style="height:400px;width:400px;">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <canvas id="Chart" width="400" height="400"></canvas>
  </div>
  <script>

  var ctx = document.getElementById("Chart");
  var Chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [<?php echo '"'.implode('","', $quiz_name).'"' ?>],
          datasets: [{
              label: 'Total Attempts',
              data:[<?php echo '"'.implode('","', $attempts).'"' ?>],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
          }
      }
  });
  </script>
</div>
</center>
<!-- end of div col sm 6 -->
</div>
 <!-- end of div row -->








<!-- table representing all students record like id,firstname,lastname,coursecount,courses enrolled,average quiz marks,average scorm marks -->

<style media="screen">
.student_record {
  margin: auto;
  width: 70%;
  border: 3px solid green;

}
</style>

<?php echo "<br/><br/><br/><br/>"; ?>
<center>
  <div class="student_record">
<?php
echo " <center> <u><h3>students report</h3></u></center>";
$student_record=third_table();
$table = new html_table();
$table->head = array( 'userid' , 'firstname', 'lastname','coursecount', 'courses enrolled', 'average quiz marks', 'average scorm marks', 'click to view report');
foreach ($student_record as $sr) {
    $userid = $sr->userid;
    $scorm_average=scorm_average($userid);
    $scorm_average_marks =$scorm_average->scorm_average_marks;
    $enrolled_courses=enrolled_courses($userid);
    $firstname = $sr->firstname;
    $lastname = $sr->lastname;
    $coursecount = $sr->coursecount;
    $link = '<a href="quizreport.php?modp=quizreport&id='.$userid.'&firstname='.$firstname.'">Click</a>';
    $sumgrades=number_format($sr->sumgrades, 2);
    $table->data[] = array($userid, $firstname, $lastname, $coursecount, $enrolled_courses, $sumgrades, $scorm_average_marks,$link);
}

echo html_writer::table($table);

 ?>
 </div>
 </center>
<?php echo $OUTPUT->footer(); ?>
