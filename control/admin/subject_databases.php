<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\SubjectDatabase;
    
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Bases de datos por tema";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");
require_once("../includes/config.php");
require_once("../includes/functions.php");

$db = new Querier;
$objDatabases = new SubjectDatabase($db);

$subs_option_boxes = $objDatabases->getSubjectsDropDownItems();


$all_subjects = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"subjects\">
<option id='place_holder'>" . _("      -- Elija el asunto --     ") . "</option>
$subs_option_boxes
</select>
</form>";


$guide_collection_list =  "<div id='guide-collection-list-container'>";
$guide_collection_list .= "<ul id='guide-collection-list'></ul>";
$guide_collection_list .= "</div>";



$database_search_viewport = "<div id='search-results-container'>";
$database_search_viewport .= "<label for='add-database-input'>Buscar</label> ";
$database_search_viewport .= "<input id='add-database-input' type='text' name='add-database-input' />";
$database_search_viewport .= "<div><h4>Resultados de la búsqueda</h4><ul id='database-search-results'></ul></div>";
$database_search_viewport .= "</div>";

$associated_databases_viewport = "<div id='database-list-container'>";
$associated_databases_viewport .= "<h4 id='database-label'></h4>";
$associated_databases_viewport .= "<ul id='database-list'></ul>";
$associated_databases_viewport .= "<button id='update-databases-btn' class='pure-button pure-button-primary' style=\"display: none;\">Guardar Cambios</button>";
$associated_databases_viewport .= "</div>";

$about_tb_body = "<p>" . _("Puede utilizar esta página para establecer las bases de datos que deben asociarse con cada tema. Esto aparecerá en la página public subjects / databases.php cuando haga clic en la lista desplegable Bases de datos por asunto (si su sitio tiene uno). ") . "</p>
    <p><a href=\"" . $PublicPath . "/databases.php?letter=bysub\">" . _("Bases de datos por tema") . "</a></p>
    <h3>" . _("Uso") . "</h3>
    <ul>
    <li>" . _("Selecciona tu tema") . "</li>
    <li>" . _("Buscar un registro almacenado en la lista A-Z de registros") . "</li>
    <li>" . _("Agregue ese registro a su lista") . "</li>
    <li>" . _("Guardar y disfrutar") . "</li>
    </ul>
    <br />"

;

?>
<style>
    .error-dialog {
        display:none;
    }
</style>



<div class="pure-g">

    <div class="pure-u-1-3">
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _("Seleccione Tema"); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="pluslet_body">
                <div class="all-subjects-dropdown dropdown_list"><?php print $all_subjects; ?></div>
            </div>
        </div>
        <?php echo makePluslet(_("Bases de datos asociadas con el tema"), $associated_databases_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">        
        <?php echo makePluslet(_("Bases de datos (Limitado a la lista A-Z DB)"), $database_search_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Acerca de las bases de datos por tema"), $about_tb_body, "no_overflow"); ?>
    </div>

</div>
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>

<script>
    var sds = subjectDatabaseService();
    sds.init();
    $(document).ready(function() {
        $('#subjects').select2();

    });
</script>
