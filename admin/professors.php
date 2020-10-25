<?php

require __DIR__ . '/admin_header.php';
require_once dirname(__DIR__) . '/include/functions.inc.php';
require_once dirname(__DIR__) . '/class/professor.inc.php';
require_once dirname(__DIR__) . '/class/department.inc.php';
require_once dirname(__DIR__) . '/class/review.inc.php';
adminmenu(5, _MD_A_PROFESSORS);

switch ($op) {
case 'accept_photo':
    $image = $_POST['im_url'];
    $prof_id = (int)$_GET['pid'];
    $quer = sprintf(
        "update %s set image_url='$image' where prof_id=$prof_id",
        $xoopsDB->prefix('cr_profs')
    );
    $xoopsDB->query($quer);
    echo $xoopsDB->error();
    redirect_header("professors.php?op=peditform&pid=${_GET['pid']}", 2, _CR_ACTIONISDONE);

        break;
case'padd':
  require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];
            $maxfilesize = $xoopsModuleConfig['img_maxfilesize'];
            $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            $uploader->setPrefix('img_admin');
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $err = $uploader->getErrors();

            echo $err;
        } else {
            $filen = XOOPS_URL . '/' . $xoopsModuleConfig['uploaddir'] . '/' . $prof_s->getVar('image_url');

            $prof_s->setVar('image_url', $uploader->getSavedFileName());
        }
    }

    $prof_s->setVar('lname', $_POST['lname']);
    $prof_s->setVar('fname', $_POST['fname']);
    $prof_s->setVar('dept_id', $_POST['dept_id']);
    $prof_s->setVar('pteaser', $_POST['pteaser']);
    $prof_s->setVar('preview', $_POST['preview']);
    if ($prof_s->store()) {
        redirect_header('professors.php', 2, _CR_ACTIONISDONE);
    }
    break;
case'peditform':
    $prof_s = new Professor($_GET['pid']);
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();

// users professor's photos
    $profarray = [];
    $profarray['photos'] = Review::getPhotosByProfessor($_GET['pid']);
    $profarray['prof_id'] = $_GET['pid'];
    $profarray['langphoto'] = _CR_PROF_PHOTO;
    $profarray['langassign'] = _CR_ASSIGN;
    $profarray['langsubmit'] = _SUBMIT;
    $profarray['languploaded_by'] = _CR_UPLOADEDBY;
    $xoopsTpl->assign('module_dirname', $xoopsModule->getVar('dirname'));
    $xoopsTpl->assign('profarray', $profarray);
    $xoopsTpl->assign('xoops_url', XOOPS_URL);
    echo cr_fieldset(_CR_USERSPHOTOS, $xoopsTpl->fetch('db:professors_photos.html'));
// admin professor's photos
    $profarray = [];
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

    $dirname = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'];

$i = 0;
if (is_dir($dirname) && $handle = opendir($dirname)) {
    while (false !== ($file = readdir($handle))) {
        if (0 === strpos($file, "img_admin") && is_file($dirname . '/' . $file)) {
            $i++;

            $profarray['photos'][$i]['image_url'] = $file;
        }
    }

    closedir($handle);
}

#	print_r($profarray['photos']);

    $profarray['prof_id'] = $_GET['pid'];
    $profarray['langphoto'] = _CR_PROF_PHOTO;
    $profarray['langassign'] = _CR_ASSIGN;
    $profarray['langsubmit'] = _SUBMIT;
    #$profarray['languploaded_by'] = _CR_UPLOADEDBY;
    $xoopsTpl->assign('module_dirname', $xoopsModule->getVar('dirname'));
    $xoopsTpl->assign('profarray', $profarray);
    $xoopsTpl->assign('xoops_url', XOOPS_URL);
    echo cr_fieldset(_CR_ADMINSPHOTOS, $xoopsTpl->fetch('db:professors_photos.html'));

    include '../include/professor_form.inc.php';
    break;
case'pedit':
    $prof_s = new Professor($_GET['pid']);
  require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];
            $maxfilesize = $xoopsModuleConfig['img_maxfilesize'];
            $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            $uploader->setPrefix('img_admin');
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $err = $uploader->getErrors();

            echo $err;
        } else {
            $filen = XOOPS_URL . '/' . $xoopsModuleConfig['uploaddir'] . '/' . $prof_s->getVar('image_url');

            #echo $filen;

            #		@unlink($filen);

            $prof_s->setVar('image_url', $uploader->getSavedFileName());
        }
    }
    if ($_POST['delete_img']) {
        $filen = XOOPS_URL . '/' . $xoopsModuleConfig['uploaddir'] . '/' . $prof_s->getVar('image_url');

        #echo $filen;

        @unlink($filen);

        $prof_s->setVar('image_url', '');
    }

    $prof_s->setVar('lname', $_POST['lname']);
    $prof_s->setVar('fname', $_POST['fname']);
    $prof_s->setVar('pteaser', $_POST['pteaser']);
    $prof_s->setVar('preview', $_POST['preview']);
    if ($prof_s->store()) {
        redirect_header('professors.php', 2, _CR_ACTIONISDONE);
    }
    break;
default:

    $professors = Professor::getAll();
    $profarray['langname'] = _CR_PROFNAME;
#	$profarray['image_url'] = ;
    $profarray['professors'] = $professors;
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();

    $xoopsTpl->assign('xoops_url', XOOPS_URL);
    $xoopsTpl->assign('profarray', $profarray);
    echo cr_fieldset(_MD_A_PROFESSORS, $xoopsTpl->fetch('db:professors_list.html'));
    include '../include/professor_form.inc.php';
    break;
}

xoops_cp_footer();
