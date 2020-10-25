<?php

require dirname(__DIR__, 3) . '/mainfile.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';

require __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

adminmenu(4, _MD_A_PERMISSIONS);

                $module_id = $xoopsModule->getVar('mid');

                $item_list = ['1' => 'approved'];

                $title_of_form = _MD_A_PERMISSIONS;

                $perm_name = _MD_A_APPROVED;

                $perm_descr = _MD_A_APPROVE_PERM_DESCR;

                $form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_descr);

                foreach ($item_list as $item_id => $item_name) {
                    $form->addItem($item_id, $item_name);
                }

            $output_str .= $form->render();

  echo $output_str;

  xoops_cp_footer();

  exit();
