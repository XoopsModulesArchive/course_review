<?php

function cr_show_featured($options)
{
    global $xoopsDB,$xoopsModuleConfig;

    $block = [];

    $content = "<table bgcolor=\"#dddddd\" rules=\"all\">\n";

    $q = "SELECT rev_id, icon, course_id, dept_id, rev_uid, overall, date_format(time, '%m/%d/%y') as my_time FROM " . $xoopsDB->prefix('cr_reviews') . " WHERE feature = 1 ORDER BY my_time DESC LIMIT $xoopsModuleConfig[list_limit]";

    $result = $xoopsDB->query($q);

    while ($row = $xoopsDB->fetchArray($result)) {
        $content .= "<tr>\n";

        $icon_file = XOOPS_URL . '/images/subject/' . $row['icon'];

        $content .= '<td><img src="' . $icon_file . "\"></td>\n";

        $link_loc = XOOPS_URL . '/modules/courseReview/showreview.php?rev_id=' . $row['rev_id'];

        #	  $content = $content . "<td><a href=\"" . $link_loc . "\">" . $row['title'] . "</a>";

        $q_dept = 'SELECT dept_name FROM ' . $xoopsDB->prefix('cr_depts') . ' WHERE dept_id = ' . $row['dept_id'];

        $dept_result = $xoopsDB->query($q_dept);

        $dept_row = $xoopsDB->fetchArray($dept_result);

        $dept_name = $dept_row['dept_name'];

        $q_course = 'SELECT num FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE course_id = ' . $row['course_id'];

        $course_result = $xoopsDB->query($q_course);

        $course_row = $xoopsDB->fetchArray($course_result);

        $course_num = $course_row['num'];

        $content .= '<td><a href="' . XOOPS_URL . '/modules/courseReview/search.php?filled=true&deptName=' . $dept_name . '&courseNum=' . $course_num . '">' . $dept_name . ' ' . $course_num . "</a></td>\n";

        $q_user = 'SELECT uname FROM xoops_users WHERE uid = ' . $row['rev_uid'];

        $user_result = $xoopsDB->query($q_user);

        $user_row = $xoopsDB->fetchArray($user_result);

        $uname = $user_row['uname'];

        $content .= '<td><a href="' . XOOPS_URL . '/userinfo.php?uid=' . $row['rev_uid'] . '">' . $uname . "</a></td>\n";

        $content .= '<td>' . $row['my_time'] . "</td>\n";

        $content .= "</tr>\n";
    }

    $content .= '</table>';

    $block['content'] = $content;

    return $block;
}

function cr_edit_featured($options)
{
    global $xoopsDB;

    $form = [];

    return $form;
}
