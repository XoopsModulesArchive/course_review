<?php

require __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once dirname(__DIR__) . '/class/professor.inc.php';

adminmenu(5, _MD_A_PROFESSORS);

switch ($op) {
    case 'upload':
  require_once XOOPS_ROOT_PATH . '/class/uploader.php';
  $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];
  $maxfilesize = $xoopsModuleConfig['maxfilesize'];

   print_r($_FILES);

        if ($_FILES['picture']['name']) {
            $allowed_mimetypes = $xoopsModuleConfig['img_mimetypes'];

            $maxfilesize = $xoopsModuleConfig['img_maxfilesize'];

            $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'], $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

            $uploader->setPrefix('img');

            if ($uploader->fetchMedia('picture')) {
                if (!$uploader->upload()) {
                    #  echo $uploader->getErrors();
                } else {
                    $prof_s = new Professor(3);

                    $prof_s->setVar('image_url', $uploader->getSavedFileName());

                    $prof_s->store();
                }
            }

            #  echo $uploader->getErrors();

            if (count($uploader->errors) > 0) {
                redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/photos.php', 3, $uploader->getErrors());

                exit();
            }
        }

        break;
    case 'accept':

    $image = $_POST[im_url];
    $prof_id = (int)$_GET[prof_id];

    $quer = sprintf(
        "update %s set image_url='$image' where prof_id=$prof_id",
        $xoopsDB->prefix(cr_profs)
    );

    $xoopsDB->query($quer);

    redirect_header('professors.php', 2, "Professor's photo has been linked");

        break;
    default:

        $prof_id = (int)$_GET[pid];

$quer = sprintf(
    "select rv.image_url , us.uname from %s rv, %s us  where rv.prof_id=$prof_id and rv.image_url!='' and us.uid=rv.rev_uid",
    $xoopsDB->prefix(cr_reviews),
    $xoopsDB->prefix(users)
);

echo $quer;
$res = $xoopsDB->query($quer);

echo "<form action='photos.php?op=accept&prof_id=$prof_id' method=post>";

echo '<table>';

while (false !== ($review = $xoopsDB->fetchArray($res))) {
    echo sprintf("<tr><td><img src='%s'><br> " . _MD_A_UPLOAD_BY . " <a href='%s/modules/ipboard/index.php?showuser'>$review[uname]</a></td>

<td><input type=radio name=im_url value='$review[image_url]'> </td></tr>", "../image_show.php?image_url=$review[image_url]", XOOPS_URL);

    #echo "../image_show.php?image_url=${review['image_url']}";
}
echo "<tr><td></td><td><input type=submit value='" . _MD_A_ASSIGN . "'></td></tr>";
echo '</table>';
echo '</form>';

$form = new XoopsThemeForm(_MD_IM_PICTURE, 'pform', 'photos.php?op=upload');
$form->setExtra("ENCTYPE='multipart/form-data'");
$file_el = new XoopsFormFile('', 'picture', $xoopsModuleConfig['maxfilesize']);

foreach ($xoopsModuleConfig['img_mimetypes'] as $mimeitem) {
    $description .= $mimeitem . '<br>';
}
$file_el->setDescription($description);
$form->addElement($file_el);

$form->addElement(new XoopsFormButton('', '', _SUBMIT, $type = 'submit'));
echo $form->render();

xoops_cp_footer();

        break;
}




