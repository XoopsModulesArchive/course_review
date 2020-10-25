<?php

require __DIR__ . '/admin_header.php';
   require_once dirname(__DIR__) . '/include/functions.inc.php';

adminmenu(1, _MD_A_APP_DENY_RVS);

$op = $_POST['op'] ?? $_GET['op'] ?? '';
require_once '../class/department.inc.php';
require_once '../class/course.inc.php';
$depart_s = new Department();

// Add the dept if it doesn't exist already

if ('add' == $op) {
    if (!$depart_s->getByName($_POST['name'])) {
        $depart_s->setVar('dept_name', $_POST['name']);

        $depart_s->store();
    }

    redirect_header('edit_dept.php', 1, _MD_A_DB_HAS_UPDATED);

    exit();
}

// DEPARTMENT CONDITION:start
// Modify the dept to a new value
if ('save' == $op) {
    print_r($_POST);

    if (isset($_POST['remove'])) {
        $depart_s->setVar('dept_id', $_POST['dept_id']);

        $depart_s->delete();
    } else {
        $depart_s->setVar('dept_name', $_POST['newval']);

        $depart_s->setVar('dept_id', $_POST['dept_id']);

        $depart_s->store();
    }

    redirect_header('edit_dept.php', 1, _MD_A_DB_HAS_UPDATED);

    exit();
}

// Show the Modify menu

if ('modify' == $op) {
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    #require_once XOOPS_ROOT_PATH.'/class/xoopsform/tableform.php';

    $depart_s->load($dept_id);

    $form = new XoopsThemeForm('<b>' . _MD_A_MODIRY_DEPARTMENT . '</b>', 'modify_form', 'edit_dept.php');

    $new_val = new XoopsFormText(_MD_A_DEPARMENT, 'newval', 35, 30, $value = $depart_s->getVar('dept_name'));

    $form->addElement($new_val);

    $hidden_save = new XoopsFormHidden('op', 'save');

    $form->addElement($hidden_save);

    $hidden_dept_id = new XoopsFormHidden('dept_id', $depart_s->getVar('dept_id'));

    $form->addElement($hidden_dept_id);

    $btray = new XoopsFormElementTray('', $delimeter = '&nbsp;');

    $submit_b = new XoopsFormButton('', 'submit', _MD_A_MODIFY, $type = 'submit');

    $remove_b = new XoopsFormButton('', 'remove', _MD_A_REMOVE, $type = 'submit');

    $btray->addElement($submit_b);

    $btray->addElement($remove_b);

    $form->addElement($btray);

    echo $form->render();

    cr_fieldset(_MD_A_COURSES, courses_index());

    #	echo "<a href='courses.php?dept_id=".$depart_s->getVar('dept_id')."'>"._CR_COURSES."</a>";
}

if ('ceditform' == $op) {
    $course_s = new Course($_GET['cid']);

    include '../include/course_form.inc.php';
}
if ('cedit' == $op) {
    $course_s = new Course();

    $course_s->load($_GET['cid']);

    #$course_s->setVar('course_id',$_GET['cid']);

    $course_s->setVar('num', $_POST['cnum']);

    $course_s->setVar('name', $_POST['cname']);

    $course_s->setVar('dept_id', $_POST['dept_id']);

    $course_s->setVar('term', $_POST['term']);

    $course_s->setVar('year', $_POST['year']);

    $course_s->setVar('units', $_POST['units']);

    $course_s->setVar('cteaser', $_POST['cteaser']);

    $course_s->setVar('creview', $_POST['creview']);

    if ($course_s->store()) {
        redirect_header('edit_dept.php?op=modify&dept_id=' . ($course_s->getVar('dept_id')), 2, _CR_ACTIONSDONE);
    }
}
if ('cadd' == $op) {
    $course_s = new Course();

    $course_s->setVar('num', $_POST['cnum']);

    $course_s->setVar('name', $_POST['cname']);

    $course_s->setVar('dept_id', $_POST['dept_id']);

    $course_s->setVar('term', $_POST['term']);

    $course_s->setVar('year', $_POST['year']);

    $course_s->setVar('units', $_POST['units']);

    $course_s->setVar('cteaser', $_POST['cteaser']);

    $course_s->setVar('creview', $_POST['creview']);

    if ($course_s->store()) {
        redirect_header('edit_dept.php?op=modify&dept_id=' . ($course_s->getVar('dept_id')), 2, _CR_ACTIONSDONE);
    }
}
if ('cdelete' == $op) {
    Course::delete($_POST['cids']);

    redirect_header('edit_dept.php?op=modify&dept_id=' . $dept_id, 2, _CR_ACTIONSDONE);
}

// show the main menu

if ('' == $op) {
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    require_once XOOPS_ROOT_PATH . '/class/xoopsform/tableform.php';

    $form = new XoopsThemeForm('<b>' . _MD_A_MODIRY_DEPARTMENT . '</b>', 'modify_form', 'edit_dept.php', 'get');

    $depart_sel = new XoopsFormSelect(_MD_A_DEPARMENT, 'dept_id', null, 0);

    $deps = $depart_s->getAll();

    foreach ($deps as $k => $v) {
        $depart_sel->addOption($v['dept_id'], $v['dept_name']);
    }

    $form->addElement($depart_sel);

    $hidden = new XoopsFormHidden('op', 'modify');

    $form->addElement($hidden);

    $button = new XoopsFormButton('', '', _MD_A_MODIFY, $type = 'submit');

    $form->addElement($button);

    echo $form->render();

    echo '<br>';

    $form = new XoopsThemeForm('<b>' . _MD_A_ADD_NEW_DEP . '</b>', 'create_form', 'edit_dept.php');

    $department_txt = new XoopsFormText(_MD_A_DEPARMENT, 'name', 25, 20);

    $form->addElement($department_txt);

    $hidden = new XoopsFormHidden('op', 'add');

    $form->addElement($hidden);

    $button = new XoopsFormButton('', '', _MD_A_ADD, $type = 'submit');

    $form->addElement($button);

    echo $form->render();
}

  xoops_cp_footer();

  exit();

function courses_index()
{
    global $dept_id;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    require_once '../class/course.inc.php';

    $courses = Course::getByDepart($dept_id);

    /*	    $content .= "<form action=edit_dept.php method=GET name=dept_form>";
            $content .= "<select name=dept_id onchange='javascript:dept_form.submit()'>";
            $deps = Department::getAll();

            foreach ($deps as $k=>$v) {
             $content .= "<option value=${v['dept_id']} ".($v['dept_id']==$_GET['dept_id']?'selected':'').">".$v['dept_name']."\n";
            }
            $content .= "<select name=dept_id>";
            $content .= '</form>';
    */

    $content .= "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";

    if (isset($courses['0'])) {
        $content .= "<form action='edit_dept.php?op=cdelete&dept_id=$dept_id' method=POST>";

        $content .= '<tr class=head><td>' . _CR_COURSEID . '</td>
			<td>' . _CR_COURSENUM . '</td>
			<td>' . _CR_COURSETITLE . '</td>			
			<td>' . _DELETE . '</td></tr>';

        $i = 0;

        foreach ($courses as $course) {
            $class = ($i % 2) ? 'odd' : 'even';

            $content .= "<tr class=$class><td>" . $course['course_id'] . '</td>
				<td>' . $course['num'] . "</td>
				<td><a href=\"edit_dept.php?op=ceditform&cid=${course['course_id']}\">" . mb_substr($course['name'], 0, 50) . "</a></td>
				<td><input type=checkbox name=cids[] value='" . $course['course_id'] . "'></td>
				</tr>";
        }

        $content .= '<tr><td></td><td colspan=2><input type=submit></td></tr>';

        $content .= '</form>';
    } else {
        $content .= _CR_THEREISNORECORDS;
    }

    $content .= '</td></tr></table>';

    ob_start();

    include '../include/course_form.inc.php';

    $content .= ob_get_contents();

    ob_end_clean();

    return $content;
}
