<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';
  require_once 'include/functions.inc.php';
  include 'class/review.inc.php';

  $GLOBALS['xoopsOption']['template_main'] = 'course_reviews.html';

  if (null === $_GET['course'] || '' == $_GET['course']) {
      $quer = sprintf(
          "select pr.image_url as p_photo, cr.num, pr.prof_id, pr.fname , pr.lname , %s , %s , %s , %s , %s , %s , %s  from %s rv , %s pr , %s cr where pr.fname=\"$_GET[profFname]\" and pr.lname=\"$_GET[profLname]\" and rv.prof_id=pr.prof_id and rv.course_id=cr.course_id  group by rv.course_id ",
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

      $crarray['isprof'] = true;
  } else {
      $quer = sprintf(
          "SELECT pr.image_url as p_photo, cr.num , pr.lname, pr.fname, dp.dept_name,  %s , %s, %s ,%s , %s , %s , %s  FROM %s rv , %s pr , %s cr, %s dp WHERE rv.course_id=$_GET[course] and  rv.dept_id=dp.dept_id and rv.prof_id=pr.prof_id and rv.course_id=cr.course_id  group by rv.course_id ",
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

      $lname = $only_row['lname'];

      $fname = $only_row['fname'];

      $cnum = $only_row['num'];

      $depname = $only_row['dept_name'];

      $crarray['rtitle'] = "$depname $cnum, ";

      crarray['isprof'] = false;

      $crarray['profs'] = Review::getByCourse($_GET['course']);
  }

#echo $quer;

  echo $xoopsDB->error();
#  echo $quer;

//  $crarray[photo]=sprintf("<a target=_blank href='%s/$xoopsModuleConfig[uploaddir]/$only_row[p_photo]'><img src='%s?image_url=$only_row[p_photo]' alt='$only_row[p_photo]'></a>",

//	XOOPS_URL,

//				XOOPS_URL.'/modules/courseReview/image_show.php');

$crarray['photo'] = sprintf("<a target=_blank href='#'><img src='%s/modules/%s/images/nophoto.gif' alt='blah'></a>", XOOPS_URL, $xoopsModule->getVar('dirname'));

  $crarray['scale_bar'] = "<img width=100 height=14  SRC='images/rating_scale.gif'>";
  $crarray['avg']['overall'] = rank_image($only_row['av_overall']) . ' ' . $only_row['av_overall'];

  $crarray['avg']['diff'] = rank_image($only_row['av_diff']) . ' ' . $only_row['av_diff'];

  $crarray['avg']['useful'] = rank_image($only_row['av_useful']) . ' ' . $only_row['av_useful'];

  $crarray['avg']['effort'] = rank_image($only_row['av_effort']) . ' ' . $only_row['av_effort'];

  $crarray['avg']['effect'] = rank_image($only_row['av_effect']) . ' ' . $only_row['av_effect'];

  $crarray['avg']['fair'] = rank_image($only_row['av_fair']) . ' ' . $only_row['av_fair'];

  $crarray['avg']['avail'] = rank_image($only_row['av_avail']) . ' ' . $only_row['av_avail'];

  if (null === $_GET['course'] || '' == $_GET['course']) {
      $quer = sprintf(
          "SELECT rv.overall , rv.usefulness , rv.prof_review ,rv.rev_id , us.uname, dp.dept_name, cr.num
					FROM %s rv , %s us, %s cr, %s dp  
					WHERE rv.course_id=cr.course_id AND rv.dept_id=dp.dept_id AND rv.rev_uid=us.uid and rv.prof_id=$only_row[prof_id]",
          $xoopsDB->prefix('cr_reviews'),
          $xoopsDB->prefix('users'),
          $xoopsDB->prefix('cr_courses'),
          $xoopsDB->prefix('cr_depts')
      );
  } else {
      $quer = sprintf(
          "SELECT rv.overall , rv.usefulness , rv.prof_review ,rv.rev_id , us.uname, dp.dept_name, cr.num
					FROM %s rv , %s us, %s cr, %s dp  
					WHERE rv.course_id=cr.course_id AND rv.dept_id=dp.dept_id AND rv.rev_uid=us.uid and rv.course_id=$_GET[course]",
          $xoopsDB->prefix('cr_reviews'),
          $xoopsDB->prefix('users'),
          $xoopsDB->prefix('cr_courses'),
          $xoopsDB->prefix('cr_depts')
      );
  }
#  echo $quer;

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

  require_once XOOPS_ROOT_PATH . '/footer.php';
