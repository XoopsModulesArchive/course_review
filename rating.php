<?php

require dirname(__DIR__, 2) . '/mainfile.php';

require XOOPS_ROOT_PATH . '/header.php';

// set a cookie so user won't be able to rate this again

setcookie('cr_' . $xoopsUser->uname('S') . '_rated_' . $_POST['rev_id'], 'true', time() + 60 * 60 * 24 * 365);

$rev_id = $_POST['rev_id'];

$useful = ('yes' == $_POST['useful']) ? 1 : 0;

$table = $xoopsDB->prefix('cr_ratings');

$q = "SELECT * FROM $table WHERE rev_id=$rev_id";

$result = $xoopsDB->query($q);

if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) { // don't have any votes yet
    $q = "INSERT INTO $table (rev_id, num_useful, total) VALUES($rev_id,";

    if ($useful) {
        $q .= ' 1, 1);';
    } else {
        $q .= ' 0, 1);';
    }

    echo "<br>$q<br>";

    $xoopsDB->query($q);

    echo 'Thanks for rating!'; // user won't even see this line since it's so fast!

    header("Location: showreview.php?rev_id=$rev_id");
} else {  // already in db, just update the values
    $row = $xoopsDB->fetchArray($result);

    $num_useful = $row['num_useful'];

    $total = $row['total'] + 1;

    $q = "UPDATE $table SET num_useful=";

    if ($useful) {
        $q .= ($num_useful + 1);
    } else {
        $q .= $num_useful;
    }

    $q .= ", total=$total WHERE rev_id=$rev_id;";

    $xoopsDB->query($q);

    echo 'Thanks for rating!';

    header("Location: showreview.php?rev_id=$rev_id");
}

?>





<?php

  require_once XOOPS_ROOT_PATH . '/footer.php';

?>





