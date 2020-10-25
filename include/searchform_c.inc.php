<fieldset>

<legend class="blockTitle"><?php echo _CR_SRCHCRS; ?></legend>

<div class="blockContent">



<form action="search.php" method="post">

  <table class="outer" style="border: 0px">



    <tr>

      <td class="head" width="30%"><?php echo _CR_DEPARTMENT; ?></td>

      <td class="even" width="70%">

<?php

  $table = $xoopsDB->prefix('cr_depts');

  $q = 'SELECT dept_name FROM ' . $table . ' ORDER BY ' . $table . '.dept_name';

  $result = $xoopsDB->query($q);

  echo "<select name=\"deptName\">\n";

  echo '<option value="">' . _CR_CHSDPT . "</option>\n";

  while ($row = $xoopsDB->fetchArray($result)) {
      echo '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . "</option>\n";
  }

  echo "</select>\n";

?>

      </td>

    </tr>

    <tr>

      <td class="head" width="30%"><?php echo _CR_CRNUMBER; ?></td>

      <td class="even" width="70%"><input type="text" name="courseNum"></td>

    </tr>

<!--    <tr>

      <td class="head" width="30%">LAST NAME</td>

      <td class="even" width="70%"><input type="text" name="pLname"></td>

    </tr>

    <tr>

      <td class="head" width="30%">FIRST NAME</td>

      <td class="even" width="70%"><input type="text" name="pFname"></td>

    </tr>

-->	

    <tr>

      <td class="head" width="30%"><?php echo _CR_UNITS; ?></td>

      <td class="even" width="70%"><input type="text" name="units"></td>

    </tr>

    <tr>

      <td class="head" width="30%"><?php echo _CR_TERM; ?></td>

      <td class="even" width="70%">        

	  <select name="term">



       <option value='all' ><?php echo _CR_ALLTERMS; ?></option>

       <option value='fall' ><?php echo _CR_FALL; ?></option>

	 <option value='spring'  ><?php echo _CR_SPRING; ?></option>



        </select></td>

    </tr>

    <tr>

      <td class="head" width="30%"><?php echo _CR_YEAR; s ?></td>

      <td class="even" width="70%"><input type="text" name="year"></td>

    </tr>		

    <tr>

      <td class="head" width="30%"></td>

      <td class="even" width="70%">

        <input type="hidden" name="filled" value="<?php echo _YES; ?>">

        <input type="submit" value="<?php echo _SEARCH; ?>">

      </td>

    </tr>

  </table>

</form>

</div></fieldset>