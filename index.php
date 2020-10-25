<?php

require dirname(__DIR__, 2) . '/mainfile.php';

  require XOOPS_ROOT_PATH . '/header.php';

  require __DIR__ . '/include/functions.inc.php';

  #echo print_r(get_approved_members());

?>

<h1><?php echo _CR_HOME; ?></h1>



<table>

  <tr>

    <td>

<?php

echo _MD_BROWSEBY . ':' . "<a href='search.php?searchType=prof&filled=1'>" . _CR_PROF . '</a>' . '|' . "<a href='search.php?filled=1'>" . _MD_COURSE . '</a>';
echo '<br>';
include 'include/addform_c.inc.php';

echo '<br>';

include 'include/searchform_c.inc.php';

echo '<br>';

include 'include/searchform_prof.inc.php';

?>







    </td>

  </tr>

</table>



<?php

  require_once XOOPS_ROOT_PATH . '/footer.php';

?>

