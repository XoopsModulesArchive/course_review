<?php

// $Id: functions.inc.php,v 1.1 2006/03/27 13:59:20 mikhail Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://xoopscube.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

    $breadcrumbs['main']['caption'] = 'main';
    $breadcrumbs['main']['url'] = XOOPS_URL . '/modules/courseReview/';

    $breadcrumbs['search']['caption'] = 'search';
    $breadcrumbs['search']['url'] = XOOPS_URL . '/modules/courseReview/search.php';
    $breadcrumbs['search']['parent'] = &$breadcrumbs['main'];

    $breadcrumbs['professors']['caption'] = 'professors';
    $breadcrumbs['professors']['url'] = XOOPS_URL . '/modules/courseReview/search.php?searchType=prof&filled=1';
    $breadcrumbs['professors']['parent'] = &$breadcrumbs['search'];

    $breadcrumbs['courses']['caption'] = 'courses';
    $breadcrumbs['courses']['url'] = XOOPS_URL . '/modules/courseReview/search.php?filled=1';
    $breadcrumbs['courses']['parent'] = &$breadcrumbs['search'];

    $breadcrumbs['departments']['caption'] = 'departments';
    $breadcrumbs['departments']['url'] = &$breadcrumbs['departments'];
    $breadcrumbs['departments']['parent'] = &$breadcrumbs['search'];

    $breadcrumbs['currdep']['caption'] = 'departments';
    $breadcrumbs['currdep']['url'] = XOOPS_URL . '/modules/courseReview/search.php?searchType=prof&filled=1';
    $breadcrumbs['currdep']['parent'] = &$breadcrumbs['departments'];

    $breadcrumbs['currcourse']['caption'] = 'none';
    $breadcrumbs['currcourse']['url'] = 'http://xoops/modules/courseReview/courses.php?cid=8';
    $breadcrumbs['currcourse']['parent'] = &$breadcrumbs['courses'];

    $breadcrumbs['currprof']['caption'] = 'none';
    $breadcrumbs['currprof']['url'] = 'http://xoops/modules/courseReview/professors.php?pid=3';
    $breadcrumbs['currprof']['parent'] = &$breadcrumbs['professors'];

function send_ipb($from_id, $recipient_id, $title, $message)
{
    #$recipients_id=array();

    if (!is_array($recipient_id)) {
        #		echo 'is_not array';

        $recipients_id[] = $recipient_id;
    } else {
        $recipients_id = $recipient_id;
    }

    #		print_r ($recipients_id);

    foreach ($recipients_id as $rcpt_id) {
        $query = sprintf(
            "INSERT INTO %s_ipb_messages (member_id,msg_date,read_state,title,message,from_id,vid, recipient_id, tracking) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",
            XOOPS_DB_PREFIX,
            $member_id = $rcpt_id,
            $msg_date = time(),
            $read_state = 0,
            $title,
            $message,
            $from_id,
            $vid = 'in',
            $rcpt_id,
            $tracking = 0
        );

        #echo $query;

        $GLOBALS['xoopsDB']->queryF($query);

        $query = sprintf('UPDATE %s_users SET ', XOOPS_DB_PREFIX) .
                        'msg_total = msg_total + 1, ' .
                        'new_msg = new_msg + 1, ' .
                        "msg_from_id='$from_id', " .
                        "msg_msg_id='$new_id', " .
                        "show_popup='" . $show_popup . "' " .
                        "WHERE uid='" . $rcpt_id . "'";

        #echo $query;

        $GLOBALS['xoopsDB']->queryF($query);
    } # foreach
}
# function
function get_approved_members()
{
    global $xoopsModule;

    $gpermHandler = xoops_getHandler('groupperm');

    $memberHandler = xoops_getHandler('Membership');

    $uids = [];

    $criteria = new CriteriaCompo(new Criteria('gperm_name', 'approved'));

    $criteria->add(new Criteria(
        'gperm_modid',
        $xoopsModule->getVar(mid)
    ));

    $perms = $gpermHandler->getObjects($criteria, true);

    foreach (array_keys($perms) as $i) {
        $gid = $perms[$i]->getVar('gperm_groupid');

        $uids_t = $memberHandler->getUsersByGroup($gid);

        $uids = array_merge($uids, $uids_t);
    }

    foreach ($uids as $key => $value) {
        if (!$seen[$value]++) {
            $uids_u[] = $value;
        }
    }

    return $uids_u;
}

function test_perms($perm = 'approved')
{
    global $xoopsUser, $xoopsModule;

    $perm_name = $perm;

    $perm_itemid = '1';

    if ($xoopsUser) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $module_id = $xoopsModule->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    return $gpermHandler->checkRight($perm_name, $perm_itemid, $groups, $module_id);
}
function rank_image($rank)
{
    $height = '10';

    $max_rank = 10;

    $rank_k = $rank / $max_rank;

    $rank_width = 100;

    $left_lenght = $rank_width * $rank_k;

    $right_lenght = $rank_width - $left_lenght;

    #		echo "rank $rank max_rank $max_rank rank_k $rank_k left $left_lenght right $right_lenght ";

    $img_set = "<img alt=$rank width=$left_lenght height=$height  SRC='images/1x1red.gif'>";

    $img_reset = "<img  width=$right_lenght height=$height SRC='images/1x1gray.gif'>";

    return $img_set . $img_reset;
}
function cr_fieldset($title, $content)
{
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>
             " . $title . '</legend>';

    echo "<br><br><table width='100%' border='0' cellspacing='1' class='outer'>
              <tr><td class=\"odd\">" . $content;

    echo '</td></tr></table>';

    echo '</fieldset>';
}
function cr_breadcrumbs($item = 'search')
{
    global $breadcrumbs;

    #	foreach ($breadcrumbs as $breadcrumb) {

    #	echo $breadcrumb['caption']."<br>";

    #	}

    $content = $breadcrumbs[$item]['caption'];

    $item_arr = $breadcrumbs[$item]['parent'];

    while (isset($item_arr)) {
        $content = "<a href='${item_arr['url']}'>" . $item_arr['caption'] . '</a>' . ':' . $content;

        $item_arr = $item_arr['parent'];
    }

    return $content;
}
