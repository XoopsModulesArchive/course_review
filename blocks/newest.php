<?php

function cr_show_newest($options)
{
    global $xoopsDB,$xoopsModuleConfig;

    $block = [];

    #$content = "<table bgcolor=\"#dddddd\" rules=\"all\">\n";

    $content = '<ul>';

    $q = "SELECT rev_id, icon, course_review as title, course_id, dept_id, rev_uid, overall, date_format(time, '%m/%d/%y') as my_time FROM " . $xoopsDB->prefix('cr_reviews') . ' WHERE approve = 1 ORDER BY my_time DESC LIMIT ' . $options['1'];

    #		echo $q;

    $result = $xoopsDB->query($q);

    echo $xoopsDB->error($result);

    $block['items'] = [];

    $item = [];

    while ($row = $xoopsDB->fetchArray($result)) {
        # preg_match('/(\w+)/',$row[title],$regs) ;$row[title]=$regs[1];

        #	  substr(eregi_replace("[\n\m]","",$row[title]) $row[title],1,$xoopsModuleConfig['char_limit']);

        #	 $row['title'] = substr ($row['title'],0,$xoopsModuleConfig['char_limit']);

        #	 $row['title'] = substr ($row['title'],0,$options[0]).'...';

        #	  $content = $content . "<tr>\n";

        $icon_file = XOOPS_URL . '/images/subject/' . $row['icon'];

        #	  $content = $content . "<td><img src=\"" . $icon_file . "\"></td>\n";

        $link_loc = XOOPS_URL . '/modules/courseReview/showreview.php?rev_id=' . $row['rev_id'];

        #	  $content = $content . "<td><a href=\"" . $link_loc . "\">" . $row['title'] . "</a>";

        $rating = get_rating($row['rev_id']);

        #	  if ($rating != 0)

        #	  	$content .= "<br>$val</td>\n";

        #	  else

        #	  	$content .= "</td>\n";

        $q_dept = 'SELECT dept_name FROM ' . $xoopsDB->prefix('cr_depts') . ' WHERE dept_id = ' . $row['dept_id'];

        $dept_result = $xoopsDB->query($q_dept);

        $dept_row = $xoopsDB->fetchArray($dept_result);

        $dept_name = $dept_row['dept_name'];

        $q_course = 'SELECT num FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE course_id = ' . $row['course_id'];

        $course_result = $xoopsDB->query($q_course);

        $course_row = $xoopsDB->fetchArray($course_result);

        $course_num = $course_row['num'];

        #	  $content = $content . "<td><a href=\"" . XOOPS_URL . "/modules/courseReview/search.php?filled=true&deptName=" . $dept_name . "&courseNum=" . $course_num . "\">" . $dept_name . " " . $course_num . "</a></td>\n";

        $q_user = 'SELECT uname FROM xoops_users WHERE uid = ' . $row['rev_uid'];

        $user_result = $xoopsDB->query($q_user);

        $user_row = $xoopsDB->fetchArray($user_result);

        $uname = $user_row['uname'];

        #	  $content = $content . "<td><a href=\"" . XOOPS_URL . "/userinfo.php?uid=" . $row['rev_uid'] . "\">" . $uname . "</a></td>\n";

        #	  $content = $content . "<td>" . $row['my_time'] . "</td>\n";

        #	  $content = $content . "</tr>\n";

        # $content .= "<li><a href=\"$link_loc\" title=\"${row['my_time']} by:&nbsp;$uname $dept_name $course_num\">${row['title']}</a></li>";

        $item['link_loc'] = $link_loc;

        $item['my_time'] = $row['my_time'];

        $item['uname'] = $uname;

        $item['dept_name'] = $dept_name;

        $item['course_num'] = $course_num;

        $item['title'] = mb_substr($row['title'], 0, $options[0]) . '...';

        $block['items'][] = $item;
    }

    $content .= '</ul>';

    $content .= 'test';

    #$block['content'] = $content;

    return $block;
}

function cr_edit_latest($options)
{
    global $xoopsDB;

    #   print_r($options);

    $form = "Display <input name=options[] value=${options[0]}> chars<br>";

    $form .= "Display <input name=options[] value=${options[1]}> rows";

    return $form;
}

function get_rating($rev_id)
{
    global $xoopsDB;

    $q = 'SELECT * FROM ' . $xoopsDB->prefix('cr_raitings') . ' WHERE rev_id = ' . $rev_id;

    if ($result = $xoopsDB->query($q)) {
        if (0 != $GLOBALS['xoopsDB']->getRowsNum($result)) {
            $row = $xoopsDB->fetchArray($result);

            $num_useful = $row['num_useful'];

            $total = $row['total'];

            $val = '<font size=2 face=verdana>';

            $val .= "$num_useful out of $total people found this review helpful.";

            return $val;
        }
    }

    return 0;
}
