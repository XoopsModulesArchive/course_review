<?php

require dirname(__DIR__, 2) . '/mainfile.php';
   require __DIR__ . '/include/functions.inc.php';
   require __DIR__ . '/config.inc.php';
  require XOOPS_ROOT_PATH . '/header.php';
# print_r($_SESSION);

  require_once 'class/department.inc.php';
  require_once 'class/review.inc.php';
 require_once 'class/course.inc.php';
 require_once 'class/professor.inc.php';

 if (!is_object($xoopsUser)) {
     redirect_header(XOOPS_URL . '/user.php', 5, _NOPERM);

     exit();
 }

if (!$op) {
    $op = 'add';
}

 switch ($op) {
 case 'preview':
   do_preview();
  break;
 case 'cancel':
  unset($_SESSION[cr]);
  redirect_header('index.php', 2, _CR_ACTIONCANCELED);
   break;
 default:
   do_add();
  break;
 }

 require_once XOOPS_ROOT_PATH . '/footer.php';

function do_preview()
{
    global $xoopsOption,$xoopsTpl,$xoopsModuleConfig,$mime_types , $myts, $xoopsDB,$SESSION_VAR,$xoopsUser, $xoopsModule;

    $GLOBALS['xoopsOption']['template_main'] = 'showreview.html';

    $myts = MyTextSanitizer::getInstance();

    $prof_s = new Professor();

    $review_s = new Review();

    $course_s = new Course();

    $course_s->loadFromSession();

    if (!$_GET['back']) {
        $course_s->setVar('name', $_POST['c_title']);

        $course_s->storeToSession();

        $prof_s->setVar('lname', $_POST['plname']);

        $prof_s->setVar('fname', $_POST['pfname']);

        $prof_s->storeToSession();

        $review_s->setVar('icon', $_POST['icon']);

        $review_s->setVar('course_review', $_POST['course_text']);

        $review_s->setVar('prof_review', $_POST['prof_text']);

        $review_s->setVar('difficulty', $_POST['diff']);

        $review_s->setVar('usefulness', $_POST['useful']);

        $review_s->setVar('effort', $_POST['effort']);

        $review_s->setVar('prof_effect', $_POST['effect']);

        $review_s->setVar('prof_fair', $_POST['fair']);

        $review_s->setVar('prof_avail', $_POST['avail']);

        $review_s->setVar('overall', $_POST['overall']);

        $review_s->setVar('rev_uid', $xoopsUser->uid());

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        $allowed_mimetypes = $xoopsModuleConfig['mimetypes'];

        $maxfilesize = $xoopsModuleConfig['maxfilesize'];

        $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        if ($_FILES[syllabus][name]) {
            $uploader->setPrefix('syllabus');

            if ($uploader->fetchMedia('syllabus')) {
                if (!$uploader->upload()) {
                    #  echo $uploader->getErrors();
                } else {
                    $review_s->setVar('syllabus_mime', $uploader->getMediaType());

                    $review_s->setVar('syllabus_url', $uploader->getSavedFileName());
                }
            }

            if (count($uploader->errors) > 0) {
                redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php', 3, $uploader->getErrors());

                exit();
            }
        }

        if ($_FILES['picture']['name']) {
            $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];

            $maxfilesize = $xoopsModuleConfig['img_maxfilesize'];

            $uploader->__construct(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig[uploaddir], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

            $uploader->setPrefix('profimg');

            if ($uploader->fetchMedia('picture')) {
                if (!$uploader->upload()) {
                    #  echo $uploader->getErrors();
                } else {
                    $review_s->setVar('image_url', $uploader->getSavedFileName());
                }
            }

            #  echo $uploader->getErrors();

            if (count($uploader->errors) > 0) {
                redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php', 3, $uploader->getErrors());

                exit();
            }
        }

        $review_s->storeToSession();
    } # end if back

    // Duc --- fix this

    # if(($_GET['op'] == "preview") && !$error) {

    #   preview();

    #   exit();

    # }

    if (!$course_s->cleanVars()) {
        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php', 3, 'Course' . $course_s->getHtmlErrors());
    }

    if (!$review_s->cleanVars()) {
        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php', 3, 'Review' . $review_s->getHtmlErrors());
    }

    if (!$prof_s->cleanVars()) {
        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php', 3, 'Professor' . $prof_s->getHtmlErrors());
    }

    if ($error) {
        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/handleadd.php?back=1', 5, "We're sorry.  It looks like you've missed some required information.  The required information is highlighted below.<br>" . implode('<br>', $error));

        exit();
    }

    $prof_s->loadFromSession();

    $course_s->loadFromSession();

    $review_s->loadFromSession();

    $xoopsTpl->assign('cname', $course_s->getVar('name'));

    $prof_review = $review_s->getVar('prof_review', 'n');

    $course_review = $review_s->getVar('course_review', 'n');

    $prof_review = $myts->displayTarea($prof_review, 1, 1, 1, 1, 1);

    $course_review = $myts->displayTarea($course_review, 1, 1, 1, 1, 1);

    $xoopsTpl->assign('course_review', $course_review);

    $xoopsTpl->assign('prof_review', $prof_review);

    $xoopsTpl->assign('syllabus_url', XOOPS_URL . '/' . $xoopsModuleConfig['uploaddir'] . '/' . $review_s->getVar('syllabus_url'));

    $smime = $review_s->getVar('syllabus_mime');

    $xoopsTpl->assign('syllabus_title', sprintf("<img src='images/mime_types/%s' alt='%s'>Show", $mime_types[(string)$smime][1], $mime_types[(string)$smime][2]));

    $xoopsTpl->assign('image_url', XOOPS_URL . '/' . $xoopsModuleConfig[uploaddir] . '/' . $review_s->getVar('image_url'));

    $xoopsTpl->assign('difficulty', rank_image($review_s->getVar('difficulty')) . ' ' . $review_s->getVar('difficulty'));

    $xoopsTpl->assign('usefulness', rank_image($review_s->getVar('usefulness')) . ' ' . $review_s->getVar('usefulness'));

    $xoopsTpl->assign('effort', rank_image($review_s->getVar('effort')) . ' ' . $review_s->getVar('effort'));

    $xoopsTpl->assign('prof_effect', rank_image($review_s->getVar('prof_effect')) . ' ' . $review_s->getVar('prof_effect'));

    $xoopsTpl->assign('prof_fair', rank_image($review_s->getVar('prof_fair')) . ' ' . $review_s->getVar('prof_fair'));

    $xoopsTpl->assign('prof_avail', rank_image($review_s->getVar('prof_avail')) . ' ' . $review_s->getVar('difficulty'));

    $xoopsTpl->assign('overall', rank_image($review_s->getVar('overall')) . ' ' . $review_s->getVar('overall'));

    $xoopsTpl->assign('plname', $prof_s->getVar('lname'));

    $xoopsTpl->assign('pfname', $prof_s->getVar('fname'));

    if (!($prof_id = Professor::getByFullName($prof_s->getVar('fname'), $prof_s->getVar('lname')))) {
        if ($prof_choice = Professor::getByFullNameSoundex($prof_s->getVar('fname'), $prof_s->getVar('lname'))) {
            $prof_choice[] = ['fname' => $prof_s->getVar('fname'), 'lname' => $prof_s->getVar('lname')];
        }
    }

    $xoopsTpl->assign('prof_choice', $prof_choice);

    $xoopsTpl->assign('uname', $xoopsUser->uname('S'));

    $xoopsTpl->assign('ufrom', $xoopsUser->user_from('S'));

    $cr['accept']['caption'] = 'accept';

    $cr['accept']['url'] = 'thankyou.php';

    $cr['edit']['caption'] = 'back';

    $cr['edit']['url'] = 'handleadd.php?back=1';

    $cr['cancel']['caption'] = 'cancel';

    $cr['cancel']['url'] = 'thankyou.php?op=cancel';

    $xoopsTpl->assign('cr', $cr);

    $xoopsTpl->assign('preview', true);

    $xoopsTpl->assign('is_picture', $review_s->getVar('image_url'));

    $xoopsTpl->assign('is_syllabus', $review_s->getVar('syllabus_url'));
}

function do_add()
{
    global $xoopsDB,$xoopsUser,$SESSION_VAR,$xoopsModule;

    $approved = (int)test_perms();

    $depart_s = new Department();

    $depart_s->loadFromSession();

    $prof_s = new Professor();

    $prof_s->loadFromSession();

    if ($_POST['fullname']) {
        [$fname, $lname] = preg_split(' ', $_POST['fullname']);

        $prof_s->setVar('fname', $fname);

        $prof_s->setVar('lname', $lname);
    } else {
        redirect_header('thankyou.php?op=preview&back=1', 2, 'Select Professor');

        exit();
    }

    $prof_s->storeToSession();

    $prof_s->setVar('dept_id', $depart_s->getVar('dept_id'));

    if ($prof_row = $prof_s->getByFullName($prof_s->getVar('fname'), $prof_s->getVar('lname'))) {
        $prof_id = $prof_row['prof_id'];
    } else {
        if (!($prof_id = $prof_s->store())) {
            redirect_header('search.php?filled=1', 2, $prof_s->getHtmlErrors);
        }
    }

    $course_s = new Course();

    $course_s->loadFromSession();

    $course_s->setVar('dept_id', $depart_s->getVar('dept_id'));

    if ($course_row = $course_s->getByNum($course_s->getVar('num'))) {
        $course_id = $course_row['course_id'];
    } else {
        $course_id = $course_s->store();
    }

    $review_s = new Review();

    $review_s->loadFromSession();

    Professor::storePhoto($prof_id, $review_s->getVar('image_url'));

    $review_s->setVar('dept_id', $depart_s->getVar('dept_id'));

    $review_s->setVar('course_id', $course_id);

    $review_s->setVar('prof_id', $prof_id);

    $review_s->setVar('approve', $approved);

    $review_s->setVar('term', $course_s->getVar('term'));

    $review_s->setVar('year', $course_s->getVar('year'));

    $review_s->store();

    unset($_SESSION['cr_store']);

    redirect_header('search.php?filled=1', 2, _CR_THANKFORREVIEW);
}

function do_prof_add()
{
}
