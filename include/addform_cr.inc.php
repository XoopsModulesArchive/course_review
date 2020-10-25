<form action="thankyou.php?op=preview" method="POST" ENCTYPE='multipart/form-data'>

  <table class="outer">

    <tr>

      <th colspan="2"><?php echo _CR_ADDCRVW; ?></th>

    </tr>

    <tr>

      <td class="head"><?php echo _CR_CRTITLE; ?><font color="red">*</font></td>

      <td class="even"><input type="text" name="c_title" size="35" value='<?php echo $course_s->getVar('name'); ?>'></td>

    </tr>

    <tr>

      <td class="head"><?php echo _CR_PROFNAME?><font color="red">*</font> (<?php echo _CR_FIRSTNAME; ?>, <?php echo _CR_LASTNAME; ?>)</td>

      <td class="even">

        <table>

          <tr>

            <td class="even"><input type="text" name="pfname" size="15" value='<?php echo $prof_s->getVar('fname'); ?>'></td>

            <td class="even"><input type="text" name="plname" size="15" value='<?php echo $prof_s->getVar('lname'); ?>'></td>

          </tr>

        </table>

      </td>

    </tr>

<!--    <tr>

       <td class="head">Catchy Title<font color="red">*</font></td>

       <td class="even"><input type="text" name="title" size="35" value='<?php echo $_SESSION[cr][reviews][title] ?>'></td>

    </tr>-->

    <tr>

      <td class="head"><?php echo _MESSAGEICON; ?></td>

      <td class="even">

<?php

    $lists = new XoopsLists();

    $filelist = $lists::getSubjectsList();

    $count = 1;

    while (list($key, $file) = each($filelist)) {
        $checked = '';

        if (($file == $review_s->getVar('icon'))) {
            $checked = ' checked';
        }

        if ('' == $checked && 'icon1.gif' == $file) {
            $checked = ' checked';
        }

        echo "\t<input type='radio' value='$file' name='icon'$checked>&nbsp;\n";

        echo "\t<img src='" . XOOPS_URL . "/images/subject/$file' alt=''>&nbsp;\n";

        if (7 == $count) {
            echo '<br>';

            $count = 0;
        }

        $count++;
    }

?>

      </td>

    </tr>

    <tr>

       <td class="head"><b><?php echo _CR_RVWTHCRS ?></b><font color="red">*</font></td>

       <td class="even">

          <textarea rows="5" cols="60" name="course_text"><?php echo $review_s->getVar('course_review') ?></textarea>

       </td>

    </tr>

    <tr>

       <td class="head"><b><?php echo _CR_RVWTHPROF ?></b><font color="red">*</font></td>

       <td class="even">

          <textarea rows="5" cols="60" name="prof_text"><?php echo $review_s->getVar('prof_review') ?></textarea>

       </td>

    </tr>



    <tr>

      <td class="head" width="20"><?php echo _CR_ATCHSLBS ?></td>

      <td class="even">

  	<table>

          <tr>

            <td><input type="file" size="15" name="syllabus"><br><?php echo $review_s->getVar('syllabus_url'); ?></td>

            <td width="20"></td>

            <td width="20"></td>

            <td></td>

 	  </tr>

	</table>

      </td>

    </tr>

    <tr>

      <td class="head" width="20"><?php echo _CR_ATTCHPICTURE; ?></td>

      <td class="even">

	<table>

 	  <tr>

            <td><input type="file" size="15" name="picture"><br><?php echo $review_s->getVar('image_url'); ?></td>

            <td> </td>

            <td> </td>

            <td> </td>

	  </tr>

	</table>

      </td>

    </tr>



    <tr>

      <td class="head"><?php echo _CR_CRRANKING ?><font color="red">*</font></td>

      <td class="even">

	<table>

	  <tr>

            <td width="30%"><?php echo _CR_DFCLT ?>:</td>

            <td width="70%">

              <select name="diff">

                <?php $checked_dropdown_value = $review_s->getVar('difficulty'); require __DIR__ . '/starDropDown.php'; ?>

              </select>

           </td>

	  </tr>

	  <tr>

	    <td width="30%"><?php echo _CR_USFLNS ?>:</td>

	    <td width="70%">

	      <select name="useful">

	        <?php $checked_dropdown_value = $review_s->getVar('usefulness'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	  <tr>

	    <td width="30%"><?php echo _CR_EFFORT; ?>:</td>

	    <td width="70%">

	      <select name="effort">

	        <?php $checked_dropdown_value = $review_s->getVar('effort'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	</table>

      </td>

    </tr>



    <tr>

      <td class="head"><?php echo _CR_PROFRANKING; ?><font color="red">*</font></td>

      <td class="even">

	<table>

	  <tr>

	    <td width="30%"><?php echo _CR_EFCTVNS; ?>:</td>

	    <td width="70%">

	      <select name="effect">

		<?php $checked_dropdown_value = $review_s->getVar('prof_effect'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	  <tr>

	    <td width="30%"><?php echo _CR_FAIRNESS ?>:</td>

	    <td width="70%">

	      <select name="fair">

		<?php  $checked_dropdown_value = $review_s->getVar('prof_fair'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	  <tr>

	    <td width="30%"><?php echo _CR_AVLBLT; ?>:</td>

	    <td width="70%">

	      <select name="avail">

		<?php $checked_dropdown_value = $review_s->getVar('prof_avail'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	</table>

      </td>

    </tr>



    <tr>

      <td class="head"><?php echo _CR_OVERALLRANKING; ?><font color="red">*</font></td>

      <td class="even">

	<table>

	  <tr>

	    <td width="30%"><?php echo _CR_OVERALL; ?>:</td>

	    <td width="70%">

	      <select name="overall">

		<?php $checked_dropdown_value = $review_s->getVar('overall'); require __DIR__ . '/starDropDown.php'; ?>

	      </select>

	    </td>

	  </tr>

	</table>

      </td>

    </tr>



    <tr>

      <td class="head"></td>

      <td class="even" align="center">



        <input type="submit" value="<?php echo _CONTINUE; ?>"> <input style='width:100px' type='button' value='<?php echo _BACK ?>' onClick=location.href='add.php?back=1'>

      </td>

    </tr>

  </table>

</form>

