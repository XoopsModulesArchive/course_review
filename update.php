<?php

// $Id: update.php,v 1.1 2006/03/27 13:59:16 mikhail Exp $

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

  require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';

?>



<?php

  OpenTable();

?>

  <b>Reviewed By:</b><a href="<{$link_loc}>"><{$uname}></a><br>

  <b>From:</b><{$ufrom}>

<?php

  CloseTable();

?>



<?php

  $rev_table = $xoopsDB->prefix('cr_reviews');

  $q = 'SELECT * FROM ' . $rev_table . ' WHERE rev_id = ' . $_GET['rev_id'];

  $result = $xoopsDB->query($q);

  $row = $xoopsDB->fetchArray($result);

  $course_table = $xoopsDB->prefix('cr_courses');

  $q_course = 'SELECT name FROM ' . $course_table . ' WHERE course_id = ' . $row['course_id'];

  $result_course = $xoopsDB->query($q_course);

  $row_course = $xoopsDB->fetchArray($result_course);

  $xoopsTpl->assign('cname', $row_course['name']);

?>

<table>

  <tr>

    <td class="head" width="30%">Course Title</td>

    <td class="even" width="70%"><?= $row_course['name']?></td>

  </tr>

  <tr>

    <td class="head" width="30%">Professor Name</td>

    <td class="even" width="70%">

      <table>

        <tr>

          <td><{$pfname}> <{$plname}></td>

        </tr>

      </table>

    </td>

  </tr>

  <tr>

    <td class="head" width="30%">Review Title</td>

    <td class="even" width="70%"><{$title}></td>

  </tr>

  <tr>

    <td class="head" width="30%">Review</td>

    <td class="even" width="70%"><{$review}></td>

  </tr>

  <tr>

    <td class="head" width="30%">Syllabus</td>

    <td class="even" width="70%"><a href="<{$syllabus_url}>"></td>

  </tr>

  <tr>

    <td class="head" width="30%">Picture</td>

    <td class="even" width="70%"><a href="<{$image_url}>"></td>

  </tr>

  <tr>

    <td class="head" width="30%">Course Ranking</td>

    <td class="even" width="70%">

      <table>

        <tr>

          <td width="30%">Difficulty:</td>

          <td width="70%"><{$difficulty}></td>

        </tr>

        <tr>

          <td width="30%">Usefulness:</td>

          <td width="70%"><{$usefulness}></td>

        </tr>

        <tr>

          <td width="30%">Effort:</td>

          <td width="70%"><{$effort}></td>

        </tr>

      </table>

    </td>

  </tr>



  <tr>

    <td class="head" width="30%">Professor Ranking</td>

    <td class="even" width="70%">

      <table>

        <tr>

          <td width="30%">Effectiveness:</td>

          <td width="70%"><{$prof_effect}></td>

        </tr>

        <tr>

          <td width="30%">Fairness:</td>

          <td width="70%"><{$prof_fair}></td>

        </tr>

        <tr>

          <td width="30%">Availability:</td>

          <td width="70%"><{$prof_avail}></td>

         </tr>

      </table>

    </td>

  </tr>



  <tr>

    <td class="head" width="30%">Overall Ranking</td>

    <td class="even" width="70%">

      <table>

        <tr>

          <td width=30%>Overall:</td>

          <td width="70%"><{$overall}></td>

        </tr>

      </table>

    </td>

  </tr>

</table>



<?php

  require_once XOOPS_ROOT_PATH . '/footer.php';

?>
