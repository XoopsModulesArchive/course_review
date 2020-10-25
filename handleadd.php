<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';
#  print_r($_SESSION);

  require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once 'class/department.inc.php';
    require_once 'class/course.inc.php';
    require_once 'class/professor.inc.php';
    require_once 'class/review.inc.php';

        $course_s = new Course();
        $depart_s = new Department();

        $prof_s = new Professor();
        $review_s = new Review();

 if (!is_object($xoopsUser)) {
     redirect_header(XOOPS_URL . '/user.php', 5, _NOPERM);

     exit();
 }
   $depart_s->loadFromSession();
   $course_s->loadFromSession();
   $prof_s->loadFromSession();
   $review_s->loadFromSession();

if (!$_GET['back']) {
    $depart_s->setVar('dept_id', $_POST['dep_id']);

    $table = $xoopsDB->prefix('cr_depts');

    $q = 'SELECT dept_name FROM ' . $table . " where dept_id=$_POST[dep_id] ORDER BY dept_name ASC";

    $result = $xoopsDB->query($q);

    $row = $xoopsDB->fetchArray($result);

    $depart_s->setVar('dept_name', $row['dept_name']);

    $depart_s->storeToSession();

    $course_s->setVar('term', $_POST['term']);

    $course_s->setVar('year', $_POST['year']);

    $course_s->setVar('units', $_POST['units']);

    $course_s->setVar('num', $_POST['courseNum']);

    if (!$course_s->storeToSession()) {
        echo $course_s->getHtmlErrors();
    }

    $SESSION_VAR['uname'] = $xoopsUser->uname('S');

    $SESSION_VAR['ufrom'] = $xoopsUser->user_from('S');
}

  echo '<h1>' . _CR_COURSEREVIEW . '</h1>';
  OpenTable();
  echo '<h3>' . $depart_s->getVar('dept_name') . ' ' . $course_s->getVar('num') . '</h3>';
  echo '<b>' . _CR_REVIEWEDBY . ':</b> ' . $xoopsUser->uname('S') . '<br>';
  echo '<b>' . _CR_FROM . ':</b> ' . $xoopsUser->user_from('S') . '<br>';
  CloseTable();

  include 'include/addform_cr.inc.php';

  require_once XOOPS_ROOT_PATH . '/footer.php';



