<?php

// $Id: course_form.inc.php,v 1.1 2006/03/27 13:59:20 mikhail Exp $

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

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
#require_once XOOPS_ROOT_PATH.'/class/xoopsform/tableform.php';

    $action = 'edit_dept.php';
if (is_object($course_s)) {
    $course_input['course_id'] = $course_s->getVar('course_id');

    $course_input['num'] = $course_s->getVar('num');

    $course_input['name'] = $course_s->getVar('name');

    $course_input['dept_id'] = $course_s->getVar('dept_id');

    $course_input['term'] = $course_s->getVar('term');

    $course_input['year'] = $course_s->getVar('year');

    $course_input['units'] = $course_s->getVar('units');

    $course_input['cteaser'] = $course_s->getVar('cteaser');

    $course_input['creview'] = $course_s->getVar('creview');

    $ext_action = "?op=cedit&cid=${course_input['course_id']}";

    $cr_title = _CR_COURSE_MODIFY;
} else {
    $cr_title = _CR_COURSE_ADD;

    $ext_action = '?op=cadd';

    if ($_GET['dept_id']) {
        $course_input['dept_id'] = $_GET['dept_id'];
    }
}

$form = new XoopsThemeForm('<b>' . $cr_title . '</b>', 'course_form', $action . $ext_action);
$depart_sel = new XoopsFormSelect(_CR_DEPARMENT, 'dept_id', $course_input['dept_id']);

    $deps = Department::getAll();
    foreach ($deps as $k => $v) {
        $depart_sel->addOption($v['dept_id'], $v['dept_name']);
    }
$form->addElement($depart_sel);
#function XoopsFormText($caption, $name, $size, $maxlength, $value="")
$cnum_el = new XoopsFormText(_CR_COURSENUM, 'cnum', 30, 50, $course_input['num']);
$form->addElement($cnum_el);
$cname_el = new XoopsFormText(_CR_NAME, 'cname', 50, 255, $course_input['name']);
$form->addElement($cname_el);
$cterm_el = new XoopsFormSelect(_CR_TERM, 'term', $course_input['term']);
$cterm_el->addOptionArray(['fall' => 'FALL', 'sprint' => 'SPRINT']);

$form->addElement($cterm_el);
$cyear_el = new XoopsFormText(_CR_YEAR, 'year', 30, 50, $course_input['year']);
$form->addElement($cyear_el);
$cunits_el = new XoopsFormText(_CR_UNITS, 'units', 30, 50, $course_input['units']);
$form->addElement($cunits_el);
#XoopsFormDhtmlTextArea($caption, $name, $value, $rows=5, $cols=50, $hiddentext="xoopsHiddenText")
$cteaser_el = new XoopsFormDhtmlTextArea(_CR_TEASER, 'cteaser', $course_input['cteaser'], 5, 50, 'cteaser_hidden');
$form->addElement($cteaser_el);
$creview_el = new XoopsFormDhtmlTextArea(_CR_REVIEW, 'creview', $course_input['creview'], 5, 50, 'creview_hidden');
$creview_el->setDescription(_CR_USE_PAGEBREAK);
$form->addElement($creview_el);
$button = new XoopsFormButton('', '', _SUBMIT, $type = 'submit');
$form->addElement($button);
echo $form->render();
