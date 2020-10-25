<?php

// $Id: addform_c.inc.php,v 1.1 2006/03/27 13:59:20 mikhail Exp $
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
include 'class/department.inc.php';
include 'class/course.inc.php';
$depart_s = new Department();
$depart_s->loadFromSession();
$course_s = new Course();
$course_s->loadFromSession();
?>
<!-- add_review_form:start-->
<br>
<fieldset>
<legend class="blockTitle"><?php echo _CR_ADDCRVW; ?></legend>
<div class="blockContent">
<form action="handleadd.php" method="POST">
  <table class="outer" style="border: 0px">
    <tr>
      <td class="head" width="30%"><?php echo _CR_TERM; ?></td>
      <td class="even" width="70%">
        <select name="term">
<?php

       printf("<option value='fall' %s >" . _CR_FALL . '</option>', ('fall' == $course_s->getVar('term') ? 'selected' : ''));
       printf("<option value='spring'  %s >" . _CR_SPRING . '</option>', ('spring' == $course_s->getVar('term') ? 'selected' : ''));
?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="head" width="30%"><?php echo _CR_YEAR; ?></td>
      <td class="even" width="70%"><input type="text" name="year" value=<?php echo $course_s->getVar('year')?> ></td>
    </tr>
    <tr>
      <td class="head" width="30%"><?php echo _CR_DEPARTMENT; ?></td>
      <td class="even" width="70%">
<?php
  $rows = $depart_s->getAll();
  echo "<select name=\"dep_id\">\n";
  foreach ($rows as $row) {
      echo "<option value=$row[dept_id] " . ($depart_s->getVar('dept_id') == $row['dept_id'] ? 'selected' : '') . '>' . $row['dept_name'] . "</option>\n";
  }
  echo "</select>\n";
?>
      </td>
    </tr>
    <tr>
      <td class="head" width="30%"><?php echo _CR_CRNUMBER; ?></td>
      <td class="even" width="70%"><input type="text" name="courseNum" value=<?php echo $course_s->getVar('num'); ?> ></td>
    </tr>
    <tr>
      <td class="head" width="30%"><?php echo _CR_NMBOFUNTS; ?></td>
      <td class="even" width="70%"><input type="text" name="units" value=<?php echo $course_s->getVar('units'); ?> ></td>
    </tr>
    <tr>
      <td class="head" width="30%"></td>
      <td class="even" width="70%"><input type="submit" value="<?php echo _CONTINUE; ?>"</td>
    </tr>
  </table>
</form>
</div></fieldset>
<!-- add_review_form:end-->  
