<?php

require dirname(__DIR__, 2) . '/mainfile.php';

require XOOPS_ROOT_PATH . '/header.php';

require __DIR__ . '/config.inc.php';

require __DIR__ . '/include/functions.inc.php';

function do_report($rev_id)
{
    global $xoopsDB ,$xoopsUser,$modreasons;

    $table = $xoopsDB->prefix('cr_reviews');

    $reporting_tbl = $xoopsDB->prefix('cr_reporting');

    if (isset($rev_id)) {
        $reason = $_POST[reason];

        $reason_text = $_POST[reason_text];

        if ($reason < 99) {
            $reason_text = $modreasons[$reason];
        }

        $uid = $xoopsUser->getVar('uid');

        $quer = "insert into $reporting_tbl (uid, lid, mess , rid) values ($uid , $rev_id , '$reason_text', '$reason')";

        $GLOBALS['xoopsDB']->queryF($quer);

        # sent to user

        $uids = get_approved_members();

        send_ipb($uid, $uids, 'CourseReview moderate ', "As courseReview administrator, your moderation is requested for the review #$rev_id");

        redirect_header('index.php', 1, 'Thank you for submission. <br>Your message has sent to moderator');
    }
}

function showreport_form($rev_id)
{
    global $modreasons;

    echo "


	<table width=100%> 


	<form action='$PHP_SELF?op=do_report&rev_id=$rev_id' method=POST>


	<tr><th>Moderation Report</th></td>


	<tr><td align=center>Mark <b><a href='" . XOOPS_URL . "/modules/myAds/index.php?pa=viewannonces&lid=$lid'>this Listing</a></b> for moderation</td></tr>


	<tr><td>


	<p>


	You are requesting a moderator to delete or edit this listing.  Note: Abusing the moderation request system can result in future reports from your account being ignored 


	</p>


	<p>


	<b>Please select the reason to investigate this item:</b>


	</p>


	<br>";

    foreach ($modreasons as $key => $modreason) {
        $checked = (99 == $key) ? 'checked' : '';

        echo "<input type=radio name=reason value=$key $checked>$modreason<br>";
    }

    echo '<textarea name=reason_text cols=30 rows=5></textarea><br>


		 <input type=submit >	


	</td></tr>


	</form>


	</table>';
}

$op = $_GET['op'];

switch ($op) {
    case form:

     showreport_form($_GET['rev_id']);

     break;
    case do_report:

     do_report($_GET['rev_id']);

     break;
    default:

     showreport_form($_GET['rev_id']);

     break;
}

require_once XOOPS_ROOT_PATH . '/footer.php';
