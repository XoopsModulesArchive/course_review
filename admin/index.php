<?php

require __DIR__ . '/admin_header.php';
 require_once dirname(__DIR__) . '/include/functions.inc.php';

function moderate_index()
{
    global $xoopsDB;

    # start moderate block

    $where = ' ' . $xoopsDB->prefix('cr_depts') . '.dept_id=' . $xoopsDB->prefix('cr_reviews') . '.dept_id AND ' . $xoopsDB->prefix('cr_profs') . '.prof_id=' . $xoopsDB->prefix('cr_reviews') . '.prof_id AND ' . $xoopsDB->prefix('cr_courses') . '.course_id=' . $xoopsDB->prefix('cr_reviews') . '.course_id AND ' . $xoopsDB->prefix('cr_reviews') . '.approve=0';

    $q = "SELECT *, date_format(time, '%m/%d/%y') as my_time FROM " . $xoopsDB->prefix('cr_reviews') . ', ' . $xoopsDB->prefix('cr_depts') . ', ' . $xoopsDB->prefix('cr_courses') . ', ' . $xoopsDB->prefix('cr_profs') . ' WHERE' . $where . ' ORDER BY ' . $xoopsDB->prefix('cr_depts') . '.dept_name ASC';

    $result = $xoopsDB->query($q);

    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $content = _MD_A_ZERO_APPROVE_LET . "<p>\n";

        $content .= '<table class="outer">';

        $content .= "<tr>\n<th>ID</th>\n";

        $content .= '<th>' . _MD_A_ICON . "</th>\n";

        $content .= '<th>' . _TITLE . "</th>\n";

        $content .= '<th>' . _MD_A_DEPT_COURSE_NUM . "</th>\n";

        $content .= '<th>' . _MD_A_REVIEWER . "</th>\n";

        $content .= '<th>' . _DATE . "</th>\n";

        $content .= '<th>' . _MD_A_APPROVED . "</th>\n";

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

            $content .= '<tr>';

            $content .= "<td class=\"$class\">" . $row['rev_id'] . "</td>\n";

            $content .= "<td class=\"$class\"><img src=\"" . XOOPS_URL . '/images/subject/' . $row['icon'] . "\"></td>\n";

            $content .= "<td class=\"$class\"><a href=\"/xoops/html/modules/courseReview/showreview.php?rev_id=" . $row['rev_id'] . '">' . $row['title'] . "</a></td>\n";

            $content .= "<td class=\"$class\">" . $row_dept['dept_name'] . ' ' . $row_course['num'] . "</td>\n";

            $content .= "<td class=\"$class\"><a href=\"" . XOOPS_URL . '/userinfo.php?uid=' . $row['rev_uid'] . '">' . $uname . "</a></td>\n";

            $content .= "<td class=\"$class\">" . $row['my_time'] . "</td>\n";

            $content .= "<td class=\"$class\">" . $row['approve'] . "</td>\n";

            $content .= '</tr>';

            $count += 1;
        }

        $content .= '</table>';

        $content .= '<br>';

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $content .= "<form method=\"post\" action=\"index.php\">\n";

        $content .= "<table class=\"outer\">\n";

        $content .= '<tr><th colspan="2">' . _MD_A_CHANGE_APP_ST . '</th></tr>';

        $content .= "<tr><td class=\"$class\">" . _MD_A_REVIEW_ID . '#</td>';

        $content .= "<td class=\"$class\"><input type=\"text\" name=\"rev_id\"></td></tr>";

        $count += 1;

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $content .= "<tr><td class=\"$class\">" . _MD_A_TYPE_CHANGE . '</td>';

        $content .= "<td class=\"$class\">\n";

        $content .= "<table>\n";

        $content .= '<tr><td>' . _MD_A_APPROVE . "</td><td><input type=\"radio\" name=\"newval\" value=\"1\"></td></tr>\n";

        //$content .="<tr><td>Deny</td><td><input type=\"radio\" name=\"newval\" value=\"0\"></td></tr>\n";

        $content .= '<tr><td>' . _DELETE . "</td><td><input type=\"radio\" name=\"newval\" value=\"0\"></td></tr>\n";

        $content .= "</table>\n";

        $content .= '</td></tr>';

        $count += 1;

        if (0 == $count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $content .= "<tr><td class=\"$class\" colspan=\"2\" align=\"center\">\n";

        $content .= "<input type=\"hidden\" name=\"filled\" value=\"true\">\n";

        $content .= "<input type=\"submit\" ></td></tr>\n";

        $content .= "</table>\n";

        $content .= '</form>';
    } else {
        $content .= _MD_A_NO_BAD_REVIEW . "\n";
    }

    return $content;
    # stop moderate block
}

function index()
{
    global $xoopsDB;

    # start Reportings blok

    $quer = sprintf(
        'select r.id as id ,r.rid as rid , r.mess as mess ,  a.rev_id as lid , u.uid as uid , u.uname as uname from %s r , %s a  , %s u where a.rev_id=r.lid and a.rev_uid= u.uid',
        $xoopsDB->prefix('cr_reporting'),
        $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('users')
    );

    #	echo $quer;

    $result = $xoopsDB->query($quer);

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        $content .= sprintf(_MD_A_THERE_ARE_REPORTS . '<br><br>', "<b>$numrows</b>");

        $content .= "<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=0 BORDER=0><form action='index.php?op=DeleteRep' method=POST>";

        $rank = 1;

        $content .= "<TR border=1 class='bg3'><td></td><TD width=300>" . _MD_A_MESSAGE . '</TD><TD width=200 align=center>' . _MD_A_REVIEW_TITLE . '</TD><TD></TD><TD align=right>' . _MD_A_REPORTEDBY . '</TD></TR>';

        while (list($id, $rid, $mess, $lid, $uid, $uname) = $xoopsDB->fetchRow($result)) {
            #$title = htmlspecialchars($title);

            #$date2 = formatTimestamp($date,"s");

            if (is_int($rank / 2)) {
                $color = 'bg4';
            } else {
                $color = 'bg1';
            }

            # if ($rid<99){$mess=$modreasons[$rid];}

            $content .= "<TR border=1 class='$color'><td><input type=checkbox name='delitems[]' value=$id></td><TD width=300>$mess</TD><TD width=200 align=center><a href='" . XOOPS_URL . "/modules/courseReview/showreview.php?rev_id=$lid'>$title</a></TD><TD><A HREF=\"index.php?op=AnnoncesModAnnonce&lid=$lid\">" . _EDIT . "</A></TD><TD align=right>$uname</TD></TR>";

            $rank++;
        }

        $content .= "<input type=hidden name=part value='reporting'";

        $content .= "<tr><td colspan=5><input type=submit value='" . _MD_A_DELETESELREPS . "' </td></tr>
			</form></TABLE>";

        CloseTable();

        $content .= '<br>';
    } else {
        $content .= _MD_A_NOMODREQ;
    }

    return $content;
    # stop Reporting blok
}
if (!isset($op)) {
    $op = '';
}
if ('reporting' == $part) {
    switch ($op) {
    case 'DeleteRep':
    foreach ($delitems as $item) {
        $quer = sprintf("delete from %s where id=$item", $xoopsDB->prefix('cr_reporting'));

        $xoopsDB->query($quer);
    }
    redirect_header('index.php', 1, _MD_A_DB_HAS_UPDATED);
    exit();
    break;
}
} else {
    $table = $xoopsDB->prefix('cr_reviews');

    if (isset($_POST['newval']) && 0 == $_POST['newval'] && isset($_POST['filled'])) {
        echo _MD_A_DELRV_CONF . ' #' . $_POST['rev_id'] . "???&nbsp;&nbsp;\n";

        echo "<form action=\"index.php\" method=\"POST\">\n";

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

        redirect_header('index.php', 1, _MD_A_APPROVED_RV . ' id #' . $_POST['rev_id']);
    } else {
        if (isset($_POST['delete']) && 'yes' == $_POST['delete']) {
            $q = "DELETE FROM $table WHERE rev_id = " . $_POST['rev_id'];
#    echo  $q;

            $xoopsDB->query($q);

            xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['rev_id']);

            redirect_header('index.php', 1, sprintf(_MD_A_DELRV_WARN, $_POST['rev_id']));
        } else {
            if (isset($_POST['delete']) && 'no' == $_POST['delete']) {
                echo sprintf(_MD_A_CANT_DELETE_DAMN . "\n", $_POST['rev_id']);

                redirect_header('index.php', 1, sprintf(_MD_A_CANT_DELETE_DAMN . "\n", $_POST['rev_id']));

                exit();
            }

            adminmenu(0, _MD_A_INDEX);

            cr_fieldset(_MD_A_MODERATIONQUE, index());

            cr_fieldset(_MD_A_APP_DENY_RVS, moderate_index());

            xoops_cp_footer();

            exit();
        }
    }
} # else if reporting
