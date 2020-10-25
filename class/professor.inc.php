<?php

// $Id: professor.inc.php,v 1.1 2006/03/27 13:59:18 mikhail Exp $
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

class Professor extends CrObject
{
    public $db;

    public $session_var = 'cr_store';

    public $session_topic = 'PROFESSOR';

    public function __construct($id = null)
    {
        $this->session_var = &$_SESSION[$this->session_var];

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->initVar('prof_id', XOBJ_DTYPE_INT);

        $this->initVar('dept_id', XOBJ_DTYPE_INT);

        $this->initVar('lname', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('fname', XOBJ_DTYPE_TXTBOX, '', true);

        $this->initVar('image_url', XOBJ_DTYPE_TXTBOX);

        $this->initVar('pteaser', XOBJ_DTYPE_TXTAREA);

        $this->initVar('preview', XOBJ_DTYPE_TXTAREA);

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

    public function load($prof_id)
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE prof_id=%s',
            $this->db->prefix('cr_profs'),
            $prof_id
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

        $id = $this->getVar('prof_id');

        if (empty($id)) {
            $sql = sprintf(
                "INSERT INTO %s (dept_id, lname, fname, image_url, pteaser, preview) VALUES (%s, '%s', '%s', '%s', '%s', '%s')",
                $this->db->prefix('cr_profs'),
                $this->getVar('dept_id'),
                $this->getVar('lname'),
                $this->getVar('fname'),
                $this->getVar('image_url'),
                $this->getVar('pteaser', 'n'),
                $this->getVar('preview', 'n')
            );

            $result = $this->db->queryF($sql);

            $id = $this->db->getInsertId();
        } else {
            $sql = sprintf(
                "UPDATE %s SET dept_id=%s, lname='%s', fname='%s', image_url='%s', pteaser='%s', preview='%s' where prof_id=$id",
                $this->db->prefix('cr_profs'),
                $this->getVar('dept_id'),
                $this->getVar('lname'),
                $this->getVar('fname'),
                $this->getVar('image_url'),
                $this->getVar('pteaser', 'n'),
                $this->getVar('preview', 'n')
            );

            $result = $this->db->queryF($sql);
        }

        if (!$result) {
            #			echo $sql;

            echo $this->db->error();

            $this->setErrors(_CLDNTSTOREDATA);

            return false;
        }

        return $id;
    }

    //Public static

    public function getByFullName($fname, $lname)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT prof_id FROM ' . $db->prefix('cr_profs') . " WHERE lname = '$lname' and fname = '$fname'";

        $result = $db->query($sql);

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        while (false !== ($row = $db->fetchArray($result))) {
            $myrow = $row;
        }

        return $myrow;
    }

    public function getByFullNameSoundex($fname, $lname)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT * FROM ' . $db->prefix('cr_profs') . " WHERE SOUNDEX(lname) = SOUNDEX('$lname') and SOUNDEX(fname) = SOUNDEX('$fname')";

        $result = $db->query($sql);

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        while (false !== ($row = $db->fetchArray($result))) {
            $myrows[] = $row;
        }

        return $myrows;
    }

    public function &getByCourse($cid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT * FROM ' . $db->prefix('cr_profs') . ' ';

        $res = $db->query($sql);

        $rows = [];

        while (false !== ($row = $db->fetchArray($res))) {
            $rows[] = $row;
        }

        return $rows;
    }

    //Public Static

    public function getAll()
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT * FROM ' . $db->prefix('cr_profs') . ' ';

        $res = $db->query($sql);

        if (!$res) {
            return false;
        }

        $rows = [];

        while (false !== ($row = $db->fetchArray($res))) {
            $rows[] = $row;
        }

        return $rows;
    }

    //Public Static

    public function getPhotos($pid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT * FROM ' . $db->prefix('cr_photos') . " WHERE prof_id = $pid";

        $res = $db->query($sql);

        $resrows = [];

        if ($db->error()) {
            return false;
        }

        while (false !== ($myrow = $db->fetchArray($res))) {
            $resrows[] = $myrow;
        }

        return $resrows;
    }

    //Public Static

    public function &getPhoto($pid)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = sprintf(
            "SELECT * FROM %s  ph, %s u  WHERE  ph.prof_id=$pid order by rating DESC LIMIT 0,1",
            $db->prefix('cr_photos')
        );

        $res = $db->query($sql);

        while (false !== ($myrow = $db->fetchArray($res))) {
            $img = $myrow['img'];
        }

        return $img;
    }

    public function &storePhoto($pid, $img_url)
    {
        global $xoopsUser;

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->uid();
        } else {
            $uid = 0;
        }

        $sql = 'INSERT INTO ' . $db->prefix('cr_photos') . " (img, prof_id, uid) VALUES ('$img_url', $pid, $uid)";

        echo $sql;

        $db->query($sql);

        echo $db->error();
    }
}
