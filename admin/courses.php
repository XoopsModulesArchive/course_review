<?php

require __DIR__ . '/admin_header.php';
  require_once '../class/course.inc.php';
 require_once '../class/department.inc.php';

adminmenu(2, _MD_A_COURSES);

$op = $_GET['op'] ?? '';

    switch ($op) {
    case'editform':
        $course_s = new Course($_GET['cid']);
        include '../include/course_form.inc.php';
        break;
    case'edit':
        $course_s = new Course();

        $course_s->load($_GET['cid']);
        #$course_s->setVar('course_id',$_GET['cid']);
        $course_s->setVar('num', $_POST['cnum']);
        $course_s->setVar('name', $_POST['cname']);
        $course_s->setVar('dept_id', $_POST['dept_id']);
        $course_s->setVar('term', $_POST['term']);
        $course_s->setVar('year', $_POST['year']);
        $course_s->setVar('units', $_POST['units']);
        if ($course_s->store()) {
            redirect_header('courses.php', 2, _CR_ACTIONSDONE);
        }

        break;
    case'add':
        $course_s = new Course();
        $course_s->setVar('num', $_POST['cnum']);
        $course_s->setVar('name', $_POST['cname']);
        $course_s->setVar('dept_id', $_POST['dept_id']);
        $course_s->setVar('term', $_POST['term']);
        $course_s->setVar('year', $_POST['year']);
        $course_s->setVar('units', $_POST['units']);
        if ($course_s->store()) {
            redirect_header('courses.php', 2, _CR_ACTIONSDONE);
        }

        break;
    case'delete':
        Course::delete($_POST['cids']);
            redirect_header('courses.php', 2, _CR_ACTIONSDONE);
        break;
    default:
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $courses = Course::getByDepart($_GET['dept_id']);
        echo '<form action=courses.php method=GET name=dept_form>';
        echo "<select name=dept_id onchange='javascript:dept_form.submit()'>";
        $deps = Department::getAll();

        foreach ($deps as $k => $v) {
            echo "<option value=${v['dept_id']} " . ($v['dept_id'] == $_GET['dept_id'] ? 'selected' : '') . '>' . $v['dept_name'] . "\n";
        }
        echo '<select name=dept_id>';
        echo '</form>';
        OpenTable();

        if (isset($courses['0'])) {
            echo "<form action='courses.php?op=delete' method=POST>";

            echo '<tr class=head><td>' . _CR_COURSEID . '</td>
			<td>' . _CR_COURSENUM . '</td>
			<td>' . _CR_COURSETITLE . '</td>			
			<td>' . _DELETE . '</td></tr>';

            $i = 0;

            foreach ($courses as $course) {
                $class = ($i % 2) ? 'odd' : 'even';

                echo "<tr class=$class><td>" . $course['course_id'] . '</td>
				<td>' . $course['num'] . "</td>
				<td><a href=\"courses.php?op=editform&cid=${course['course_id']}\">" . mb_substr($course['name'], 0, 50) . "</a></td>
				<td><input type=checkbox name=cids[] value='" . $course['course_id'] . "'></td>
				</tr>";
            }

            echo '<tr><td></td><td colspan=2><input type=submit></td></tr>';

            echo '</form>';
        } else {
            echo _CR_THEREISNORECORDS;
        }
        CloseTable();

        include '../include/course_form.inc.php';

        break;
    }
