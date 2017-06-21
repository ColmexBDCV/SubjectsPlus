<?php
/**
 *   @file index.php
 *   @brief handles RUD (Read, Update, Delete) for FAQ module.
 *
 *   @author adarby
 *   @date march 2011
 */

use SubjectsPlus\Control\Querier;
    
$subcat = "faq";
$page_title = "FAQ Admin";

include("../includes/header.php");

try {
  } catch (Exception $e) {
  echo $e;
}

if (isset($_GET["limit"])) {
  if ($_GET["limit"] == "all") {
  $limit = "";
  } else {
  $limit = "LIMIT 0," . scrubData($_GET["limit"], "int");
  }

} else {
  $limit = "LIMIT 0,10";
}

$querierFAQ = new  Querier();
$qFAQ = "SELECT faq_id, question, answer, keywords
	FROM faq
	ORDER BY faq_id DESC
	$limit";

$faqArray = $querierFAQ->query($qFAQ);

$row_count1 = 0;
$row_count2 = 0;

$colour1 = "evenrow";
$colour2 = "oddrow";

$faq_list = "";

if ($faqArray) {
  foreach ($faqArray as $value) {
    $row_colour1 = ($row_count1 % 2) ? $colour1 : $colour2;

    $short_question = Truncate($value["question"], 200);
    $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));
    $last_revised_line = lastModded("faq", $value[0]);
// Answered FAQs
    $faq_list .= "
            <div class=\"striper faq_wrapper $row_colour1\">
                <div class=\"faq_tools\">
                <a href=\"faq.php?faq_id=$value[0]&amp;wintype=pop\" class=\"showmedium-reloader\"><i class=\"fa fa-pencil fa-lg\" alt=\"" . _("Editar") . "\"></i></a>
                &nbsp; &nbsp;<a href=\"" . $FAQPath . "?faq_id=$value[0]\" target=\"_blank\"><i class=\"fa fa-eye fa-lg\" alt=\"" . _("Ver en vivo") . "\"></i></a>
                </div>
                <div class=\"faq_question\">
                 $short_question <span class=\"faq-short-question\">($last_revised_line)</span>
                </div>
            </div>";

    $row_count1++;
  }
} else {

  $faq_list = "<p>" . _("No hay preguntas frecuentes todavía. ¿Por qué no soñar uno?") . "</p>";
}

$faq_body = "<p><strong>$row_count1 " . _("Preguntas frecuentes visibles");

if (!isset($limit) || $limit != "all") {
  $faq_body .= " (<a href=\"index.php?limit=all\">" . _("Ver Todas") . "</a>)";
}

$faq_body .= "</strong></p>" . $faq_list;

?>
<div class="pure-g">
  <div class="pure-u-2-3">  

<?php makePluslet(_("Ver preguntas frecuentes"), $faq_body, "no_overflow"); ?>

  </div>
  <div class="pure-u-1-3">  
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Crear FAQ"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="topimage"></div>
      <div class="pluslet_body">
        <p><a href="faq.php?faq_id=&amp;wintype=pop" class="showmedium-reloader"><?php print _("CREATE FAQ"); ?></a></p>
      </div>
    </div>
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Acerca de  FAQs"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="topimage"></div>
      <div class="pluslet_body">
        <p><i class="fa fa-pencil fa-lg" alt=" <?php print _("Edit"); ?>"></i> <?php print _("Editar FAQ"); ?></p>
        <p><i class="fa fa-eye fa-lg" alt=" <?php print _("View"); ?>"></i> <?php print _("Ver preguntas frecuentes sobre el sitio público"); ?></p>
        <p><?php print _("A las preguntas frecuentes se les puede asignar un tema o una colección. Las colecciones de preguntas frecuentes son agrupaciones que pueden aparecer en la barra lateral de la página de preguntas frecuentes del público:
         Por ejemplo, el Catálogo de Bibliotecas o el Préstamo Interbibliotecario. Si asigna un tema, puede agregar fácilmente todas las preguntas frecuentes para ese tema a una guía de temas."); ?></p>
        <p><?php print _("Las colecciones de preguntas frecuentes son creadas por el administrador del sitio o por alguien con el privilegio 'admin' en SubjectsPlus."); ?></p>
      </div>
    </div>
  </div>


</div>
<?php
include("../includes/footer.php");
?>


<script type="text/javascript">
  $(document).ready(function(){
    $(".toggle_unanswered").click(function() {
      $("#unanswered .hideme").toggle();
      return false;
    });

    $(".toggle_answered").click(function() {
      $("#answered .hideme").toggle();
      return false;
    });

  });
</script>

