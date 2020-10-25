<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';
  include 'class/course.inc.php';
  include 'class/review.inc.php';

  switch ($op) {
  case'maction':

    mass_action();

   break;
  case'modify':

    $review_s = new Review($_GET['rid']);
    $course_s = new Course($review_s->getVar('course_id'));

    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    include 'include/editform_cr.inc.php';

   break;
    case'do_modify':

    $review_s = new Review($_GET['rid']);
    $review_s->setVar('rev_id', $_GET['rid']);
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
    if ($review_s->store()) {
        $course_s = new Course($review_s->getVar('course_id'));

        $course_s->setVar('num', $_POST['num']);

        $course_s->store();

        redirect_header('edit.php', 3, _DATAUPDATED);

        exit();
    }

  break;
  default:

    user_reviews($xoopsUser->getVar('uid'));

   break;
  }

  require_once XOOPS_ROOT_PATH . '/footer.php';

 function user_reviews($uid)
 {
     global $xoopsModuleConfig,$xoopsUser;

     $review_s = new Review();

     $res_rows = $review_s->getByUID($uid);

     echo "<fieldset>\n";

     echo '   <legend class="blockTitle">This is your reviews ,' . $xoopsUser->getVar('uname') . " </legend>\n";

     echo "   <div class=\"blockContent\"><br>\n";

     if (!$res_rows) {
         echo '<table>';

         echo '<tr>';

         echo "<td colspan=4>No reviews  <a href='add.php'><b>Add review</b></a></td>\n";

         echo '</tr>';

         echo '</table>';

         echo '</fieldset>';

         return 1;
     }

     echo "<form method=post action='?op=maction'>";

     echo '<table>';

     echo '<tr>';

     echo "<td><b>Review Id</b></td>\n";

     echo "<td><b>Course number</b></td>\n";

     echo "<td><b>Review</b></td>\n";

     echo "<td><b></b></td>\n";

     echo "<td><b>Delete</b></td>\n";

     echo '</tr>';

     foreach ($res_rows as $row) {
         echo '<tr>';

         echo "<td>$row[rev_id]</td>\n";

         echo "<td>$row[num]</td>\n";

         $row[course_review] = mb_substr($row[course_review], 0, $xoopsModuleConfig['char_limit']);

         echo "<td>$row[course_review]</td>\n";

         echo "<td><a href='?op=modify&rid=$row[rev_id]'>edit</a></td>\n";

         echo "<td><input type=checkbox name=del_items[] value='$row[rev_id]'></td>\n";

         echo '</tr>';
     }

     echo '<tr>';

     echo "<td colspan=5><input type=submit ></td>\n";

     echo '</tr>';

     echo '</table>';

     echo '</fieldset>';

     echo '</form>';
 }

function mass_action()
{
    # print_r($_POST[del_items]);

    if (is_array($_POST[del_items])) {
        foreach ($_POST[del_items] as $rid) {
            Review::delete($rid);
        }
    }

    redirect_header('edit.php', 3, _DATAUPDATED);
}
