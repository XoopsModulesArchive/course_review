<?php

// $Id: course.inc.php,v 1.1 2006/03/27 13:59:18 mikhail Exp $
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

class Course extends CrObject
{
    public $db;

    public $session_var = 'cr_store';

    public $session_topic = 'COURSE';

    public function __construct($id = null)
    {
        $this->session_var = &$_SESSION[$this->session_var];

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        #initVar($key, $course, $value = null, $required = false, $maxlength = null, $options = '')

        $this->initVar('course_id', XOBJ_DTYPE_INT, 0, '', false);

        $this->initVar('dept_id', XOBJ_DTYPE_INT, 0, '', false);

        $this->initVar('num', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('term', XOBJ_DTYPE_TXTBOX);

        $this->initVar('year', XOBJ_DTYPE_INT);

        $this->initVar('units', XOBJ_DTYPE_INT);

        $this->initVar('cteaser', XOBJ_DTYPE_TXTAREA);

        $this->initVar('creview', XOBJ_DTYPE_TXTAREA);

        $this->initVar('dohtml', XOBJ_DTYPE_OTHER, 1);

        $this->initVar('doxcode', XOBJ_DTYPE_OTHER, 1);

        $this->initVar('dosmiley', XOBJ_DTYPE_OTHER, 1);

        $this->initVar('doimage', XOBJ_DTYPE_OTHER, 1);

        $this->initVar('dobr', XOBJ_DTYPE_OTHER, 1);

        if (!empty($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        }
    }

    public function load($course_id)
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE course_id=%s',
            $this->db->prefix('cr_courses'),
            $course_id
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

        $id = $this->getVar('course_id');

        if (empty($id)) {
            $sql = sprintf(
                "INSERT INTO %s (dept_id, num, name, term, year, units, cteaser, creview) VALUES (%s, '%s', '%s', '%s' , '%s', '%s', '%s', '%s')",
                $this->db->prefix('cr_courses'),
                $this->getVar('dept_id'),
                $this->getVar('num'),
                $this->getVar('name'),
                $this->getVar('term'),
                $this->getVar('year'),
                $this->getVar('units'),
                $this->getVar('cteaser'),
                $this->getVar('creview')
            );

            $result = $this->db->queryF($sql);

            $id = $this->db->getInsertId();
        } else {
            $sql = sprintf(
                "UPDATE %s SET dept_id=%s, num='%s', name='%s', term='%s', year='%s', units='%s', cteaser='%s', creview='%s' where course_id=$id",
                $this->db->prefix('cr_courses'),
                $this->getVar('dept_id'),
                $this->getVar('num', '', true),
                $this->getVar('name', '', true),
                $this->getVar('term'),
                $this->getVar('year'),
                $this->getVar('units'),
                $this->getVar('cteaser'),
                $this->getVar('creview')
            );

            $result = $this->db->query($sql);
        }

        echo $sql;

        if (!$result) {
            echo $sql;

            echo $this->db->error();

            $this->setErrors(_CLDNTSTOREDATA);

            return false;
        }

        return $id;
    }

    //Public static

    public function getByNum($course_num)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT course_id FROM ' . $db->prefix('cr_courses') . " WHERE num = '$course_num'";

        $result = $db->query($sql);

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        $myrow = $db->fetchArray($result);

        return $myrow;
    }

    //Public static

    public function getByNum_Term_Year($course_num, $course_term, $course_year)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT course_id FROM ' . $db->prefix('cr_courses') . " WHERE num = '$course_num' and term= '$course_term' and year = '$course_year'";

        $result = $db->query($sql);

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        $myrow = $db->fetchArray($result);

        return $myrow;
    }

    //Public Static

    public function getByDepart($did)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT * FROM ' . $db->prefix('cr_courses') . " WHERE dept_id=$did";

        $result = $db->query($sql);

        echo $db->error();

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        $myrows = [];

        while (false !== ($myrow = $db->fetchArray($result))) {
            $myrows[] = $myrow;
        }

        return $myrows;
    }

    //Public Static

    public function &getAll()
    {
    }

    //Public Static

    # int ids

    # array ids : array(0 ,id1)

    # array ids : array(1 , id2)

    public function delete($ids)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if (!is_array($ids)) {
            $ids[0] = $ids;
        }

        foreach ($ids as $key => $id) {
            $sql = 'delete from ' . $db->prefix('cr_courses') . " where course_id=$id";

            $db->query($sql);

            echo $db->error();
        }
    }
}
