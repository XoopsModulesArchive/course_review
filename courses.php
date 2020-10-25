<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';
  require_once 'include/functions.inc.php';
  include 'class/review.inc.php';
  include 'class/course.inc.php';

if (!$_GET['cid']) {
    exit();
}
   $course_c = new Course($_GET['cid']);
$breadcrumbs['currcourse']['caption'] = $course_c->getVar('name');
echo cr_breadcrumbs('currcourse') . '<br>';

// menu:start
   if (!$_GET['tab'] || 'overview' == $_GET['tab']) {
       echo '<b>';
   }
   echo " <a href='?cid=${_GET['cid']}&tab=overview'>" . _CR_OVERVIEW . '</a>';
   if (!$_GET['tab'] || 'overview' == $_GET['tab']) {
       echo '</b>';
   }
   if ('review' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?cid=${_GET['cid']}&tab=review'>" . _CR_REVIEW . '</a>';
   if ('review' == $_GET['tab']) {
       echo '</b>';
   }
   if ('specs' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?cid=${_GET['cid']}&tab=specs'>" . _CR_SPECS . '</a>';
   if ('specs' == $_GET['tab']) {
       echo '</b>';
   }
   if ('opinions' == $_GET['tab']) {
       echo '<b>';
   }
   echo "| <a href='?cid=${_GET['cid']}&tab=opinions'>" . _CR_USEROPINIONS . '</a>';
   if ('opinions' == $_GET['tab']) {
       echo '</b>';
   }
   echo '<HR>';
   $course_c = new Course($_GET['cid']);
   echo '<H2>' . $course_c->getVar('name') . '</H2>';

// menu:end

switch ($_GET['tab']) {
case'review':
    courses_show_review();
    break;
case'specs':
    courses_show_specs();
    break;
case'opinions':
    courses_show_useropinions();
    break;
case'overview':
default:
    courses_show_overview();
    break;
}
  require_once XOOPS_ROOT_PATH . '/footer.php';

function courses_show_overview()
{
    global $xoopsDB, $xoopsTpl, $xoopsOption;
#    $GLOBALS['xoopsOption']['template_main'] = 'course_overview.html';

    $quer = sprintf(
        "SELECT cr.cteaser, cr.creview, pr.image_url as p_photo, cr.num , pr.lname, pr.fname, dp.dept_name,  %s , %s, %s ,%s , %s , %s , %s  FROM %s rv , %s pr , %s cr, %s dp WHERE rv.course_id=${_GET['cid']} and  cr.dept_id=dp.dept_id and rv.prof_id=pr.prof_id and rv.course_id=cr.course_id  group by rv.course_id ",
        'AVG(rv.overall) av_overall',
        'AVG(rv.difficulty) av_diff',
        'AVG(rv.usefulness) av_useful',
        'AVG(rv.effort) av_effort',
        'AVG(rv.prof_effect) av_effect',
        'AVG(rv.prof_fair) av_fair',
        'AVG(rv.prof_avail) av_avail',
        $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('cr_profs'),
        $xoopsDB->prefix('cr_courses'),
        $xoopsDB->prefix('cr_depts')
    );

    $res = $xoopsDB->query($quer);

    $only_row = $xoopsDB->fetchArray($res);

    #	echo $quer;

    $cnum = $only_row['num'];

    $depname = $only_row['dept_name'];

    $crarray['rtitle'] = "$depname $cnum, ";

    $crarray['isprof'] = false;

    $crarray['profs'] = Review::getByCourse($_GET['cid']);

    echo $xoopsDB->error();

    $crarray['scale_bar'] = "<img width=100 height=14  SRC='images/rating_scale.gif'>";

    $crarray['avg']['overall'] = rank_image($only_row['av_overall']) . ' ' . $only_row['av_overall'];

    $crarray['avg']['diff'] = rank_image($only_row['av_diff']) . ' ' . $only_row['av_diff'];

    $crarray['avg']['useful'] = rank_image($only_row['av_useful']) . ' ' . $only_row['av_useful'];

    $crarray['avg']['effort'] = rank_image($only_row['av_effort']) . ' ' . $only_row['av_effort'];

    $crarray['avg']['effect'] = rank_image($only_row['av_effect']) . ' ' . $only_row['av_effect'];

    $crarray['avg']['fair'] = rank_image($only_row['av_fair']) . ' ' . $only_row['av_fair'];

    $crarray['avg']['avail'] = rank_image($only_row['av_avail']) . ' ' . $only_row['av_avail'];

    $ts = MyTextSanitizer::getInstance();

    $crarray['cteaser'] = $ts->displayTarea($only_row['cteaser'], 1, 1, 1, 1, 1);

    $crarray['morelink'] = "<a href='?cid=${_GET['cid']}&tab=review' >" . _MORE . '</a>';

    $sql = sprintf(
        "SELECT pr.fname, pr.lname, rv.term, rv.year, AVG(rv.overall) as avg_overall, CONCAT(rv.term,rv.year) as termyear ,CONCAT(rv.term,rv.year,pr.fname,pr.lname) as termprof FROM %s rv, %s pr WHERE pr.prof_id = rv.prof_id and rv.course_id = ${_GET['cid']} group by termprof ",
#	$sql = sprintf("SELECT pr.fname, pr.lname, rv.term, rv.year, rv.overall,CONCAT(rv.term,rv.year,pr.fname,pr.lname) as termprof FROM %s rv, %s pr WHERE rv.course_id = ${_GET['cid']}  ",
                    $xoopsDB->prefix('cr_reviews'),
        $xoopsDB->prefix('cr_profs')
    );

    $res = $xoopsDB->query($sql);

    echo $xoopsDB->error();

    $crarray['termoverviews'] = [];

    while (false !== ($reviews_rows = $xoopsDB->fetchArray($res))) {
        #		echo $reviews_rows['term'].' '.$reviews_rows['year'].rank_image($reviews_rows['overall']).' '.$reviews_rows['avg_overall']."<br / >";

        $reviews_rows['rankimg'] = rank_image($reviews_rows['avg_overall']);

        $reviews_rows['instructor'] = $reviews_rows['fname'] . ' ' . $reviews_rows['lname'];

        $crarray['termoverviews'][] = $reviews_rows;
    }

    if (Review::getByUID(30, true) > 0) {
        $crarray['show_edit'] = true;
    }

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:course_overview.html');
}
function courses_show_review()
{
    global $xoopsDB, $xoopsTpl, $xoopsOption;

    $reviewpage = $_GET['page'] ?: 0;

    $course_c = new Course($_GET['cid']);

    $course_review = $course_c->getVar('creview', 's');

    if ('' != trim($course_review)) {
        $reviewtext = explode('[pagebreak]', $course_review);

        $review_pages = count($reviewtext);

        if ($review_pages > 1) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

            $pagenav = new XoopsPageNav($review_pages, 1, $reviewpage, 'page', "cid=${_GET['cid']}&tab=review");

            $crarray['navbar'] = $pagenav->renderNav();

            if (0 == $storypage) {
                //	    	$story['bodytext'] = $story['lead'].'<br><br>'.htmlspecialchars($articletext[$storypage]);

                $crarray['creview'] = $reviewtext[$reviewpage];
            } else {
                //			$story['bodytext'] = htmlspecialchars($articletext[$storypage]);

                $crarray['creview'] = $reviewtext[$reviewpage];
            }
        } else {
            //		$story['bodytext'] = $story['lead'].'<br><br>'.htmlspecialchars($bodytext);

            $crarray['creview'] = $reviewtext[$reviewpage];
        }
    }

    //hack end

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:course_review.html');
}
function courses_show_specs()
{
}

function courses_show_useropinions()
{
    global $xoopsDB, $xoopsTpl;

    $quer = sprintf(
        "SELECT rv.overall , rv.usefulness ,rv.course_review, rv.prof_review ,rv.rev_id , us.uname, dp.dept_name, cr.num
					FROM %s rv , %s us, %s cr, %s dp  
					WHERE rv.course_id=cr.course_id AND rv.dept_id=dp.dept_id AND rv.rev_uid=us.uid and rv.course_id=${_GET['cid']} and rv.course_review!=''",
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

        $review['text'] = $row['course_review'];

#    $title = substr($row[course_review], 0, 20);

        $review['overall'] = rank_image($row['overall']) . ' ' . $row['overall'];

        $review['usefulness'] = rank_image($row['usefulness']) . ' ' . $row['usefulness'];

        $review['id'] = $row['rev_id'];

        array_unshift($crarray['reviews'], $review);
    }

    $xoopsTpl->assign('crarray', $crarray);

    $xoopsTpl->display('db:course_useropinions.html');
}

require_once XOOPS_ROOT_PATH . '/footer.php';
