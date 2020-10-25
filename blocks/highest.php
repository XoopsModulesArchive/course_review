<?php

function cr_show_highest($options)
{
    global $xoopsDB;

    $block = [];

    $content = "<table bgcolor=\"#dddddd\" rules=\"all\">\n";

    $q = 'SELECT course_id, AVG(overall) as score from ' . $xoopsDB->prefix('cr_reviews') . " GROUP BY 'course_id' ORDER BY score DESC LIMIT 10";

    $result = $xoopsDB->query($q);

    while ($row = $xoopsDB->fetchArray($result)) {
        $content .= "<tr>\n";

        $q_course = 'SELECT dept_id, num, term, year FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE course_id = ' . $row['course_id'];

        $course_result = $xoopsDB->query($q_course);

        $course_row = $xoopsDB->fetchArray($course_result);

        $course_num = $course_row['num'];

        $dept_id = $course_row['dept_id'];

        $q_dept = 'SELECT dept_name FROM ' . $xoopsDB->prefix('cr_depts') . ' WHERE dept_id = ' . $dept_id;

        $dept_result = $xoopsDB->query($q_dept);

        $dept_row = $xoopsDB->fetchArray($dept_result);

        $dept_name = $dept_row['dept_name'];

        $content .= '<td><a href="' . XOOPS_URL . '/modules/courseReview/search.php?filled=true&deptName=' . $dept_name . '&courseNum=' . $course_num . '">' . $dept_name . ' ' . $course_num . "</a></td>\n";

        $content .= '<td>' . $course_row['term'] . ' ' . $course_row['year'] . "</td>\n";

        $content .= '<td>' . $row['score'] . "</td>\n";

        $content .= "</tr>\n";
    }

    $content .= '</table>';

    $block['content'] = $content;

    return $block;
}

function cr_edit_highest($options)
{
    global $xoopsDB;

    $form = [];

    return $form;
}
