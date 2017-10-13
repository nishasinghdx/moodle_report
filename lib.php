<?php
require_once(dirname(__FILE__).'/../../config.php');

  function first_graph(){
     global $DB;
    $results=$DB->get_records_sql('SELECT cr.shortname, cr.fullname, count( ra.id ) AS enrolled
    FROM `mdl_course` cr
    JOIN `mdl_context` ct ON ( ct.instanceid = cr.id )
    LEFT JOIN `mdl_role_assignments` ra ON ( ra.contextid = ct.id )
    WHERE ct.contextlevel =50 AND cr.id != 1
    GROUP BY cr.shortname, cr.fullname'
    );
    return $results;
  }

  function second_graph(){
    global $DB;
    $countattempts=$DB->get_records_sql("SELECT  q.id, sum(qa.attempt) as totalattempts,AVG(qg.grade) as grade,  q.name FROM {quiz_attempts} qa
    JOIN {quiz} q ON q.id = qa.quiz
    LEFT JOIN {quiz_grades} qg ON qg.quiz = q.id
    GROUP BY q.id");
    return $countattempts;
  }

  function third_table(){
    global $DB;
    $student_record=$DB->get_records_sql("SELECT u.id AS userid, u.firstname, u.lastname, count(c.id) AS coursecount, c.fullname, AVG(qa.sumgrades) as sumgrades
    FROM mdl_course AS c
    JOIN mdl_context AS ctx ON c.id = ctx.instanceid
    JOIN mdl_role_assignments AS ra ON ra.contextid = ctx.id
    JOIN mdl_user AS u ON u.id = ra.userid
    JOIN {quiz_attempts} qa ON u.id=qa.userid
    JOIN {quiz} q ON q.id = qa.quiz
    GROUP BY  u.id
    ");
    return $student_record;
  }

  function student_quiz_report($id){
    global $DB;
    $countattempts=$DB->get_records_sql("SELECT  q.id, sum(qa.attempt) as totalattempts, qa.sumgrades as sumgrades,  q.name FROM {quiz_attempts} qa
    JOIN {quiz} q ON q.id = qa.quiz
    LEFT JOIN {quiz_grades} qg ON qg.quiz = q.id
    JOIN {user} u ON u.id = qa.userid
    WHERE qa.userid=$id GROUP BY q.id");
    return $countattempts;
  }

  function student_scorm_report($id){
    global $DB;
    $scorm_report=$DB->get_records_sql("SELECT  s.name, sst.value FROM {scorm_scoes_track} sst
    JOIN {scorm} s ON s.id = sst.scormid
    WHERE sst.userid=$id AND sst.element='cmi.core.score.raw'
    ");
    return $scorm_report;
  }

  function scorm_average($id){
    global $DB;
    $scorm_average=$DB->get_record_sql("SELECT AVG(value) as scorm_average_marks
    FROM {scorm_scoes_track}
    WHERE element='cmi.core.score.raw' AND userid=$id
    ");
    return $scorm_average;
  }

  function enrolled_courses($id){
    global $DB;
    $enrolled_courses=$DB->get_records_sql("SELECT c.fullname
      FROM {course} c
      JOIN {enrol} e ON e.courseid=c.id
      JOIN {user_enrolments} ue ON ue.enrolid=e.id
      WHERE ue.userid=$id
    ");
    $enrolled_courses = array_keys($enrolled_courses);
    $enrolled_courses= implode(',', $enrolled_courses);
    return $enrolled_courses;
  }

 ?>
