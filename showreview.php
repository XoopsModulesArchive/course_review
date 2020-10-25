<?php

	require dirname(__DIR__, 2) . '/mainfile.php';
	include 'config.inc.php';
	require_once 'class/review.inc.php';
	require_once 'class/course.inc.php';
    require_once 'class/professor.inc.php';
	require_once 'include/functions.inc.php';

	
  if (!is_object($xoopsUser)) {

   redirect_header(XOOPS_URL . '/user.php', 5, _NOPERM);

   exit();

 }

  $GLOBALS['xoopsOption']['template_main'] = 'showreview.html';

  $review_s = new Review($_GET['rev_id']);
  if ($op == 'showsyllabus') {show_syllabus($rev_id);exit();}

  require XOOPS_ROOT_PATH.'/header.php';

 $myts = MyTextSanitizer::getInstance();
  
   $prof_review = $review_s->getVar('prof_review','n');
   $course_review = $review_s->getVar('course_review','n');


  $prof_review = $myts->displayTarea($prof_review,1,1,1,1,1);
  $course_review = $myts->displayTarea($course_review,1,1,1,1,1);
  $xoopsTpl->assign('prof_review', $prof_review);
  $xoopsTpl->assign('course_review', $course_review);
  $xoopsTpl->assign('syllabus_url', XOOPS_URL.'/'.$xoopsModuleConfig[uploaddir].'/'.$review_s->getVar('syllabus_url'));
  $smime = $review_s->getVar('syllabus_mime');
  $xoopsTpl->assign('syllabus_title', sprintf("<img src='images/mime_types/%s' alt='%s'>Show", $mime_types[(string)$smime][1], $mime_types[(string)$smime][2]));
  $xoopsTpl->assign('image_url', XOOPS_URL.'/'.$xoopsModuleConfig[uploaddir].'/'.$review_s->getVar('image_url'));
  $xoopsTpl->assign('is_syllabus', $review_s->getVar('syllabus_url'));
  $xoopsTpl->assign('is_picture', $review_s->getVar('image_url'));
  $xoopsTpl->assign('scale_bar', "<img width=100 height=14  SRC='images/rating_scale.gif'>");
  $xoopsTpl->assign('difficulty', rank_image($review_s->getVar('difficulty')).' '.$review_s->getVar('difficulty'));
  $xoopsTpl->assign('usefulness', rank_image($review_s->getVar('usefulness')).' '.$review_s->getVar('usefulness'));
  $xoopsTpl->assign('effort', rank_image($review_s->getVar('effort')).' '.$review_s->getVar('effort'));
  $xoopsTpl->assign('prof_effect', rank_image($review_s->getVar('prof_effect')).' '.$review_s->getVar('prof_effect'));
  $xoopsTpl->assign('prof_fair', rank_image($review_s->getVar('prof_fair')).' '.$review_s->getVar('prof_fair'));
  $xoopsTpl->assign('prof_avail', rank_image($review_s->getVar('prof_avail')).' '.$review_s->getVar('prof_avail'));
  $xoopsTpl->assign('overall', rank_image($review_s->getVar('overall')).' '.$review_s->getVar('overall'));

  $course_s = new Course($review_s->getVar('course_id'));
  $xoopsTpl->assign('cname', $course_s->getVar('name'));

  $prof_s = new Professor($review_s->getVar('prof_id'));	
    
  $xoopsTpl->assign('plname', $prof_s->getVar('lname'));
  $xoopsTpl->assign('pfname', $prof_s->getVar('fname'));

#  $user_h 	= new XoopsUserHandler;
	$user_h = xoops_getHandler('member');
	

  $user_obj	= $user_h->getUser($review_s->getVar('rev_uid'));
  


  $xoopsTpl->assign('uname', $user_obj->uname('S'));
  $xoopsTpl->assign('ufrom', $user_obj->user_from('S'));

  $xoopsTpl->assign('rev_id', $review_s->getVar('rev_id'));

  $result = $xoopsDB->query('SELECT * from ' . $xoopsDB->prefix('cr_raitings') . " WHERE rev_id=$_GET[rev_id]");
  $xoopsTpl->assign('link_loc', XOOPS_URL . '/userinfo.php?uid=' . $review_s->getVar('rev_id'));

  $useful_string = '';

  if ($GLOBALS['xoopsDB']->getRowsNum($result) != 0) {

    $row = $xoopsDB->fetchArray($result);

    $num_useful = $row['num_useful'];

    $total = $row['total'];

		

    $useful_string .= '<font size=2 face=verdana color=#ff0000><b>';

    $useful_string .= sprintf(_CR_PPLFNDITUSEFULL,$num_useful ,$total);

    $useful_string .= '</b></font>';

  }

  $xoopsTpl->assign('useful_string', $useful_string);



  $myform = '';

  $cookie_name = 'cr_' . $xoopsUser->uname('S') . '_rated_' . $_GET['rev_id'];

  if (!isset($_COOKIE[$cookie_name])) {

    $myform .= '<form method="post" action="rating.php" name="useful_rate_form">';
    $myform .= '<input type="hidden" name="rev_id" value="';
    $myform .= $_GET['rev_id'];
    $myform .= '">';
    $myform .= '<p><br></p>';
    $myform .= '<font face="verdana" size="2" color="#ff0000">Did you find this review helpful?</font>&nbsp;&nbsp;';
    $myform .= '<font face="verdana" size="2">';
    $myform .= '	' . _YES . '&nbsp;<input type="radio" name="useful" value="yes">&nbsp;&nbsp;';

    $myform .= '	' . _NO . '&nbsp;<input type="radio" name="useful" value="no">';

    $myform .= '</font>&nbsp;&nbsp;<input type="submit" ></form>';

  }	// end if

  $xoopsTpl->assign('myform', $myform);



  require XOOPS_ROOT_PATH.'/include/comment_view.php';
  require_once XOOPS_ROOT_PATH.'/footer.php';

  







  function show_syllabus ($rev_id){

  global $xoopsDB,$review_s;


  $syllabus_text = $review_s->getVar('syllabus_text');



?>

	<html>

	<body>

	<table>

	<tr>

	<td>

        <?php echo $syllabus_text;?>

	</td>

	</tr>

	<tr>

	<td>

	<a href='javascript:window.close();'>Close</a>

	</td>

	</tr>

	</table>

	</body>

	</html>

      <?php


  }

?>
