<?php

// $Id: professor_form.inc.php,v 1.1 2006/03/27 13:59:20 mikhail Exp $
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

    $action = 'professors.php';
if (is_object($prof_s)) {
    $prof_input['prof_id'] = $prof_s->getVar('prof_id');

    $prof_input['lname'] = $prof_s->getVar('lname');

    $prof_input['fname'] = $prof_s->getVar('fname');

    $prof_input['dept_id'] = $prof_s->getVar('dept_id');

    $prof_input['image_url'] = $prof_s->getVar('image_url');

    $prof_input['pteaser'] = $prof_s->getVar('pteaser');

    $prof_input['preview'] = $prof_s->getVar('preview');

    $ext_action = "?op=pedit&pid=${prof_input['prof_id']}";

    $cr_title = _CR_PROF_MODIFY;
} else {
    $cr_title = _CR_PROF_ADD;

    $ext_action = '?op=padd';
}

 $form = new XoopsThemeForm($cr_title, 'pform', $action . $ext_action);
 $form->setExtra("ENCTYPE='multipart/form-data'");
 $fname_el = new XoopsFormText(_CR_PROF_FNAME, 'fname', 30, 50, $prof_input['fname']);
 $form->addElement($fname_el);
 $lname_el = new XoopsFormText(_CR_PROF_LNAME, 'lname', 30, 50, $prof_input['lname']);
 $form->addElement($lname_el);
# 	function XoopsFormElementTray($caption, $delimeter="&nbsp;"){

 $filetray_el = new XoopsFormElementTray(_CR_PROF_PHOTO, '<br>');
  foreach ($xoopsModuleConfig['img_mimetypes'] as $mimeitem) {
      $description .= $mimeitem . '<br>';
  }
 $filetray_el->setDescription($description);

 $file_el = new XoopsFormFile('', 'picture', $xoopsModuleConfig['maxfilesize']);
 $filetray_el->addElement($file_el);
# 	function XoopsFormLabel($caption="", $value=""){
if ($prof_input['image_url']) {
    $filetray_el->addElement(new XoopsFormLabel('', "<img src='../image_show.php?image_url=${prof_input['image_url']}'>"));

    $temp_el = new XoopsFormCheckBox('', 'delete_img', '0');

    $temp_el->addOption('1', ' ' . _DELETE);

    $filetray_el->addElement($temp_el);

    # $filetray_el->addElement(new XoopsFormCheckBox(_DELETE,'delete_img'));
}
 $form->addElement($filetray_el);
 $depart_sel = new XoopsFormSelect(_CR_DEPARMENT, 'dept_id', $prof_input['dept_id']);

    $deps = Department::getAll();
    foreach ($deps as $k => $v) {
        $depart_sel->addOption($v['dept_id'], $v['dept_name']);
    }
$form->addElement($depart_sel);
$pteaser_el = new XoopsFormDhtmlTextArea(_CR_PTEASER, 'pteaser', $prof_input['pteaser'], 5, 50, 'pteaser_hidden');
$form->addElement($pteaser_el);
$preview_el = new XoopsFormDhtmlTextArea(_CR_PREVIEW, 'preview', $prof_input['preview'], 5, 50, 'preview_hidden');
$preview_el->setDescription(_CR_USE_PAGEBREAK);
$form->addElement($preview_el);

 $form->addElement(new XoopsFormButton('', '', _SUBMIT, $type = 'submit'));
 echo $form->render();
