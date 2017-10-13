<style media="screen">
h3 {
  text-align: center;
}
</style>
<?php
require_once(dirname(__FILE__) . '/../../config.php');
include("lib.php");
defined('MOODLE_INTERNAL') || die;
echo $OUTPUT->header();

if(isset($_GET['id'])){
    $id=$_GET['id'];
}
if(isset($_GET['firstname'])){
   $firstname=$_GET['firstname'];
}

$countattempts=student_quiz_report($id);
foreach($countattempts as $countattempt){
  $attempts[] = $countattempt->totalattempts;
  $quiz_name[] = $countattempt->name;
  $sumgrades[] = number_format($countattempt->sumgrades, 2);
 }
 $total_sumgrades= array_sum($sumgrades);
$average_sumgrades = array_sum($sumgrades)/count($sumgrades);

echo "<u><h3>$firstname"."- quiz grade chart</h3></u>";
echo "total marks of quiz:$total_sumgrades";
echo "</br></br></br>";
echo "average marks of quiz:$average_sumgrades";

 ?>

<center>
<div style="height:400px;width:400px;">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
 <canvas id="report" width="400" height="400"></canvas>
 </div>
 <script>

 var ctx = document.getElementById("report");
 var report = new Chart(ctx, {
     type: 'bar',
     data: {
         labels: [<?php echo '"'.implode('","', $quiz_name).'"' ?>],
         datasets: [{
             label: 'grades',

             data:[<?php echo '"'.implode('","', $sumgrades).'"' ?>],

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
             borderWidth: 1,

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
 </center>








<?php
echo "</br></br></br></br>";
$scorm_report=student_scorm_report($id);
foreach($scorm_report as $sc){
  $scorm_name_arr[] = $sc->name;
  $scorm_value_arr[] = $sc->value;
 }
 ?>
 <?php
 echo "<u><h3>$firstname"."- scorm report</h3></u>";
  ?>
 <center>
 <div style="height:400px;width:400px;">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <canvas id="scorm" width="400" height="400"></canvas>
  </div>
  <script>

  var ctx = document.getElementById("scorm");
  var scorm = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [<?php echo '"'.implode('","', $scorm_name_arr).'"' ?>],
          datasets: [{
              label: 'status',
              data:[<?php echo '"'.implode('","', $scorm_value_arr).'"' ?>],

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
              borderWidth: 1,

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
  </center>
  <?php echo $OUTPUT->footer(); ?>
