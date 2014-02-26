<?php
/**
 *   @file guide.php
 *   @brief Create and arrange the contents of a research guide
 *   @description This is where the magic happens.  Process like this:
 *   1. Determine if it's a cloned guide or not --REMOVED THIS
 *   2. Load guide's metadata (query $q)
 *   3. Load guide's pluslets and arrange intro appropriate column (query $qc)
 *   4. Pull in local css (guide.css) and javascript (guide.js)
 *   5. Put together page
 *
 *   @author adarby
 *   @date Dec 2012
 *   @todo Help popups not pointing to correct files
 *   @todo Edit history not present
 *   @todo Make sure user is allowed to modify this guide (NOFUN not set)
 */
use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\Guide;


if (!isset($_GET["subject_id"])) {
    header("location:index.php");
}

// necessary for jquery slider
$use_jquery = array("ui_styles");

// clear out existing cookies


setcookie("our_guide", "", 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_guide_id", "", 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_shortform", "", 0, '/', $_SERVER['HTTP_HOST']);

$subcat = "guides";
$page_title = "Modify Guide";
$tertiary_nav = "yes";

ob_start();

include("../includes/header.php");

try {$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

$postvar_subject_id = scrubData($_GET['subject_id']);

$this_id = $_GET["subject_id"];
$clone = 0;

// See if they have permission to edit this guide
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
    $q = "SELECT staff_id from staff_subject WHERE subject_id = '$this_id'
    AND staff_id = '" . $_SESSION["staff_id"] . "'";

    $r = mysql_query($q);
    $num_rows = mysql_num_rows($r);

    if ($num_rows < 1) {
        $no_permission =  _("You do not have permission to edit this guide.  Ask the guide's creator to add you as a co-editor.");

        print noPermission($no_permission);

        include("../includes/footer.php");
        exit;
    }
}



// See if anything has been added through the Find button

if (isset($_GET["insert_pluslet"])) {
    $qa = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
    FROM pluslet p WHERE p.pluslet_id = '" . $_GET["insert_pluslet"] . "'";
    $ra = mysql_query($qa);

    //$new_clone = mysql_fetch_row($ra);
}

if (isset($this_id)) {
    $subject_id = $_GET["subject_id"];
    // get name of quide
    $q = "SELECT subject, shortform, active, extra from subject where subject_id = '$subject_id'";

    $r = mysql_query($q);

    // If this guide doesn't exist, send them away
    if (mysql_numrows($r) == 0) {
        header("location:index.php");
    }



    $mysub = mysql_fetch_array($r);

    $subject_name = $mysub["0"];
    $shortform = $mysub["1"];

    $jobj = json_decode($mysub["extra"]);

    // In this section, we get the widths of the three columns, which add up to 12
    // We then do a little bit of math to get them into columns that add up to a bit under 100
    // In order to convert to css %.  If this page were bootstrapped, this wouldn't be necessary.
    $col_widths = explode("-", $jobj->{'maincol'});

    if (isset($col_widths[0]) && $col_widths[0] > 0) {
        $left_width = $col_widths[0] * 8;
    } else {
        $left_width = 0;
    }

    if (isset($col_widths[1])) {
        $main_width = $col_widths[1] * 8;
    } else {
        $main_width = 0;
    }

    if (isset($col_widths[2]) && $col_widths[2] > 0) {
        $side_width = ($col_widths[2] * 8) - 3; // we make this a squidgen narrower so it doesn't wrap nastily
    } else {
        $side_width = 0;
    }

    // Is there a selected tab?
    if (isset($_GET["t"]) && $_GET["t"] != "") {
        $selected_tab = scrubData($_GET["t"]);
    } else {
        $selected_tab = 0;
    }

	//create new guide object and set admin view to true
    $lobjGuide = new Guide($this_id);
	$lobjGuide->_isAdmin = TRUE;

    $all_tabs = $lobjGuide->getTabs();

} else {
    print "no guide";
}






////////////////////////////
// Now, get our pluslets //
///////////////////////////
global $pluslets_activated;

$all_boxes = "
<ul id=\"box_options\">
<li class=\"box_note box-item\">" . _("Drag selection, then drop to right") . "</li>";

 foreach( $pluslets_activated as $lstrPluslet )
{
	if( file_exists( dirname(dirname(__DIR__)) . "/lib/SubjectsPlus/Control/Pluslet/$lstrPluslet.php" ) )
	{
		$lstrObj = "SubjectsPlus\Control\Pluslet_" . $lstrPluslet;

		if( method_exists( $lstrObj, 'getMenuName' ) )
		{
			$all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\">" . call_user_func(array( $lstrObj, 'getMenuName' )) . "</li>";
		}else
		{
			$all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\">" . $lstrPluslet . "</li>";
		}
	}
}

// Now get Special ones
// make sure:  a) there are some linked resources (to show All Items by Source)

$conditions = "";

$q1 = "SELECT rank_id FROM rank WHERE subject_id = '$this_id'";

$r1 = mysql_query($q1);

$num_resources = mysql_num_rows($r1);

if ($num_resources == 0) {
    $conditions = "AND pluslet_id != '1'";
}

$q = "SELECT distinct pluslet_id, title, body
FROM pluslet
WHERE type = 'Special'
$conditions
";

$r = mysql_query($q);

while ($myrow = mysql_fetch_array($r)) {
    $all_boxes .= "<li class=\"box-item draggable clone\" id=\"pluslet-id-" . $myrow[0] . "\">\n";
    $all_boxes .= $myrow[1] . "</li>";
}

$all_boxes .= "</ul>";

// END DRAGGABLE //
// print_r($_SESSION);
// Let's set some cookies to be used by ckeditor
setcookie("our_guide", $subject_name, 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_guide_id", $postvar_subject_id, 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_shortform", $shortform, 0, '/', $_SERVER['HTTP_HOST']);
ob_end_flush();

?>





<script type="text/javascript">
    // We're just setting up a few vars that we'll need
    var user_id = "<?php print $_SESSION["staff_id"]; ?>";
    var user_name = "<?php print $_SESSION["fname"] . " " . $_SESSION["lname"]; ?>";
    var subject_id = "<?php print $postvar_subject_id; ?>";
    var cloned_guide = "<?php print $clone; ?>";
    var l_c = "<?php print $left_width; ?>";
    var new_left_width = "<?php print $left_width; ?>%";
    var new_main_width = "<?php print $main_width; ?>%";
    var r_c = "<?php print $side_width; ?>";
    var new_sidebar_width = "<?php print $side_width; ?>%";

    // This will be changed by using the Find button, and selecting a clone to insert
    window.addItem = 0;

    // Hides the global nav on load
    jQuery("#header, #subnavcontainer").hide();



    jQuery(document).ready(function(){
     jQuery("#box_options").hide();
        reLayout(new_left_width, new_main_width, new_sidebar_width);

        function addBoxy(){
            jQuery("#box_options").show();
            return;

        }

        function removeBoxy(){
            jQuery("#box_options").hide();
            return;
        }

        var boxyConfig = {
            interval: 50,
            sensitivity: 4,
            over: addBoxy,
            timeout: 500,
            out: removeBoxy
        };

        jQuery("#newbox").hoverIntent(boxyConfig);

    // config our box for tabs
/*
    function addTabOptionsBox(){
        jQuery("#tabs_options").show();
        return;

    }

    function removeTabOptionsBox(){
        jQuery("#tabs_options").hide();
        //alert ($( "#extra" ).val());
        return;
    }

    var tabsOptionsConfig = {
        interval: 50,
        sensitivity: 4,
        over: addTabOptionsBox,
        timeout: 500,
        out: removeTabOptionsBox
    };

    jQuery("#tabsbox").hoverIntent(tabsOptionsConfig);
    jQuery("#tabsbox input[type='text']").change(function() {
        jQuery("#save_tab").show();
    });

  */

    ///////////////////////////////////
    // config our box for layout slider
    ///////////////////////////////////

    function addSlider(){
        jQuery("#slider_options").show();
        return;

    }

    function removeSlider(){
        jQuery("#slider_options").hide();
        //alert ($( "#extra" ).val());
        return;
    }

    var sliderConfig = {
        interval: 50,
        sensitivity: 4,
        over: addSlider,
        timeout: 500,
        out: removeSlider
    };

    jQuery("#layoutbox").hoverIntent(sliderConfig);

    var ov = '<?php print $jobj->{'maincol'}; ?>';
    var ourval = ov.split("-");
    var lc = parseInt(ourval[0]);
    var cc = parseInt(ourval[1]);
    var rc = lc + cc;

    jQuery( "#slider" ).slider({
      range: true,
      min: 0,
      max: 12,
      step: 1,
      values: [lc, rc],
      slide: function( event, ui ) {
        // figure out our vals
        var left_col = ui.values[0];
        var right_col = 12 - ui.values[1];
        var center_col = 12 - (left_col + right_col);
        var extra_val = left_col + "-" + center_col + "-" + right_col;
        jQuery( "#extra" ).val(extra_val);
        jQuery( "#main_col_width" ).html(left_col + "-" + center_col + "-" + right_col);
        jQuery("#save_layout").show();
        }
    });

    jQuery('button[id=save_layout]').click(function(event) {
        var ourcols = jQuery( "#extra" ).val();
        new_layout = jQuery.ajax({
            url: "helpers/save_layout.php",
            type: "POST",
            data: ({cols: ourcols, subject_id: subject_id}),
            dataType: "html",
            success: function(html) {
                var ourval = jQuery( "#extra" ).val().split("-");

                var lc = parseInt(ourval[0]) * 8;
                lc = lc.toString() + "%"; //convert to string before concating
                var cc = parseInt(ourval[1]) * 8;
                cc = cc.toString() + "%";
                var rc = (parseInt(ourval[2]) * 8 - 3);
                rc = rc.toString() + "%";

                reLayout(lc, cc, rc);
            }
        });
    });


     jQuery("div#tabs ul li a").dblclick(function () {

        alert("doublclickitude!");
     })
});

//setup jQuery UI tabs and dialogs
jQuery(function() {
    var tabTitle = $( "#tab_title" ),
    tabContent = $( "#tab_content" ),
    tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-wrench' role='presentation'>Remove Tab</span></li>",
    tabCounter = <?php echo ( count($all_tabs) ); ?>;
    var tabs = $( "#tabs" ).tabs();

    // modal dialog init: custom buttons and a "close" callback reseting the form inside
    var dialog = $( "#dialog" ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            Add: function() {
            addTab();
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            form[ 0 ].reset();
        }
    });

    //setup dialog to edit tab
    $( "#dialog_edit" ).dialog({
        autoOpen: false,
        modal: true,
        width: "auto",
        height: "auto",
        buttons: {
            "Rename": function() {
              var id = window.lastClickedTab.replace("#tabs-", "");

              $( 'a[href="#tabs-' + id + '"]' ).text( $('input[name="rename_tab_title"]').val() );

              $( this ).dialog( "close" );
              $('#save_guide').fadeIn();
            },
            "Delete" : function() {
              var id = window.lastClickedTab.replace("#tabs-", "");

              $( 'a[href="#tabs-' + id + '"]' ).parent().remove();
              $( 'div#tabs-' + id ).remove();
              tabs.tabs("destroy");
              tabs.tabs();
              $( this ).dialog( "close" );
              $('#save_guide').fadeIn();
            },
            Cancel: function() {
            $( this ).dialog( "close" );
            }
        },
        open: function(event, ui) {
          var id = window.lastClickedTab.replace("#tabs-", "");
          $(this).find('input[name="rename_tab_title"]').val($( 'a[href="#tabs-' + id + '"]' ).text());
        },
        close: function() {
            form[ 0 ].reset();
        }
    });

    //override submit for form in edit tab dialog to click rename button
    $( "#dialog_edit" ).find( "form" ).submit(function( event ) {
        $(this).parent().parent().find('span:contains("Rename")').click();
        event.preventDefault();
    });

    // addTab form: calls addTab function on submit and closes the dialog
    var form = dialog.find( "form" ).submit(function( event ) {
        addTab();
        dialog.dialog( "close" );
        event.preventDefault();
    });

    // actual addTab function: adds new tab using the input from the form above
    function addTab() {
        var label = tabTitle.val() || "Tab " + tabCounter,
        id = "tabs-" + tabCounter,
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
        tabs.find( ".ui-tabs-nav" ).append( li );

        var slim = jQuery.ajax({
                    url: "helpers/create_tab.php",
                    type: "GET",
                    data: {},
                    dataType: "html",
                    success: function(html) {
                        tabs.tabs("destroy");

                        tabs.append( "<div id='" + id + "' class=\"sptab\">" + html
                         + "</div>" );

                        var ourval = jQuery( "#extra" ).val().split("-");

                        var lc = parseInt(ourval[0]) * 8;
                        lc = lc.toString() + "%"; //convert to string before concating
                        var cc = parseInt(ourval[1]) * 8;
                        cc = cc.toString() + "%";
                        var rc = (parseInt(ourval[2]) * 8 - 3);
                        rc = rc.toString() + "%";

                        reLayout(lc, cc, rc);

                        jQuery("#response").hide();
                        jQuery("#save_guide").fadeIn();

                        tabs.tabs();
                        tabCounter++;
                    }
                });
    }

    // addTab button: just opens the dialog
    $( "#add_tab" ).button().click(function() {
        dialog.dialog( "open" );
    });

    // edit icon: removing or renaming tab on click
    tabs.delegate( "span.ui-icon-wrench", "click", function(lobjClicked) {
        var List = $(this).parent().children("a");
        var Tab = List[0];
        window.lastClickedTab = $(Tab).attr("href");
        $('#dialog_edit').dialog("open");
    });
});

jQuery(window).load(function(){
        // jQuery functions to initialize after the page has loaded.
        refreshFeeds();
    });
</script>

<div id="guide_header">
    <div class="pure-g-r">
      <div class="wrapper-full">
        <div class="pure-u-1-2"> 
        
            <ul id="guide_nav">
                <li id="hide_header"><img src="<?php print $AssetPath; ?>images/icons/menu-26.png" title="<?php print _("show/hide header"); ?>" /></li>
                <li id="newbox" class="togglenewz"><a href="#"><img src="<?php print $AssetPath; ?>images/icons/down_circular-white-26.png" alt="" /><?php print _("New Box");?></a>
                    <?php print $all_boxes; ?>
                </li>
                <li class="showdisco"><a href="helpers/discover.php"><img src="<?php print $AssetPath; ?>images/icons/find-white.png" title="<?php print _("Find Box"); ?>" /><?php print _("Find Box"); ?></a></li>
                <li><a href=""><img src="<?php print $AssetPath; ?>images/icons/section-white.png" title="<?php print _("New Section"); ?>" /><?php print _("New Section"); ?></a></li>
                <!--<li class="showrecord"><a href="../records/record.php?wintype=pop&amp;caller_id=<?php print $subject_id; ?>"><?php print _("New Record"); ?></a></li>
                <li class="showmeta"><a href="metadata.php?subject_id=<?php print $subject_id; ?>&amp;wintype=pop"><?php print _("Metadata"); ?></a></li>
                <li id="layoutbox" class="togglelayout" href="metadata.php?subject_id=<?php print $subject_id; ?>&amp;wintype=pop"><a href="#"><?php print _("Layout"); ?></a>
                <div id="slider_options" style="display: none; width: 200px; padding: 1em;">
                    <p><?php print _("Adjust column sizes"); ?></p>
                    <div id="slider"></div>
                    <button id="save_layout" style="display:none;clear: left;margin-top: 1em;font-size: smaller;"><?php print _("SAVE CHANGES"); ?></button>
                </div>-->
            </li>
            <li class="selected"></li>
          </ul>
        </div>
        <div class="pure-u-1-2"> <h2>
        <?php print "<a target=\"_blank\" href=\"$PublicPath" . "guide.php?subject=$shortform\">$subject_name</a>"; ?>
        <a href="<?php print $PublicPath . "guide.php?subject=$shortform"; ?>"><img class="icon-view-guide" src="<?php print $AssetPath; ?>images/icons/visible-white-26.png" title="<?php print _("View Guide"); ?>" /></a>
        <a href="<?php print $CpanelPath . "guides/metadata.php?subject_id=$subject_id" . "&amp;wintype=pop"; ?>"><img class="icon-edit-guide" src="<?php print $AssetPath; ?>images/icons/gears-white-26.png" title="<?php print _("Edit Guide Metadata"); ?>" /></a>
        
        </h2></div>
      </div>
    </div>
</div>


<input id="extra" type="hidden" size="1" value="<?php print $jobj->{'maincol'}; ?>" name="extra" />

<!-- Save Button -->
 <p align="center" id="savour"><button class="button" id="save_guide"><?php print _("SAVE CHANGES"); ?></button></p>
</div>  
<!-- end guide header -->

<!-- Feedback -->
<div id="response"></div>



<!-- new tab form (suppressed until wrench clicked) -->
<div id="dialog" title="Tab data">
<form class="pure-form pure-form-aligned">
    <fieldset class="ui-helper-reset">
        <div class="pure-control-group">
            <label for="tab_title"><?php print _("Title"); ?></label>
            <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
        </div>
        <div class="pure-control-group">
            <label for="tab_external_link"><?php print _("Redirect URL"); ?></label>
            <input type="text" name="tab_external_link" id="tab_external link" />
        </div>
    </fieldset>
</form>
</div>

<!-- edit tab form (suppressed until wrench clicked) -->
<div id="dialog_edit" title="Tab edit">
    <form class="pure-form pure-form-aligned">
        <fieldset class="ui-helper-reset">
        <div class="pure-control-group">
        <label for="tab_title"><?php print _("New Title"); ?></label>
        <input type="text" name="rename_tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
        </div>
        <div class="pure-control-group">
            <label for="tab_external_link"><?php print _("Redirect URL"); ?></label>
            <input type="text" name="tab_external_link" id="tab_external link" />
        </div>
        </fieldset>
    </form>
</div>
<script>
//make tabs sortable
jQuery(function() {
    $(tabs).find( ".ui-tabs-nav" ).sortable({
        axis: "x",
        stop: function(event, ui) {
            if(jQuery(ui.item).attr("id") == 'add_tab' || jQuery(ui.item).parent().children(':first').attr("id") != 'add_tab')
                $(tabs).find( ".ui-tabs-nav" ).sortable("cancel");
            else
            {
               // $(tabs).tabs( "refresh" );
            	tabs.tabs("destroy");
            	tabs.tabs();
            	jQuery("#response").hide();
                jQuery("#save_guide").fadeIn();
            }
        }
    });
});
</script>

<div class="guidewrapper">
    <div id="tabs">

    <?php $lobjGuide->outputNavTabs(); ?>

    <?php
    $lobjGuide->outputTabs();
    ?>

    </div>
</div>
<?php include("../includes/footer.php"); ?>
