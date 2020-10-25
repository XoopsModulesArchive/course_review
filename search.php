<?php

require dirname(__DIR__, 2) . '/mainfile.php';
  require XOOPS_ROOT_PATH . '/header.php';
    require __DIR__ . '/include/functions.inc.php';
    if ('prof' == $_POST['searchType'] || 'prof' == $_GET['searchType']) {
        echo cr_breadcrumbs('professors') . '<br>';
    } else {
        echo cr_breadcrumbs('courses') . '<br>';
    }
// DOES THIS ALLOW SQL INJECTION?
  if (isset($_POST['filled']) || isset($_GET['filled'])) {
      $where = ' dp.dept_id=cr.dept_id AND pr.prof_id=rv.prof_id AND
cr.course_id=rv.course_id AND approve = 1';

      $needs_AND = 1;

      $deptandprof = 0;

      if ('POST' == $_SERVER['REQUEST_METHOD']) {
          if (null != $_POST['deptName']) {
              $srch_deptName = $_POST['deptName'];
          }

          if (null != $srch_deptName) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' dp.dept_name="' . $srch_deptName . '"';

              $needs_AND = 1;
          }

          if (null != $_POST['courseNum']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.num= "' . $_POST['courseNum'] . '"';

              $needs_AND = 1;
          }

          if (null != $_POST['pLname']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' pr.lname = "' . $_POST['pLname'] . '"';

              if (0 == $deptandprof) {
                  $where .= ' AND pr.dept_id = dp.dept_id';

                  $deptandprof = 1;
              }

              $needs_AND = 1;
          }

          if (null != $_POST['pFname']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' pr.fname = "' . $_POST['pFname'] . '"';

              if (0 == $deptandprof) {
                  $where .= ' AND pr.dept_id = dp.dept_id';

                  $deptandprof = 1;
              }
          }

          if (null != $_POST['units']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.units = ' . $_POST['units'];
          }

          if ((null != $_POST['term']) and ('all' != $_POST['term'])) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= " cr.term = '$_POST[term]'";
          }

          if (null != $_POST['year']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.year = ' . $_POST['year'];
          }
      } else {
          if (null != $_GET['deptName']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' dp.dept_name = "' . $_GET['deptName'] . '"';

              $needs_AND = 1;
          }

          if (null != $_GET['courseNum']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.num= "' . $_GET['courseNum'] . '"';

              $needs_AND = 1;
          }

          if (null != $_GET['pLname']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' pr.lname = "' . $_GET['pLname'] . '"';

              $needs_AND = 1;
          }

          if (null != $_GET['pFname']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' pr.fname = "' . $_GET['pFname'] . '"';
          }

          if (null != $_GET['units']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.units = ' . $_GET['units'];
          }

          if ((null != $_GET['term']) and ('all' != $_GET['term'])) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= " cr.term = '$_GET[term]'";
          }

          if (null != $_GET['year']) {
              if ($needs_AND) {
                  $where .= ' AND';
              }

              $where .= ' cr.year = ' . $_GET['year'];
          }
      }

      $q = 'SELECT *, count(rv.course_id) as rv_cnt FROM ' . $xoopsDB->prefix('cr_reviews') . ' rv, ' . $xoopsDB->prefix('cr_depts') . ' dp, ' . $xoopsDB->prefix('cr_courses') . ' cr , ' . $xoopsDB->prefix('cr_profs') . " pr  WHERE $where group by cr.course_id";

#     echo $q;

      $result = $xoopsDB->query($q);

      echo $xoopsDB->error();

      if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
          echo "<fieldset>\n";

          echo '   <legend class="blockTitle">' . _CR_SRCHRESULT . "</legend>\n";

          echo "   <div class=\"blockContent\"><br>\n";

          echo '<table>';

          echo '<tr>';

          if ('prof' == $_POST['searchType'] || 'prof' == $_GET['searchType']) {
              echo '<td><b>' . _CR_PROFNAME . "</b></td>\n";

              echo '<td><b>' . _CR_CNTREVIEW . "</b></td>\n";
          } else {
              echo '<td><b>' . _CR_DEPARTCOURSENUM . "</b></td>\n";

              echo '<td><b>' . _CR_CNTREVIEW . "</b></td>\n";
          }

          echo '</tr>';

          while (false !== ($row = $xoopsDB->fetchArray($result))) {
              echo '<tr>';

              if ('prof' == $_POST['searchType'] || 'prof' == $_GET['searchType']) {
                  echo '<td>' . "<a href='professors.php?pid=$row[prof_id]'>" . "$row[fname] $row[lname]" . '</a>' . "</td>\n";

                  #echo "<td>$row[rv_cnt] <a href='reviews.php?profFname=$row[fname]&profLname=$row[lname]'>"._CR_DETAILS."</a></td>\n";

                  echo "<td>$row[rv_cnt] <a href='professors.php?pid=$row[prof_id]'>" . _CR_DETAILS . "</a></td>\n";
              } else {
                  echo '<td>' . "<a href='courses.php?cid=$row[course_id]'>" . "$row[dept_name] $row[num]" . '</a>' . "</td>\n";

                  echo "<td>$row[rv_cnt] <a href='courses.php?cid=$row[course_id]'>Details</a></td>\n";
              }

              echo '</tr>';
          }

          echo '</table>';

          echo "</div></fieldset>\n";

          echo '<p><br></p>';
      }
  }

if (!isset($_POST['filled'])) {
    require_once 'include/searchform_c.inc.php';

    require_once 'include/searchform_prof.inc.php';
}
require_once XOOPS_ROOT_PATH . '/footer.php';
