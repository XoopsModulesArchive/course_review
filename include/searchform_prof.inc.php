<!-- prof_search_form:start-->

<br>

<fieldset>

<legend class="blockTitle"><?php echo _CR_SRCHPROF; ?></legend>

<div class="blockContent">

<br>
	<form action="<?=XOOPS_URL?>/modules/<?php echo $xoopsModule->dirname(); ?>/search.php" method="post">

	  <table class="outer" style="border: 0px">

	    <tr>

	      <td class="head" 	width="30%"><?php echo _CR_DEPARTMENT; ?></td>

	      <td class="even" width="70%" align="left">

<?php

$table = $xoopsDB->prefix('cr_depts');

    $q = 'SELECT dept_name FROM ' . $table . ' ORDER BY ' . $table . '.dept_name';

    $result = $xoopsDB->query($q);

    echo "<select name=\"deptName\">\n";

    echo '<option value="">' . _CR_CHSDPT . "</option>\n";

    while ($row = $xoopsDB->fetchArray($result)) {
        echo '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . "</option>\n";
    }

?>

	</select>



	      </td>

	    </tr>

	    <tr>

	      <td class="head" width="30%"><?php echo _CR_LASTNAME; ?></td>

	      <td class="even" width="70%"><input type="text" name="pLname" align="left"></td>

	    </tr>

	    <tr>

	      <td class="head" width="30%"><?php echo _CR_FIRSTNAME; ?></td>

	      <td class="even" width="70%"><input type="text" name="pFname" align="left"></td>

	    </tr>

	    <tr>

	      <td class="head" width="30%"></td>

	      <td class="even" width="70%" align="left">

	        <input type="hidden" name="filled" value="yes">

	        <input type="hidden" name="searchType" value="prof">

	        <input type="submit" value="<?php echo _SEARCH; ?>">

	      </td>

	    </tr>

	  </table>

	</form>

</div></fieldset>

<!-- prof_search_form:end -->

