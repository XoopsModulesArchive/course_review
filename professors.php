<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';
  require_once 'include/functions.inc.php';
  require_once 'class/review.inc.php';
  require_once 'class/course.inc.php';
  require_once 'class/professor.inc.php';

   $prof_c = new Professor($_GET['pid']);
  $breadcrumbs['currprof']['caption'] = $prof_c->getVar('fname') . ' ' . $prof_c->getVar('lname');
echo cr_breadcrumbs('currprof') . '<br>';

// menu:start

   if (!$_GET['tab'] || 'overview' == $_GET['tab']) {
       echo '<b>';
   }
   echo " <a href='?pid=${_GET['pid']}&tab=overview'>" . _CR_OVERVIEW . '</a>';
   if (!$_GET['tab'] || 'overview' == $_GET['tab']) {
       echo '</b>';
   }
   if ('review' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?pid=${_GET['pid']}&tab=review'>" . _CR_REVIEW . '</a>';
   if ('review' == $_GET['tab']) {
       echo '</b>';
   }
   if ('opinions' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?pid=${_GET['pid']}&tab=opinions'>" . _CR_USEROPINIONS . '</a>';
   if ('opinions' == $_GET['tab']) {
       echo '</b>';
   }
   if ('photos' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?pid=${_GET['pid']}&tab=photos'>" . _MD_PHOTOS . '</a>';
   if ('photos' == $_GET['tab']) {
       echo '</b>';
   }

   echo '<HR>';
// menu:end

   echo '<H2>' . $prof_c->getVar('fname') . ' ' . $prof_c->getVar('lname') . '</H2>';

switch ($_GET['op']) {
    case'upload':
      require_once XOOPS_ROOT_PATH . '/class/uploader.php';
  $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];
  $maxfilesize = $xoopsModuleConfig['maxfilesize'];
            $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];
            $maxfilesize = $xoopsModuleConfig['img_maxfilesize'];
            $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            $uploader->setPrefix('profimg');
             if ($uploader->fetchMedia('picture')) {
                 if (!$uploader->upload()) {
                     #  echo $uploader->getErrors();
                 } else {
                     $profimg = $uploader->getSavedFileName();

                     Professor::storePhoto($_GET['pid'], $profimg);
                 }
             }
  if (count($uploader->errors) > 0) {
      redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/professors.php?pid=${_GET['pid']}&tab=photos", 3, $uploader->getErrors());

      exit();
  }  redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/professors.php?pid=${_GET['pid']}&tab=photos", 3, _MD_ACTIONISDONE);

    break;
    case'rate':
    $sql = sprintf("SELECT rating FROM %s WHERE id=${_GET['item']}", $xoopsDB->prefix('cr_photos'));
    $res = $xoopsDB->query($sql);
    while (false !== ($myrow = $xoopsDB->fetchArray($res))) {
        $rating = $myrow['rating'];
    }

    if ('up' == $_GET['value']) {
        $rating++;
    }
    if ('down' == $_GET['value']) {
        $rating--;
    }

    $sql = sprintf("UPDATE %s SET rating=$rating WHERE id=${_GET['item']}", $xoopsDB->prefix('cr_photos'));
    $xoopsDB->queryF($sql);
    if ($xoopsDB->error()) {
        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/professors.php?pid=${_GET['pid']}&tab=photos", 3, $xoopsDB->error());
    }
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/professors.php?pid=${_GET['pid']}&tab=photos", 3, _MD_ACTIONISDONE);
    break;
}

switch ($_GET['tab']) {
case'review':
    professors_show_admin_review();
    break;
case'opinions':
    professors_show_user_reviews();
    break;
case'photos':
    professors_show_photos($_GET['pid']);
    break;
case'overview':
default:
    professors_show_overview();
    break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';

function professors_show_overview()
{
    global $xoopsDB, $xoopsTpl, $xoopsOption, $xoopsModule,$xoopsModuleConfig;

    $quer = sprintf(
        "select pr.pteaser, pr.preview, pr.image_url as p_photo, cr.num, pr.prof_id, pr.fname , pr.lname , %s , %s , %s , %s , %s , %s , %s  from %s rv , %s pr , %s cr where pr.prof_id=\"${_GET['pid']}\" and rv.prof_id=pr.prof_id and rv.course_id=cr.course_id  group by rv.course_id ",
        'AVG(rv.overall) av_overall',
        'AVG(rv.difficulty) av_diff',
        'AVG(rv.usefulness) av_useful',
        'AVG(rv.effort) av_effort',
        'AVG(rv.prof_effect) av_effect',
        'AVG(rv.prof_fair) av_fair',
        'AVG(rv.prof_avail) av_avail',
        $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('cr_profs'),
        $xoopsDB->prefix('cr_courses')
    );

    $res = $xoopsDB->query($quer);

    $only_row = $xoopsDB->fetchArray($res);

    $lname = $only_row['lname'];

    $fname = $only_row['fname'];

    $crarray['rtitle'] = _CR_PROFNAME . ":$fname $lname";

    $crarray['scale_bar'] = "<img width=100 height=14  SRC='images/rating_scale.gif'>";

    $crarray['avg']['overall'] = rank_image($only_row['av_overall']) . ' ' . $only_row['av_overall'];

    $crarray['avg']['diff'] = rank_image($only_row['av_diff']) . ' ' . $only_row['av_diff'];

    $crarray['avg']['useful'] = rank_image($only_row['av_useful']) . ' ' . $only_row['av_useful'];

    $crarray['avg']['effort'] = rank_image($only_row['av_effort']) . ' ' . $only_row['av_effort'];

    $crarray['avg']['effect'] = rank_image($only_row['av_effect']) . ' ' . $only_row['av_effect'];

    $crarray['avg']['fair'] = rank_image($only_row['av_fair']) . ' ' . $only_row['av_fair'];

    $crarray['avg']['avail'] = rank_image($only_row['av_avail']) . ' ' . $only_row['av_avail'];

    $photo = Professor::getPhoto($_GET['pid']);

    if ($photo) {
        $img_url = XOOPS_URL . '/' . $xoopsModuleConfig['uploaddir'] . "/$photo";
    } else {
        $img_url = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/images/nophoto.gif';
    }

    $crarray['photo'] = sprintf("<a  href='professors.php?pid=${_GET['pid']}&tab=photos'><img src='$img_url' alt='blah'></a>");

    $ts = MyTextSanitizer::getInstance();

    $crarray['pteaser'] = $ts->displayTarea($only_row['pteaser'], 1, 1, 1, 1, 1);

    $crarray['morelink'] = "<a href='?pid=${_GET['pid']}&tab=review' >" . _MORE . '</a>';

    $sql = sprintf(
        "SELECT dp.dept_name as dept, cr.course_id, rv.year, rv.term, cr.num, AVG(rv.prof_effect) as effect, CONCAT(dp.dept_name,cr.num,rv.term,rv.year) as groupitem  FROM %s cr , %s rv, %s dp where rv.prof_id=${_GET['pid']} and rv.course_id=cr.course_id and dp.dept_id = cr.dept_id group by groupitem ",
        $xoopsDB->prefix('cr_courses'),
        $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('cr_depts')
    );

    $res = $xoopsDB->query($sql);

    $crarray['courses'] = [];

    while (false !== ($crrow = $xoopsDB->fetchArray($res))) {
        if (1 == mb_strlen($crrow['year'])) {
            $crrow['year'] = '200' . $crrow['year'];
        } else {
            if (2 == mb_strlen($crrow['year'])) {
                $crrow['year'] = '20' . $crrow['year'];
            } else {
                if (3 == mb_strlen($crrow['year'])) {
                    $crrow['year'] = '2' . $crrow['year'];
                }
            }
        }

        $crrow['effectimg'] = rank_image($crrow['effect']);

        $crarray['courses'][] = $crrow;
    }

    echo $xoopsDB->error();

    if (Review::getByUID(30, true) > 0) {
        $crarray['show_edit'] = true;
    }

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:professor_overview.html');
}
function professors_show_admin_review()
{
    global $xoopsDB, $xoopsTpl, $xoopsOption;

    $reviewpage = $_GET['page'] ?: 0;

    $prof_c = new Professor($_GET['pid']);

    $prof_review = $prof_c->getVar('preview');

    if ('' != trim($prof_review)) {
        $reviewtext = explode('[pagebreak]', $prof_review);

        $review_pages = count($reviewtext);

        if ($review_pages > 1) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

            $pagenav = new XoopsPageNav($review_pages, 1, $reviewpage, 'page', "pid=${_GET['pid']}&tab=review");

            $crarray['navbar'] = $pagenav->renderNav();

            if (0 == $storypage) {
                //	    	$story['bodytext'] = $story['lead'].'<br><br>'.htmlspecialchars($articletext[$storypage]);

                $crarray['preview'] = $reviewtext[$reviewpage];
            } else {
                //			$story['bodytext'] = htmlspecialchars($articletext[$storypage]);

                $crarray['preview'] = $reviewtext[$reviewpage];
            }
        } else {
            //		$story['bodytext'] = $story['lead'].'<br><br>'.htmlspecialchars($bodytext);

            $crarray['preview'] = $reviewtext[$reviewpage];
        }
    }

    //hack end

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:professor_admin_review.html');
}
function professors_show_user_reviews()
{
    global $xoopsDB, $xoopsTpl;

    $quer = sprintf(
        "SELECT rv.overall , rv.usefulness , rv.prof_review ,rv.rev_id , us.uname, dp.dept_name, cr.num
					FROM %s rv , %s us, %s cr, %s dp  
					WHERE rv.course_id=cr.course_id AND rv.dept_id=dp.dept_id AND rv.rev_uid=us.uid and rv.prof_id=${_GET['pid']}",
        $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('users'),
        $xoopsDB->prefix('cr_courses'),
        $xoopsDB->prefix('cr_depts')
    );

    $res = $xoopsDB->query($quer);

    echo $xoopsDB->error();

    $crarray['reviews'] = [];

    while (false !== ($row = $xoopsDB->fetchArray($res))) {
        #   print_r($row);

        $review['title'] = "${row['dept_name']} ${row['num']}";

        $review['uname'] = $row['uname'];

        $review['text'] = $row['prof_review'];

#    $title = substr($row[course_review], 0, 20);

        $review['overall'] = rank_image($row['overall']) . ' ' . $row['overall'];

        $review['usefulness'] = rank_image($row['usefulness']) . ' ' . $row['usefulness'];

        $review['id'] = $row['rev_id'];

        array_unshift($crarray['reviews'], $review);
    }

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:professor_user_reviews.html');
}

function professors_show_photos($pid)
{
    global $xoopsModuleConfig;

    require_once 'class/professor.inc.php';

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $profrows = Professor::getPhotos($pid);

    echo '<table>';

    foreach ($profrows as $profrow) {
        echo "<tr><td><img src='" . XOOPS_URL . "/${xoopsModuleConfig['uploaddir']}/${profrow['img']}'></td>";

        echo "<td><a href='professors.php?pid=${_GET['pid']}&op=rate&item=${profrow['id']}&value=up'><img src='images/thumbup.jpg'></a>  <a href='professors.php?pid=${_GET['pid']}&op=rate&item=${profrow['id']}&value=down'><img src='images/thumbdown.jpg'></a> (${profrow['rating']})</td></tr>";
    }

    echo '</table>';

    $form = new XoopsThemeForm(_CR_ATTCHPICTURE, 'pform', "professors.php?pid=$pid&op=upload");

    $form->setExtra("ENCTYPE='multipart/form-data'");

    $file_el = new XoopsFormFile('', 'picture', $xoopsModuleConfig['maxfilesize']);

    foreach ($xoopsModuleConfig['img_mimetypes'] as $mimeitem) {
        $description .= $mimeitem . '<br>';
    }

    $file_el->setDescription($description);

    $form->addElement($file_el);

    $form->addElement(new XoopsFormButton('', '', _SUBMIT, $type = 'submit'));

    echo $form->render();
}
