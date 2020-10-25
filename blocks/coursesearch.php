<?php

function cr_show_coursesearch($options)
{
    global $xoopsDB;

    $block = [];

    $content = '<form action="' . XOOPS_URL . '/modules/courseReview/search.php" method="post">';

    $content .= '  <table class="outer">';

    $content .= '    <tr>';

    $content .= '      <td class="head" 	width="30%">DEPARTMENT</td>';

    $content .= '      <td class="even" width="70%" align="left">';

    $table = $xoopsDB->prefix('cr_depts');

    $q = 'SELECT dept_name FROM ' . $table . ' ORDER BY ' . $table . '.dept_name';

    $result = $xoopsDB->query($q);

    $content .= "<select name=\"deptName\">\n";

    $content .= "<option value=\"\">CHOOSE A DEPT</option>\n";

    while ($row = $xoopsDB->fetchArray($result)) {
        $content .= '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . "</option>\n";
    }

    $content .= "</select>\n";

    $content .= '      </td>';

    $content .= '    </tr>';

    $content .= '    <tr>';

    $content .= '      <td class="head" width="30%">COURSE NUMBER</td>';

    $content .= '      <td class="even" width="70%"><input type="text" name="courseNum" align="left"></td>';

    $content .= '    </tr>';

    $content .= '    <tr>';

    $content .= '      <td class="head" width="30%">UNITS</td>';

    $content .= '      <td class="even" width="70%"><input type="text" name="units" align="left"></td>';

    $content .= '    </tr>';

    $content .= '    <tr>';

    $content .= '      <td class="head" width="30%">TERM</td>';

    $content .= '      <td class="even" width="70%" align="left">';

    $content .= '        <select name="term">';

    $content .= '          <option value="fall">Fall</option>';

    $content .= '          <option value="spring">Spring</option>';

    $content .= '        </select>';

    $content .= '    </tr>';

    $content .= '    <tr>';

    $content .= '      <td class="head" width="30%">Year</td>';

    $content .= '      <td class="even" width="70%"><input type="text" name="year" align="left"></td>';

    $content .= '    </tr>';

    $content .= '    <tr>';

    $content .= '      <td class="head" width="30%"></td>';

    $content .= '      <td class="even" width="70%" align="left">';

    $content .= '        <input type="hidden" name="filled" value="yes">';

    $content .= '        <input type="hidden" name="searchType" value="course">';

    $content .= '        <input type="submit" value="SEARCH">';

    $content .= '      </td>';

    $content .= '    </tr>';

    $content .= '  </table>';

    $content .= '</form>';

    $block['content'] = $content;

    return $block;
}
