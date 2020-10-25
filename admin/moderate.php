<?php

require __DIR__ . '/admin_header.php';
adminmenu(3, _MD_A_APP_DENY_RVS);
  $table = $xoopsDB->prefix('cr_reviews');
  if (isset($_POST['newval']) && 0 == $_POST['newval'] && isset($_POST['filled'])) {
      echo _MD_A_DELRV_CONF . ' #' . $_POST['rev_id'] . "???&nbsp;&nbsp;\n";

      echo "<form action=\"moderate.php\" method=\"POST\">\n";

      echo '<input type="hidden" name="rev_id" value="' . $_POST['rev_id'] . "\">\n";

      echo '<input type="checkbox" name="delete" value="no">' . _NO . "&nbsp;\n";

      echo '<input type="checkbox" name="delete" value="yes">' . _YES . "&nbsp;\n";

      echo "<input type=\"submit\" >\n";

      echo "</form>\n";

      xoops_cp_footer();

      exit();
  }
      if (isset($_POST['newval']) && 1 == $_POST['newval'] && isset($_POST['filled'])) {
          $table = $xoopsDB->prefix('cr_reviews');

          $q = "UPDATE $table SET approve = 1 WHERE rev_id = " . $_POST['rev_id'];

          $xoopsDB->query($q);

          echo _MD_A_APPROVED_RV . ' id #' . $_POST['rev_id'] . ".<p>\n";
      } else {
          if (isset($_POST['delete']) && 'yes' == $_POST['delete']) {
              $q = "DELETE FROM $table WHERE rev_id = " . $_POST['rev_id'];
#    echo $q;

              $xoopsDB->query($q);

              echo sprinf(_MD_A_DELRV_WARN, $_POST['rev_id']);

              xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['rev_id']);
          } else {
              if (isset($_POST['delete']) && 'no' == $_POST['delete']) {
                  echo sprintf(_MD_A_CANT_DELETE_DAMN . "\n", $_POST['rev_id']);

                  echo '<a href="moderate.php">' . _MD_A_GO_BACK_MODERATE . "</a>\n";

                  xoops_cp_footer();

                  exit();
              }
          }
      }

  $where = ' ' . $xoopsDB->prefix('cr_depts') . '.dept_id=' . $xoopsDB->prefix('cr_reviews') . '.dept_id AND ' . $xoopsDB->prefix('cr_profs') . '.prof_id=' . $xoopsDB->prefix('cr_reviews') . '.prof_id AND ' . $xoopsDB->prefix('cr_courses') . '.course_id=' . $xoopsDB->prefix('cr_reviews') . '.course_id AND ' . $xoopsDB->prefix('cr_reviews') . '.approve=0';
  $q = "SELECT *, date_format(time, '%m/%d/%y') as my_time FROM " . $xoopsDB->prefix('cr_reviews') . ', ' . $xoopsDB->prefix('cr_depts') . ', ' . $xoopsDB->prefix('cr_courses') . ', ' . $xoopsDB->prefix('cr_profs') . ' WHERE' . $where . ' ORDER BY ' . $xoopsDB->prefix('cr_depts') . '.dept_name ASC';
    $result = $xoopsDB->query($q);
    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        echo _MD_A_ZERO_APPROVE_LET . "<p>\n";

        echo '<table class="outer">';

        echo "<tr>\n<th>ID</th>\n";

        echo '<th>' . _MD_A_ICON . "</th>\n";

        echo '<th>' . _TITLE . "</th>\n";

        echo '<th>' . _MD_A_DEPT_COURSE_NUM . "</th>\n";

        echo '<th>' . _MD_A_REVIEWER . "</th>\n";

        echo '<th>' . _DATE . "</th>\n";

        echo '<th>' . _MD_A_APPROVED . "</th>\n";

        $count = 0;

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            if (0 == $count % 2) {
                $class = 'even';
            } else {
                $class = 'odd';
            }

            $q_deptName = 'SELECT dept_name FROM ' . $xoopsDB->prefix('cr_depts') . ' WHERE dept_id = ' . $row['dept_id'];

            $res_dept = $xoopsDB->query($q_deptName);

            $row_dept = $xoopsDB->fetchArray($res_dept);

            $q_courseNum = 'SELECT num FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE course_id = ' . $row['course_id'];

            $res_course = $xoopsDB->query($q_courseNum);

            $row_course = $xoopsDB->fetchArray($res_course);

            $q_user = 'SELECT uname FROM xoops_users WHERE uid = ' . $row['rev_uid'];

            $user_result = $xoopsDB->query($q_user);

            $user_row = $xoopsDB->fetchArray($user_result);

            $uname = $user_row['uname'];

            echo '<tr>';

            echo "<td class=\"$class\">" . $row['rev_id'] . "</td>\n";

            echo "<td class=\"$class\"><img src=\"" . XOOPS_URL . '/images/subject/' . $row['icon'] . "\"></td>\n";

            echo "<td class=\"$class\"><a href=\"/xoops/html/modules/courseReview/showreview.php?rev_id=" . $row['rev_id'] . '">' . $row['title'] . "</a></td>\n";

            echo "<td class=\"$class\">" . $row_dept['dept_name'] . ' ' . $row_course['num'] . "</td>\n";

            echo "<td class=\"$class\"><a href=\"" . XOOPS_URL . '/userinfo.php?uid=' . $row['rev_uid'] . '">' . $uname . "</a></td>\n";

            echo "<td class=\"$class\">" . $row['my_time'] . "</td>\n";

            echo "<td class=\"$class\">" . $row['approve'] . "</td>\n";

            echo '</tr>';

            $count += 1;
        }

        echo '</table>';

        echo '<br>';

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        echo "<form method=\"post\" action=\"moderate.php\">\n";

        echo "<table class=\"outer\">\n";

        echo '<tr><th colspan="2">' . _MD_A_CHANGE_APP_ST . '</th></tr>';

        echo "<tr><td class=\"$class\">" . _MD_A_REVIEW_ID . '#</td>';

        echo "<td class=\"$class\"><input type=\"text\" name=\"rev_id\"></td></tr>";

        $count += 1;

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        echo "<tr><td class=\"$class\">" . _MD_A_TYPE_CHANGE . '</td>';

        echo "<td class=\"$class\">\n";

        echo "<table>\n";

        echo '<tr><td>' . _MD_A_APPROVE . "</td><td><input type=\"radio\" name=\"newval\" value=\"1\"></td></tr>\n";

        //echo "<tr><td>Deny</td><td><input type=\"radio\" name=\"newval\" value=\"0\"></td></tr>\n";

        echo '<tr><td>' . _DELETE . "</td><td><input type=\"radio\" name=\"newval\" value=\"0\"></td></tr>\n";

        echo "</table>\n";

        echo '</td></tr>';

        $count += 1;

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        echo "<tr><td class=\"$class\" colspan=\"2\" align=\"center\">\n";

        echo "<input type=\"hidden\" name=\"filled\" value=\"true\">\n";

        echo "<input type=\"submit\" ></td></tr>\n";

        echo "</table>\n";

        echo '</form>';
    } else {
        echo _MD_A_NO_BAD_REVIEW . "\n";
    }
  xoops_cp_footer();
  exit();
