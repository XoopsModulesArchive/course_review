<?php

require __DIR__ . '/admin_header.php';

adminmenu(2, _MD_A_FEATURED_RVS);

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>


             " . _MD_A_FEATURED_RVS . '</legend>';

        echo "<br><br><table width='100%' border='0' cellspacing='1' class='outer'>


             <tr><td class=\"odd\">";

  if (isset($_POST['filled'])) {
      $table = $xoopsDB->prefix('cr_reviews');

      $q = "UPDATE $table SET feature = " . $_POST['newval'] . ' WHERE rev_id = ' . $_POST['rev_id'];

      $xoopsDB->query($q);
  }

  $where = ' ' . $xoopsDB->prefix('cr_depts') . '.dept_id=' . $xoopsDB->prefix('cr_reviews') . '.dept_id AND ' . $xoopsDB->prefix('cr_profs') . '.prof_id=' . $xoopsDB->prefix('cr_reviews') . '.prof_id AND ' . $xoopsDB->prefix('cr_courses') . '.course_id=' . $xoopsDB->prefix('cr_reviews') . '.course_id';

  $q = "SELECT *, date_format(time, '%m/%d/%y') as my_time FROM " . $xoopsDB->prefix('cr_reviews') . ', ' . $xoopsDB->prefix('cr_depts') . ', ' . $xoopsDB->prefix('cr_courses') . ', ' . $xoopsDB->prefix('cr_profs') . ' WHERE' . $where . ' ORDER BY ' . $xoopsDB->prefix('cr_depts') . '.dept_name ASC';

    $result = $xoopsDB->query($q);

    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        echo '<table class="outer">';

        echo "<tr>\n<th>ID</th>\n";

        echo '<th>' . _MD_A_ICON . "</th>\n";

        echo '<th>' . _MD_A_REVIEW_TITLE . "</th>\n";

        echo '<th>' . _MD_A_DEPT_COURSE_NUM . "</th>\n";

        echo '<th>' . _MD_A_REVIEWER . "</th>\n";

        echo '<th>' . _DATE . "</th>\n";

        echo '<th>' . _MD_A_FEATURED . "</th>\n";

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

            echo "<td class=\"$class\"><a href=\"../showreview.php?rev_id=" . $row['rev_id'] . '">' . $row['title'] . "</a></td>\n";

            echo "<td class=\"$class\">" . $row_dept['dept_name'] . ' ' . $row_course['num'] . "</td>\n";

            echo "<td class=\"$class\"><a href=\"" . XOOPS_URL . '/userinfo.php?uid=' . $row['rev_uid'] . '">' . $uname . "</a></td>\n";

            echo "<td class=\"$class\">" . $row['my_time'] . "</td>\n";

            echo "<td class=\"$class\">" . $row['feature'] . "</td>\n";

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

        echo "<form method=\"post\" action=\"featured.php\">\n";

        echo "<table class=\"outer\">\n";

        echo '<tr><th colspan="2">' . _MD_A_CHANGE_FT_ST . '</th></tr>';

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

        echo '<tr><td>' . _MD_A_FEATURE . "</td><td><input type=\"radio\" name=\"newval\" value=\"1\"></td></tr>\n";

        echo '<tr><td>' . _MD_A_UFEATURE . "</td><td><input type=\"radio\" name=\"newval\" value=\"0\"></td></tr>\n";

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
    }

  echo '</td></tr></table>';

        echo '</fieldset>';

  xoops_cp_footer();

  exit();
