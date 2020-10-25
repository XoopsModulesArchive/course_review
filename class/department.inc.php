<?php

// $Id: department.inc.php,v 1.1 2006/03/27 13:59:18 mikhail Exp $

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

class Department extends CrObject
{
    public $db;

    public $session_var = 'cr_store'; #$SESSION_VAR;

    public $session_topic = 'DEPARTMENT';

    // Constructor

    public function __construct($id = null)
    {
        $this->session_var = &$_SESSION[$this->session_var];

        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->initVar('dept_id', XOBJ_DTYPE_INT, null, false);

        $this->initVar('dept_name', XOBJ_DTYPE_TXTBOX, null, true);

        if (!empty($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        }
    }

    // Public

    public function load($dept_id)
    {
        $table = $this->db->prefix('cr_depts');

        $sql = "SELECT * FROM $table WHERE dept_id = " . $dept_id;

        $myrow = $this->db->fetchArray($this->db->query($sql));

        $this->assignVars($myrow);
    }

    // Public

    public function store()
    {
        if (!$this->cleanVars()) {
            return false;
        }

        foreach ($this->cleanVars as $k => $v) {
            $$k = $v;
        }

        $id = $this->getVar('dept_id');

        if (empty($id)) {
            $sql = 'INSERT INTO ' . $this->db->prefix('cr_depts') . " (dept_name) VALUES ('" . $this->getVar('dept_name') . "')";
        } else {
            $sql = 'UPDATE ' . $this->db->prefix('cr_depts') . " SET dept_name='" . $this->getVar('dept_name') . "' WHERE dept_id=" . $id;
        }

        if (!$result = $this->db->query($sql)) {
            echo $this->db->error();

            $this->setErrors(_CR_CLDNTSTORE);

            return false;
        }

        echo $this->db->error();

        return $id;
    }

    // Public

    public function delete()
    {
        // Delete cascade

        $sql = 'DELETE FROM ' . $this->db->prefix('cr_depts') . ' WHERE dept_id=' . $this->getVar('dept_id');

        $this->db->query($sql);
    }

    // Public static

    public function &getAll()
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $ret = [];

        $sql = sprintf('SELECT * FROM %s ', $db->prefix('cr_depts'));

        $result = $db->query($sql);

        while (false !== ($myrow = $db->fetchArray($result))) {
            $ret[] = $myrow;
        }

        return $ret;
    }

    public function getByName($name)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $ret = [];

        $sql = sprintf("SELECT * FROM %s where dept_name='$name'", $db->prefix('cr_depts'));

        $result = $db->query($sql);

        echo $db->error();

        if (!($db->getRowsNum($result) > 0)) {
            return false;
        }

        $myrow = $db->fetchArray($result);

        return $myrow;
    }
}
