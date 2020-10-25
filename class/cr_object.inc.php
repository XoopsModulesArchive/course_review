<?php

// $Id: cr_object.inc.php,v 1.1 2006/03/27 13:59:18 mikhail Exp $

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

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

class CrObject extends XoopsObject
{
    public $db;

    public $session_var;

    public $session_topic = '';

    public function loadFromSession()
    {
        return $this->setVars($this->session_var[$this->session_topic]);
    }

    public function storeToSession()
    {
        foreach ($this->vars as $k => $v) {
            $this->session_var[$this->session_topic][$k] = $v['value'];
        }

        return true;
    }
}
