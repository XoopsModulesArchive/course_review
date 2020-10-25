<?php

require dirname(__DIR__, 2) . '/mainfile.php';
   require __DIR__ . '/include/functions.inc.php';
   require __DIR__ . '/config.inc.php';
  require XOOPS_ROOT_PATH . '/header.php';

 switch ($op) {
 case'preview':
   do_preview();
  break;
 case'cancel':
  unset($_SESSION[cr]);
  redirect_header('index.php', 2, 'Your action has been canceled');
   break;
 default:
   do_add();
  break;
 }

 require_once XOOPS_ROOT_PATH . '/footer.php';

function do_preview()
{
    global $xoopsOption,$xoopsTpl,$xoopsModuleConfig,$mime_types , $myts, $xoopsDB;

    $GLOBALS['xoopsOption']['template_main'] = 'showreview.html';

    //if (!$_POST['c_title']) {$error[].="<b style='color:red'>Catchy Title</b> is required";}

    if (!$_POST['plname'] || !$_POST['pfname']) {
        $error[] .= "<b style='color:red'>Professor Name</b> is required";
    } else {
        $quer = sprintf('select lname , fname from %s', $xoopsDB->prefix(cr_profs));

        $res = $xoopsDB->query($quer);

        $sndxf = soundex($_POST['pfname']);

        $sndxl = soundex($_POST['plname']);

        $_SESSION[cr][profs][plname] = $_POST['plname'];

        $_SESSION[cr][profs][pfname] = $_POST['pfname'];

        echo "POST[fname]:$_POST[pfname] ($sndxf) POST[lname]:$_POST[plname] ($sndxl)<br>";

        # soundex compare
/*
   while (false !== ($professors=$xoopsDB->fetchArray($res))) {
        echo "Fname=$professors[fname](".soundex($professors[fname]).") Lname=$professors[lname](".soundex($professors[lname]).") <br>";
     if ($sndxf==soundex($professors[fname]) && $sndxl==soundex($professors[lname])){
      $_SESSION[cr][profs][plname]=$professors[lname];
      $_SESSION[cr][profs][pfname]=$professors[fname];
     }
    }
*/
    }

    if (!$_POST['title']) {
        $error[] .= "<b style='color:red'>Course Review Title</b> is required";
    }

    if (!$_POST['text']) {
        $error[] .= "<b style='color:red'>Course Review Text</b> is required";
    }

    //if ($_POST['diff']==0) {$error[].="<b style='color:red'>Difficulty</b> is required";};

    //if ($_POST['useful']==0) {$error[].="<b style='color:red'>Usefulness</b> is required";};

    //if ($_POST['effort']==0) {$error[].="<b style='color:red'>Effort</b> is required";};

    if (0 == $_POST['effect']) {
        $error[] .= "<b style='color:red'>Effectiveness</b> is required";
    }

    if (0 == $_POST['fair']) {
        $error[] .= "<b style='color:red'>Fairness</b> is required";
    }

    if (0 == $_POST['avail']) {
        $error[] .= "<b style='color:red'>Availability</b> is required";
    }

    if (0 == $_POST['overall']) {
        $error[] .= "<b style='color:red'>Overall</b> is required";
    }

    $_SESSION[cr][courses]['c_title'] = $_POST['c_title'];

    $_SESSION[cr][reviews]['title'] = $_POST['title'];

    $_SESSION[cr][reviews]['icon'] = $_POST['icon'];

    $_SESSION[cr][reviews]['text'] = $_POST['text'];

    $_SESSION[cr][reviews]['diff'] = $_POST['diff'];

    $_SESSION[cr][reviews]['useful'] = $_POST['useful'];

    $_SESSION[cr][reviews]['effort'] = $_POST['effort'];

    $_SESSION[cr][reviews]['effect'] = $_POST['effect'];

    $_SESSION[cr][reviews]['fair'] = $_POST['fair'];

    $_SESSION[cr][reviews]['avail'] = $_POST['avail'];

    $_SESSION[cr][reviews]['overall'] = $_POST['overall'];

    require_once XOOPS_ROOT_PATH . '/class/uploader.php';

    $allowed_mimetypes = $xoopsModuleConfig[mimetypes];

    $maxfilesize = $xoopsModuleConfig[maxfilesize];

    $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

    if ($_FILES[syllabus][name]) {
        $uploader->setPrefix('syllabus');

        if ($uploader->fetchMedia('syllabus')) {
            if (!$uploader->upload()) {
                #  echo $uploader->getErrors();
            } else {
                $_SESSION[cr][reviews][syllabus_mime] = $uploader->getMediaType();

                $_SESSION[cr][reviews][syllabus] = $uploader->getSavedFileName();
            }
        }

        if (count($uploader->errors) > 0) {
            redirect_header(XOOPS_URL . '/modules/courseReview/handleaddprof.php', 3, $uploader->getErrors());

            exit();
        }
    }

    if ($_FILES[picture][name]) {
        $allowed_mimetypes = $xoopsModuleConfig[img_mimetypes];

        $maxfilesize = $xoopsModuleConfig[img_maxfilesize];

        $uploader->__construct(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        $uploader->setPrefix('img');

        if ($uploader->fetchMedia('picture')) {
            if (!$uploader->upload()) {
                #  echo $uploader->getErrors();
            } else {
                $_SESSION[cr][reviews][picture] = $uploader->getSavedFileName();
            }
        }

        #  echo $uploader->getErrors();

        if (count($uploader->errors) > 0) {
            redirect_header(XOOPS_URL . '/modules/courseReview/handleaddprof.php', 3, $uploader->getErrors());

            exit();
        }
    }

    if ($error) {
        redirect_header(XOOPS_URL . '/modules/courseReview/handleaddprof.php?back=1', 5, "We're sorry.  It looks like you've missed some required information.  The required information is highlighted below.<br>" . implode('<br>', $error));

        exit();
    }

    #$syllabus_text=file($HTTP_POST_FILES[syllabus][tmp_name]);

    #$syllabus_text= join ("\n" ,$syllabus_text);

    #	require_once XOOPS_ROOT_PATH."/class/uploader.php";

    #	$uploaddir=XOOPS_ROOT_PATH."/modules/courseReview/images_ann/$userdir/";

    #	if (!is_dir($uploaddir)) {mkdir($uploaddir);};

    #	$uploader = new XoopsMediaUploader($uploaddir, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'), $photomax);

    $myts = MyTextSanitizer::getInstance();

    $xoopsTpl->assign('title', $_SESSION[cr][reviews][title]);

    $xoopsTpl->assign('review', $myts->nl2Br($_SESSION[cr][reviews][text]));

    $xoopsTpl->assign('syllabus_url', XOOPS_URL . '/' . $xoopsModuleConfig[uploaddir] . '/' . $_SESSION[cr][reviews][syllabus]);

    $smime = $_SESSION[cr][reviews][syllabus_mime];

    $xoopsTpl->assign('syllabus_title', sprintf("<img src='images/mime_types/%s' alt='%s'>Show", $mime_types[(string)$smime][1], $mime_types[(string)$smime][2]));

    $xoopsTpl->assign('image_url', XOOPS_URL . '/' . $xoopsModuleConfig[uploaddir] . '/' . $_SESSION[cr][reviews][picture]);

    $xoopsTpl->assign('difficulty', $_SESSION[cr][reviews][diff]);

    $xoopsTpl->assign('usefulness', $_SESSION[cr][reviews][useful]);

    $xoopsTpl->assign('effort', $_SESSION[cr][reviews][effort]);

    $xoopsTpl->assign('prof_effect', $_SESSION[cr][reviews][effect]);

    $xoopsTpl->assign('prof_fair', $_SESSION[cr][reviews][fair]);

    $xoopsTpl->assign('prof_avail', $_SESSION[cr][reviews][avail]);

    $xoopsTpl->assign('overall', $_SESSION[cr][reviews][overall]);

    $xoopsTpl->assign('cname', $_SESSION[cr][courses]['c_title']);

    $xoopsTpl->assign('plname', $_SESSION[cr][profs][pfname]);

    $xoopsTpl->assign('pfname', $_SESSION[cr][profs][plname]);

    $xoopsTpl->assign('uname', $_SESSION[cr]['uname']);

    $xoopsTpl->assign('ufrom', $_SESSION[cr]['user_from']);

    $cr[accept][caption] = 'accept';

    $cr[accept][url] = 'thankyou2.php';

    $cr[edit][caption] = 'back';

    $cr[edit][url] = 'handleaddprof.php?back=1';

    $cr[cancel][caption] = 'cancel';

    $cr[cancel][url] = 'thankyou2.php?op=cancel';

    $xoopsTpl->assign('cr', $cr);

    $xoopsTpl->assign('preview', 'true');

    $xoopsTpl->assign('is_picture', isset($_SESSION[cr][reviews][picture]));

    $xoopsTpl->assign('is_syllabus', isset($_SESSION[cr][reviews][syllabus]));
}

function do_add()
{
    global $xoopsDB,$xoopsUser;

    $approved = (int)test_perms();

    $q_prof = 'SELECT prof_id FROM ' . $xoopsDB->prefix('cr_profs') . ' lname = "' . $_SESSION[cr][profs]['plname'] . '" AND fname = "' . $_SESSION[cr][profs]['pfname'] . '"';

    echo "$q_prof <br";

    $prof_result = $xoopsDB->query($q_prof);

    $numprofs = $GLOBALS['xoopsDB']->getRowsNum($prof_result);

    if ($numprofs > 0) {
        $prof_row = $xoopsDB->fetchArray($prof_result);

        $prof_id = $prof_row['prof_id'];
    } else {
        $q_insert = 'INSERT INTO ' . $xoopsDB->prefix('cr_profs') . ' (dept_id, lname, fname) VALUES (' . $_SESSION[cr]['dep_id'] . ', "' . $_SESSION[cr][profs]['plname'] . '", "' . $_SESSION[cr][profs]['pfname'] . '")';

        $GLOBALS['xoopsDB']->queryF($q_insert);

        $prof_id = $GLOBALS['xoopsDB']->getInsertId();
    }

    $q_course = 'SELECT course_id FROM ' . $xoopsDB->prefix('cr_courses') . ' WHERE num = "' . $_SESSION[cr][courses]['courseNum'] . '"';

    $course_result = $xoopsDB->query($q_course);

    if ($GLOBALS['xoopsDB']->getRowsNum($course_result) > 0) {
        $course_row = $xoopsDB->fetchArray($course_result);

        $course_id = $course_row['course_id'];
    } else {
        $q_insert2 = 'INSERT INTO ' . $xoopsDB->prefix('cr_courses') . ' (dept_id, num, name, term, year, units) VALUES (' . $_SESSION[cr]['dep_id'] . ', "' . $_SESSION[cr][courses]['courseNum'] . '", "' . $_SESSION[cr][courses]['c_title'] . '", "' . $_SESSION[cr][courses]['term'] . '", ' . $_SESSION[cr][courses]['year'] . ', ' . $_SESSION[cr][courses]['units'] . ')';

        $GLOBALS['xoopsDB']->queryF($q_insert2);

        $course_id = $GLOBALS['xoopsDB']->getInsertId();
    }

    $myts = MyTextSanitizer::getInstance();

    $q = 'INSERT INTO ' . $xoopsDB->prefix('cr_reviews') . ' (dept_id, course_id, prof_id, title, icon, review, syllabus_url, image_url, difficulty, usefulness, effort, prof_effect, prof_fair, prof_avail, overall, rev_uid, comments, feature, approve , syllabus_mime) VALUES (';

    $q .= $_SESSION[cr]['dep_id'] . ', ';

    $q .= $course_id . ', ';

    $q .= $prof_id . ', ';

    $q .= '"' . $_SESSION[cr][reviews][title] . '", ';

    $q .= '"' . $_SESSION[cr][reviews]['icon'] . '", ';

    $q .= '"' . $myts->addSlashes($_SESSION[cr][reviews]['text']) . '", ';

    $q .= '"' . $_SESSION[cr][reviews][syllabus] . '", ';

    $q .= '"' . $_SESSION[cr][reviews][picture] . '", ';

    $q .= '0, 0, 0, ';

    $q .= $_SESSION[cr][reviews]['effect'] . ', ';

    $q .= $_SESSION[cr][reviews]['fair'] . ', ';

    $q .= $_SESSION[cr][reviews]['avail'] . ', ';

    $q .= $_SESSION[cr][reviews]['overall'] . ', ';

    $q .= '"' . $xoopsUser->uid() . '", ';

    $q .= '0, '; // no comments by default
  $q   .= '0, '; // not featured by default
  $q   .= "$approved, "; // approved if have right permissions
  $q   .= "'" . $_SESSION[cr][reviews][syllabus_mime] . "')";

    //  echo "$q<br>";

    #  $xoopsDB->query($q);

    $GLOBALS['xoopsDB']->queryF($q);

    //  echo $q;

    echo $GLOBALS['xoopsDB']->error();

    unset($_SESSION[cr]);

    redirect_header('search.php?filled=1', 2, 'THANK YOU FOR YOUR REVIEW.  YOU ROCK!');
}
