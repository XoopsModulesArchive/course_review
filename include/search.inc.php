<?php

// $Id: search.inc.php,v 1.1 2006/03/27 13:59:20 mikhail Exp $
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

function myReviews_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $where = ' d.dept_id=r.dept_id AND p.prof_id=r.prof_id AND c.course_id=r.course_id AND approve = 1';

    if ($userid > 0) {
        $where .= ' AND r.rev_uid = ' . $userid;
    }

    if (is_array($queryarray) && $count = count($queryarray)) {
        $where .= " AND ((r.title LIKE '%$queryarray[0]%' OR r.review LIKE '%$queryarray[0]%' OR p.lname LIKE '%$queryarray[0]%' OR p.fname LIKE '%queryarray[0]%' OR c.name LIKE '%queryarray[0]%' OR d.dept_name LIKE '%queryarray[0]%' OR c.num LIKE '%queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $where .= ' ' . $andor . ' ';

            $where .= " AND ((r.title LIKE '%$queryarray[i]%' OR r.review LIKE '%$queryarray[i]%' OR p.lname LIKE '%$queryarray[i]%' OR p.fname LIKE '%queryarray[i]%' OR c.name LIKE '%queryarray[0]%' OR d.dept_name LIKE '%queryarray[i]%' OR c.num LIKE '%queryarray[i]%')";
        }

        $where .= ')';
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('cr_depts') . ' d, ' . $xoopsDB->prefix('cr_reviews') . ' r, ' . $xoopsDB->prefix('cr_profs') . ' p, ' . $xoopsDB->prefix('cr_courses') . ' c WHERE ' . $where . ' ORDER BY r.time DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while ($row = $xoopsDB->fetchArray($result)) {
        $ret[$i]['link'] = 'showreview.php?rev_id=' . $row['rev_id'];

        $ret[$i]['title'] = $row['title'];

        $ret[$i]['time'] = $row['time'];

        $ret[$i]['uid'] = $row['rev_uid'];

        $i++;
    }

    return $ret;
}
