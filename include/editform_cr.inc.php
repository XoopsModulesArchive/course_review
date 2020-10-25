<form action="?op=do_modify&rid=<?php echo $rid; ?>" method="POST" ENCTYPE='multipart/form-data'>



  <table class="outer">



    <tr>



      <th colspan="2">Edit a Course Review</th>



    </tr>



    <tr>



      <td class="head">Message Icons</td>



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



       <td class="head"><b>Course Num</b><font color="red">*</font></td>



       <td class="even">



          <input type=text name=num value='<?php echo $course_s->getVar('num')?>'>



       </td>



    </tr>







    <tr>



       <td class="head"><b>Review the Course</b><font color="red">*</font></td>



       <td class="even">



          <textarea rows="5" cols="60" name="course_text"><?php echo $review_s->getVar('course_review') ?></textarea>



       </td>



    </tr>



    <tr>



       <td class="head"><b>Review the Professor</b><font color="red">*</font></td>



       <td class="even">



          <textarea rows="5" cols="60" name="prof_text"><?php echo $review_s->getVar('prof_review') ?></textarea>



       </td>



    </tr>











    <tr>



      <td class="head">Course Ranking<font color="red">*</font></td>



      <td class="even">



	<table>



	  <tr>



            <td width="30%">Difficulty:</td>



            <td width="70%">



              <select name="diff">



                <?php $checked_dropdown_value = $review_s->getVar('difficulty'); require __DIR__ . '/starDropDown.php'; ?>



              </select>



           </td>



	  </tr>



	  <tr>



	    <td width="30%">Usefulness:</td>



	    <td width="70%">



	      <select name="useful">



	        <?php $checked_dropdown_value = $review_s->getVar('usefulness'); require __DIR__ . '/starDropDown.php'; ?>



	      </select>



	    </td>



	  </tr>



	  <tr>



	    <td width="30%">Effort:</td>



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



      <td class="head">Professor Ranking<font color="red">*</font></td>



      <td class="even">



	<table>



	  <tr>



	    <td width="30%">Effectiveness:</td>



	    <td width="70%">



	      <select name="effect">



		<?php $checked_dropdown_value = $review_s->getVar('prof_effect'); require __DIR__ . '/starDropDown.php'; ?>



	      </select>



	    </td>



	  </tr>



	  <tr>



	    <td width="30%">Fairness:</td>



	    <td width="70%">



	      <select name="fair">



		<?php  $checked_dropdown_value = $review_s->getVar('prof_fair'); require __DIR__ . '/starDropDown.php'; ?>



	      </select>



	    </td>



	  </tr>



	  <tr>



	    <td width="30%">Availability:</td>



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



      <td class="head">Overall Ranking<font color="red">*</font></td>



      <td class="even">



	<table>



	  <tr>



	    <td width="30%">Overall:</td>



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







        <input type="submit" value="Edit">



      </td>



    </tr>



  </table>



</form>

