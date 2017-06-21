<?php

/**
 *   @file index.php
 *   @brief Splash page for subject guide creation
 *
 *   @author adarby
 *   @date mar 2011
 */


use SubjectsPlus\Control\Querier;


$subcat = "guides";
$page_title = "Modificar guías";

include("../includes/config.php");
include("../includes/header.php");

$db = new Querier;


$gear_alt = _("Editar los metadatos de la guía");
$eye_alt = _("Ver guía en el sitio público");
$linkie_alt = _("Revisar los enlaces de la guía");
$view_alt = _("Ver guía en el sitio público");
    
try {
  } catch (Exception $e) {
  echo $e;
}

$subs_option_boxes = getSubBoxes("guide.php?subject_id=", "", 1);

$dropdown_intro_text = _("Consulte con el propietario de la guía antes de modificarlo.");

$all_guides = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"guides\" size=\"1\" onChange=\"window.location=this.options[selectedIndex].value\">
<option value=\"\">" . _("-- Elegir Guía --") . "</option>
$subs_option_boxes
</select>
</form>";

// Get all subjects associated with the person

$my_subs_query = "SELECT subject.subject_id, subject, subject.active, shortform, type
FROM `subject`, staff_subject, staff
WHERE staff.staff_id = staff_subject.staff_id
AND staff_subject.subject_id = subject.subject_id
AND staff.staff_id = '$_SESSION[staff_id]'
ORDER BY subject";

$my_subs_result = $db->query($my_subs_query);
$num_rows = count($my_subs_result);

if ($num_rows > 0) {

  $myguides = "";
  $row_count = 0;
  $colour1 = "#fff";
  $colour2 = "#F6E3E7";
  $colour3 = "highlight";

  foreach ($my_subs_result as $myrow1) {
    $mysubs_id = $myrow1[0];
    $mysubs_name = stripslashes($myrow1[1]);
    $active = $myrow1[2];
    $active = $myrow1[2];

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;


    $myguides .= "<div style=\"background-color:$row_colour ; padding: 2px;\" class=\"striper\"> &nbsp;&nbsp;
        <a class=\"showmedium-reloader\" href=\"../guides/metadata.php?subject_id=$mysubs_id&amp;wintype=pop\"><i class=\"fa fa-gear fa-lg\" alt=\"$gear_alt\" title=\"$gear_alt\"></i></a> &nbsp;&nbsp;
        <a target=\"_blank\" href=\"../../subjects/guide.php?subject=$myrow1[3]\"><i class=\"fa fa-eye fa-lg\" alt=\"$view_alt\" title=\"$view_alt\"></i></a> &nbsp;&nbsp;
        <a class=\"showmedium\" href=\"../guides/link_checker.php?subject_id=$mysubs_id&amp;wintype=pop\"><i class=\"fa fa-link fa-lg\" alt=\"$linkie_alt\" title=\"$linkie_alt\"></i></a> &nbsp;&nbsp; <a href=\"guide.php?subject_id=$mysubs_id\">$mysubs_name</a>";
    if ($active != "1") {
      $myguides .= " <span style=\"color: #666;\">" . _("unpublished") . "</span>";
    }
    $myguides .= " <span style=\"color: #666; font-size: 10px;\">$myrow1[4]</span> </div>";
    $row_count++;
  }
} else {
  $myguides = "<p>" . _("Aún no tienes guías. ¿Por qué no crear uno?") . "</p>";
}
?>


<script>
$(document).ready(function() {

$('#guides').select2();

});
</script>

<div class="pure-g">
  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Editar guías"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><?php print $myguides; ?></p>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Todas las guías"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><?php print $dropdown_intro_text; ?></p>
        <br />
        <div class="all-guides-dropdown dropdown_list"><?php print $all_guides; ?></div>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">

    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Crear"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <ol>
          <li><?php print _("Asegúrese de que la guía no existe!"); ?></li>
          <li><a href="metadata.php"><?php print _("Crear nueva guía"); ?></a></li>
        </ol>
      </div>
    </div>
<!--
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Import LibGuides"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
       <p>You can use this feature to import LibGuides</p>
       <a href="./lgimporter/lg_importer_user_selection.php"><?php print _("Import LibGuides"); ?></a>
        
      </div>
	  </div>
-->
   <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Tips"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><i class="fa fa-gear fa-lg"  alt="<?php echo $gear_alt; ?>" title="<?php echo $gear_alt; ?>"></i> <?php print _("Editar los metadatos de la guía"); ?> </p>
        <p><i class="fa fa-eye fa-lg"  alt="<?php echo $eye_alt; ?>" title="<?php echo $eye_alt; ?>"></i> <?php print _("Ver guía en el sitio público"); ?></p>
        <p><i class="fa fa-link fa-lg"  alt="<?php echo $linkie_alt; ?>" title="<?php echo $linkie_alt; ?>"></i> <?php print _("Revisar los enlaces de la guía"); ?></p>
        <p><?php echo _("¿Necesita eliminar una guía? Utilice el icono de rueda dentada y utilice el botón Eliminar."); ?></p>
      </div>
    </div>



 
  </div>
</div>



<?php
include("../includes/footer.php");
?>