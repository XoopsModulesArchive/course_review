<?php

require dirname(__DIR__, 2) . '/mainfile.php';

switch ($op) {
case 'start':

  require XOOPS_ROOT_PATH . '/header.php';

  require __DIR__ . '/include/functions.inc.php';

    start();

  require XOOPS_ROOT_PATH . '/footer.php';

    break;
case 'handleadd':

  require XOOPS_ROOT_PATH . '/header.php';

  require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

    handleadd();

  require XOOPS_ROOT_PATH . '/footer.php';

    break;
case 'preview':

    do_preview();

    break;
case 'do_add':

    do_add($_SESSION[profs_field], $_SESSION[courses_field], $_SESSION[reviews_field]);

    break;
default:

  require XOOPS_ROOT_PATH . '/header.php';

  require __DIR__ . '/include/functions.inc.php';

    start();

  require XOOPS_ROOT_PATH . '/footer.php';

    break;
}

  require_once XOOPS_ROOT_PATH . '/class/uploader.php';

  $allowed_mimetypes = $xoopsModuleConfig[mimetypes];

  $maxfilesize = $xoopsModuleConfig[maxfilesize];

  $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

  $uploader->setPrefix('syllabus');

  if ($uploader->fetchMedia('syllabus')) {
      if (!$uploader->upload()) {
          #  echo $uploader->getErrors();
      } else {
          $mime_type = $uploader->getMediaType();

          $syllabus_url = $uploader->getSavedFileName();
      }
  }

  #  echo $uploader->getErrors();

  if (count($uploader->errors) > 0) {
      redirect_header(XOOPS_URL . '/modules/courseReview/handleadd.php', 3, $uploader->getErrors());

      exit();
  }

    $allowed_mimetypes = $xoopsModuleConfig[img_mimetypes];

    $maxfilesize = $xoopsModuleConfig[img_maxfilesize];

    $uploader->__construct(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

    $uploader->setPrefix('img');

  if ($uploader->fetchMedia('picture')) {
      if (!$uploader->upload()) {
          #  echo $uploader->getErrors();
      } else {
          $mime_type_img = $uploader->getMediaType();

          $image_url = $uploader->getSavedFileName();
      }
  }

  #  echo $uploader->getErrors();

  if (count($uploader->errors) > 0) {
      redirect_header(XOOPS_URL . '/modules/courseReview/handleadd.php', 3, $uploader->getErrors());

      exit();
  }

#$syllabus_text=file($HTTP_POST_FILES[syllabus][tmp_name]);

#$syllabus_text= join ("\n" ,$syllabus_text);

#	require_once XOOPS_ROOT_PATH."/class/uploader.php";

#	$uploaddir=XOOPS_ROOT_PATH."/modules/courseReview/images_ann/$userdir/";

#	if (!is_dir($uploaddir)) {mkdir($uploaddir);};

#	$uploader = new XoopsMediaUploader($uploaddir, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'), $photomax);

 ?>





<h3><center>THANK YOU FOR YOUR REVIEW.  YOU ROCK!</center></h3><br>





<?php

  require_once XOOPS_ROOT_PATH . '/footer.php';

function start()
{
    global $xoopsDB,$xoopsTpl,$xoopsOption;

    $GLOBALS['xoopsOption']['template_main'] = 'start_form.html';

    $table = $xoopsDB->prefix('cr_depts');

    $q = 'SELECT dept_name FROM ' . $table . ' ORDER BY dept_name ASC';

    $result = $xoopsDB->query($q);

    $d_list .= "<select name=\"deptName\">\n";

    while ($row = $xoopsDB->fetchArray($result)) {
        $d_list .= '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . "</option>\n";
    }

    $d_list .= "</select>\n";

    $cr[dep_list] = $d_list;

    $cr[action] = 'index.php?op=handleadd';

    $xoopsTpl->assign('cr', $cr);
}

function handleadd()
{
    global $xoopsOption,$xoopsTpl,$xoopsUser;

    $cr[dropdown] = '<option value="10">5 Stars


  <option value="9">4 1/2 Stars


  <option value="8">4 Stars


  <option value="7">3 1/2 Stars


  <option value="6">3 Stars


  <option value="5">2 1/2 Stars


  <option value="4">2 Stars


  <option value="3">1 1/2 Stars


  <option value="2">1 Star


  <option value="1">1/2 Star


  <option value="0">What stars?';

    $GLOBALS['xoopsOption']['template_main'] = 'handleadd.html';

    $lists = new XoopsLists();

    $filelist = $lists::getSubjectsList();

    $count = 1;

    while (list($key, $file) = each($filelist)) {
        $checked = '';

        if (isset($icon) && $file == $icon) {
            $checked = ' checked';
        }

        $cr['list'] .= "\t<input type='radio' value='$file' name='icon'$checked>&nbsp;\n";

        $cr['list'] .= "\t<img src='" . XOOPS_URL . "/images/subject/$file' alt=''>&nbsp;\n";

        if (8 == $count) {
            $cr['list'] .= '<br>';

            $count = 0;
        }

        $count++;
    }

    $xoopsTpl->assign('cr', $cr);
}

function do_preview()
{
}

function do_add($profs_field, $courses_field, $reviews_field)
{
    global $xoopsDB;

    $approved = (int)test_perms();

    $q_prof = 'SELECT prof_id FROM ' . $xoopsDB->prefix('cr_profs') . ' WHERE lname = "' . $profs_field['plname'] . '" AND fname = "' . $profs_field['pfname'] . '"';

    $prof_result = $xoopsDB->query($q_prof);

    $numprofs = $GLOBALS['xoopsDB']->getRowsNum($prof_result);

    if ($numprofs > 0) {
        $prof_row = $xoopsDB->fetchArray($prof_result);

        $prof_id = $prof_row['prof_id'];
    } else {
        $q_insert = 'INSERT INTO ' . $xoopsDB->prefix('cr_profs') . ' (dept_id, lname, fname) VALUES (' . $profs_field['dept_id'] . ', "' . $profs_field['plname'] . '", "' . $profs_field['pfname'] . '")';

        $xoopsDB->query($q_insert);

        $prof_id = $GLOBALS['xoopsDB']->getInsertId();
    }

    $q_course = 'SELECT course_id FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE num = "' . $courses_field['course_num'] . '"';

    $course_result = $xoopsDB->query($q_course);

    if ($GLOBALS['xoopsDB']->getRowsNum($course_result) > 0) {
        $course_row = $xoopsDB->fetchArray($course_result);

        $course_id = $course_row['course_id'];
    } else {
        $q_insert2 = 'INSERT INTO ' . $xoopsDB->prefix('cr_profs') . ' (dept_id, num, name, term, year, units) VALUES (' . $courses_field['dept_id'] . ', "' . $courses_field['course_num'] . '", "' . $courses_field['c_title'] . '", "' . $courses_field['term'] . '", ' . $courses_field['year'] . ', ' . $courses_field['units'] . ')';

        $xoopsDB->query($q_insert2);

        $course_id = $GLOBALS['xoopsDB']->getInsertId();
    }

    $q = 'INSERT INTO ' . $xoopsDB->prefix('cr_reviews') . ' (dept_id, course_id, prof_id, title, icon, review, syllabus_url, image_url, difficulty, usefulness, effort, prof_effect, prof_fair, prof_avail, overall, rev_uid, comments, feature, approve , syllabus_mime) VALUES (';

    $q .= $reviews_field['dept_id'] . ', ';

    $q .= $course_id . ', ';

    $q .= $prof_id . ', ';

    $q .= '"' . $reviews_field['title'] . '", ';

    $q .= '"' . $reviews_field['icon'] . '", ';

    $q .= '"' . $reviews_field['text'] . '", ';

    $q .= $reviews_field[syllabus_url] . ', ';

    $q .= '"' . $reviews_field[image_url] . '", ';

    $q .= $reviews_field['diff'] . ', ';

    $q .= $reviews_field['useful'] . ', ';

    $q .= $reviews_field['effort'] . ', ';

    $q .= $reviews_field['effect'] . ', ';

    $q .= $reviews_field['fair'] . ', ';

    $q .= $reviews_field['avail'] . ', ';

    $q .= $reviews_field['overall'] . ', ';

    $q .= '"' . $xoopsUser->uid() . '", ';

    $q .= '0, '; // no comments by default

    $q .= '0, '; // not featured by default

    $q .= "$approved, "; // approved if have right permissions

    $q .= "'$reviews_field[mime_type]')";

    $xoopsDB->query($q);
}

?>


