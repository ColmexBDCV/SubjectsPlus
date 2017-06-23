<?php
/**
*   @file index.php
*   @brief Splash interface to the SubjectsPlus admin folder.
*
*   @author agdarby
*   @date Dec 2012
*   @todo
*/
$subcat = "admin";
$page_title = "Admin ";

include("../includes/header.php");

$recent_activity = seeRecentChanges("", 20);

$users_box = "<ul>
            <li><a href=\"user.php?type=add\">" .  _("Agregar usuario") . "</a></li>
            <li><a href=\"user.php?browse\">" . _("Navegar Usuarios") . "</a></li>
        </ul>";

print "
<div class=\"pure-g\">
  <div class=\"pure-u-1-3\">  
  ";

makePluslet(_("Usuarios"), $users_box, "no_overflow");

$map_box = "<p>" . _("Nota: esto es material potencialmente confidencial.") . "</p>
        <ul>
            <li><a href=\"staff_map.php\">" . _("Ver mapa de direcciones de personal") . "</a></li>
        </ul>";

makepluslet(_("Mapa del personal"), $map_box, "no_overflow");

$guides_box = "<ul>
            <li><a href=\"manage_guides.php\">" . _("Administrar guías") . "</a></li>";

            if ($use_disciplines == TRUE) {
                $guides_box .= '<li><a href="disciplines.php">' .  _("Administrar Disciplinas") . '</a></li>';
            }

        $guides_box .=  "</ul>";

makepluslet( _("Guías"), $guides_box, "no_overflow");

$departments_box = "
<ul>
    <li><a href=\"departments.php\">" . _("Explorar / Agregar nuevo departamento") . "</a></li>
</ul>";
    
makepluslet(_("Departamentos"), $departments_box, "no_overflow");

 $sources_box = "<ul>
            <li><a href=\"sources.php\">" . _("Agregar nuevo tipo de fuente") . "</a></li>
            <li><a href=\"../guides/link_checker.php?type=records\">" . _("Revisar todos los registros") . "</a></li>
        </ul>"; 


makepluslet(_("Tipos de fuentes de registro"), $sources_box, "no_overflow");

$faq_box = "<ul>
            <li><a href=\"faq_collections.php\">" . _("Explorar / Agregar Colecciones de Preguntas frecuentes ") . "</a></li>
        </ul>";

makepluslet( _(" Colecciones de Preguntas frecuentes"), $faq_box, "no_overflow");
        

print "</div>"; // close pure-u-1-3
print "<div class=\"pure-u-2-3\">";

makePluslet(_("Actividad Reciente"), $recent_activity, "no_overflow");

print "</div>"; // close pure-u-2-3
print "</div>"; // close pure

?>


<?php
        include("../includes/footer.php");
?>
