<?php

require dirname(__DIR__, 3) . '/include/cp_header.php';

  xoops_cp_header();

/*echo "<div align=center>";


echo "<a href='index.php'>Index</a>::";


echo "<a href='edit_dept.php'>Edit Departments</a>::";


echo "<a href='featured.php'>Featured Reviews</a>::";


echo "<a href='moderate.php'>Approve/Deny Reviews</a>::";


echo "<a href='permissions.php'>Permissions</a>::";


echo "<a href='professors.php'>Professors</a>::";


echo "<a href=''>Preferences</a>";


echo "</div><HR><br><br>";


*/

function adminmenu($currentoption, $breadcrumb)
{
    global $xoopsModule, $xoopsConfig;

    $tblColors = [];

    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = '#DDE';

    $tblColors[$currentoption] = 'white';

    echo "<table width=100% class'outer'><tr><td align=right>


          <font size=2>" . _MD_A_MODULE_ADM . ':' . $xoopsModule->name() . ':' . $breadcrumb . '</font>


          </td></tr></table><br>';

    echo '<div id="navcontainer"><ul style="padding: 3px 0; margin-left:


         0;font: bold 12px Verdana, sans-serif; ">';

    echo '<li style="list-style: none; margin: 0; display: inline; ">


         <a href="index.php" style="padding: 3px 0.5em;


         margin-left: 3px;


         border: 1px solid #778; background: ' . $tblColors[0] . ';


         text-decoration: none; ">' . _MD_A_INDEX . '</a></li>';

    echo '<li style="list-style: none; margin: 0; display: inline; ">


         <a href="edit_dept.php" style="padding: 3px 0.5em;


         margin-left: 3px;


         border: 1px solid #778; background: ' . $tblColors[1] . ';


         text-decoration: none; ">' . _MD_A_DEPARMENT . '</a></li>';

    /*	    echo "<li style=\"list-style: none; margin: 0; display: inline; \">


             <a href=\"courses.php\" style=\"padding: 3px 0.5em;


             margin-left: 3px;


             border: 1px solid #778; background: ".$tblColors[2].";


             text-decoration: none; \">"._MD_A_EDIT_COURSES."</a></li>";
    */

    echo '<li style="list-style: none; margin: 0; display: inline; ">


         <a href="featured.php" style="padding: 3px 0.5em;


         margin-left: 3px;


         border: 1px solid #778; background: ' . $tblColors[2] . ';


         text-decoration: none; ">' . _MD_A_FEATURED_RVS . '</a></li>';

    echo '<div id="navcontainer"><ul style="padding: 3px 0; margin-left:


         0; font: bold 12px Verdana, sans-serif; ">';

    echo '<li style="list-style: none; margin: 0; display: inline; ">


          <a href="permissions.php" style="padding: 3px 0.5em;


          margin-left:3px;


          border: 1px solid #778; background: ' . $tblColors[4] . ';


          text-decoration: none; ">' . _MD_A_PERMISSIONS . '</a></li>';

    echo '<li style="list-style: none; margin: 0; display: inline; ">


         <a href="professors.php" style="padding: 3px 0.5em; margin-left: 3px;


         border: 1px solid #778;


         background: ' . $tblColors[5] . ';


         text-decoration: none; ">' . _MD_A_PROFESSORS . '</a></li>';

    echo '<li style="list-style: none; margin: 0; display: inline; ">


         <a href="' . XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid') . '" style="padding: 3px 0.5em; margin-left: 3px;


         border: 1px solid #778;


         background: ' . $tblColors[6] . ';


         text-decoration: none; ">' . _MD_A_PREFERENCES . '</a></li>';

    echo '</div></ul>';

    echo '<br><br>';
}




