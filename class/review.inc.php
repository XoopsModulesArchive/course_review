<?php

// $Id: review.inc.php,v 1.1 2006/03/27 13:59:18 mikhail Exp $
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

require_once 'cr_object.inc.php';

class Review extends CrObject
{
    public $db;

    public $session_var = 'cr_store';

    public $session_topic = 'REVIEW';

    public function __construct($id = null)
    {
        $this->session_var = &$_SESSION[$this->session_var];

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->initVar('rev_id', XOBJ_DTYPE_INT);

        $this->initVar('dept_id', XOBJ_DTYPE_INT);

        $this->initVar('course_id', XOBJ_DTYPE_INT);

        $this->initVar('prof_id', XOBJ_DTYPE_INT);

        $this->initVar('rev_uid', XOBJ_DTYPE_INT);

        $this->initVar('icon', XOBJ_DTYPE_TXTBOX);

        $this->initVar('prof_review', XOBJ_DTYPE_TXTAREA, '', true);

        $this->initVar('course_review', XOBJ_DTYPE_TXTAREA, '', true);

        $this->initVar('syllabus_url', XOBJ_DTYPE_TXTBOX);

        $this->initVar('syllabus_text', XOBJ_DTYPE_TXTAREA);

        $this->initVar('image_url', XOBJ_DTYPE_TXTBOX);

        $this->initVar('difficulty', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('usefulness', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('effort', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('prof_effect', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('prof_fair', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('prof_avail', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('overall', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('feature', XOBJ_DTYPE_INT);

        $this->initVar('approve', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('comments', XOBJ_DTYPE_INT);

        $this->initVar('syllabus_mime', XOBJ_DTYPE_TXTBOX);

        $this->initVar('time', XOBJ_DTYPE_STIME);

        $this->initVar('term', XOBJ_DTYPE_TXTBOX);

        $this->initVar('year', XOBJ_DTYPE_INT);

        if (!empty($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        }
    }

    public function load($id)
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE rev_id=%s',
            $this->db->prefix('cr_reviews'),
            $id
        );

        $myrow = $this->db->fetchArray($this->db->query($sql));

        $this->assignVars($myrow);
    }

    public function store()
    {
        if (!$this->cleanVars()) {
            return false;
        }

        foreach ($this->cleanVars as $k => $v) {
            $$k = $v;
        }

        $id = $this->getVar('rev_id');

        if (empty($id)) {
            $sql = sprintf(
                "INSERT INTO %s (dept_id, course_id, prof_id, icon, course_review, prof_review, syllabus_url, image_url, difficulty, usefulness, effort, prof_effect, prof_fair, prof_avail, overall, rev_uid, comments, feature, approve , syllabus_mime, term, year) VALUES (%d, %d, %d, '%s', '%s', '%s', '%s', '%s', %d, %d, %d, %d, %d,  %d, %d, %d, '%s', %d, %d , '%s', '%s', %d)",
                $this->db->prefix('cr_reviews'),
                $this->getVar('dept_id'),
                $this->getVar('course_id'),
                $this->getVar('prof_id'),
                $this->getVar('icon'),
                $course_review,
                $prof_review,
                $this->getVar('syllabus_url'),
                $this->getVar('image_url'),
                $this->getVar('difficulty'),
                $this->getVar('usefulness'),
                $this->getVar('effort'),
                $this->getVar('prof_effect'),
                $this->getVar('prof_fair'),
                $this->getVar('prof_avail'),
                $this->getVar('overall'),
                $this->getVar('rev_uid'),
                $this->getVar('comments'),
                $this->getVar('feature'),
                $this->getVar('approve'),
                $this->getVar('syllabus_mime'),
                $this->getVar('term'),
                $this->getVar('year')
            );

            $result = $this->db->queryF($sql);

            $id = $this->db->getInsertId();
        } else {
            $sql = sprintf(
                "UPDATE %s SET dept_id=%d, course_id=%d, prof_id=%d, icon='%s', course_review='%s', prof_review='%s', syllabus_url='%s', image_url='%s', difficulty=%d, usefulness=%d, effort=%d, prof_effect=%d, prof_fair=%d, prof_avail=%d, overall=%d, rev_uid=%d, comments=%d, feature=%d, approve=%d , syllabus_mime='%s' , term = '%s', year = %d where rev_id=$id",
                $this->db->prefix('cr_reviews'),
                $this->getVar('dept_id'),
                $this->getVar('course_id'),
                $this->getVar('prof_id'),
                $this->getVar('icon'),
                $this->getVar('course_review'),
                $this->getVar('prof_review'),
                $this->getVar('syllabus_url'),
                $this->getVar('image_url'),
                $this->getVar('difficulty'),
                $this->getVar('usefulness'),
                $this->getVar('effort'),
                $this->getVar('prof_effect'),
                $this->getVar('prof_fair'),
                $this->getVar('prof_avail'),
                $this->getVar('overall'),
                $this->getVar('rev_uid'),
                $this->getVar('comments'),
                $this->getVar('feature'),
                $this->getVar('approve'),
                $this->getVar('syllabus_mime'),
                $this->getVar('term'),
                $this->getVar('year')
            );

            $result = $this->db->query($sql);
        }

        if (!$result) {
            echo $sql;

            echo $this->db->error();

            $this->setErrors(_CLDNTSTOREDATA);

            return false;
        }

        return $id;
    }

    //Public Static

    public function getByUID($uid, $cnt = false)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if ($cnt) {
            $sql = sprintf('select count(rv.rev_id) as cnt from %s rv , %s cr where rv.course_id=cr.course_id and rev_uid=%s', $db->prefix('cr_reviews'), $db->prefix('cr_courses'), $uid);

            $result = $db->query($sql);

            $row = $db->fetchArray($result);

            return $row['cnt'];
        }

        $sql = sprintf('select * from %s rv , %s cr where rv.course_id=cr.course_id and rev_uid=%s', $db->prefix('cr_reviews'), $db->prefix('cr_courses'), $uid);

        $result = $db->query($sql);

        $res = [];

        if (!$result) {
            return false;
        }

        while (false !== ($row = $db->fetchArray($result))) {
            $res[] = $row;
        }

        return $res;
    }

    public function delete($rid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = sprintf("delete from %s where rev_id=$rid", $db->prefix('cr_reviews'));

        $db->queryF($sql);

        echo $db->error();
    }

    public function getByCourse($cid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = sprintf(
            "SELECT * FROM %s rv, %s pr WHERE rv.prof_id=pr.prof_id and rv.course_id=$cid",
            $db->prefix('cr_reviews'),
            $db->prefix('cr_profs')
        );

        $res = $db->query($sql);

        while (false !== ($row = $db->fetchArray($res))) {
            $rows[$row['prof_id']] = $row;
        }

        return $rows;
    }

    // professors photo uploaded by users with review
//      int pid - professor id
//

    public function getUsersPhotos($pid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = sprintf(
            "SELECT rv.prof_id, rv.image_url,   FROM %s rv WHERE  rv.image_url!=''",
            $db->prefix('cr_reviews')
        );

        $result = $db->query($sql);

        echo $db->error();

        $rows = [];

        if (!$result) {
            return false;
        }

        while (false !== ($row = $db->fetchArray($result))) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getPhotosByProfessor($pid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = sprintf(
            "select rv.image_url , us.uname, us.uid as uid from %s rv, %s us  where rv.prof_id=$pid and rv.image_url!='' and us.uid=rv.rev_uid",
            $db->prefix(cr_reviews),
            $db->prefix(users)
        );

        $rows = [];

        $result = $db->query($sql);

        echo $db->error();

        if (!$result) {
            return false;
        }

        while (false !== ($row = $db->fetchArray($result))) {
            $rows[] = $row;
        }

        return $rows;
    }
}
