<?php
/**
 *   @file stat.php
 *   @brief 
 *
 *   @author Jamie Little (little9)
 *   @date Aug 2016
 *   
 */

$subcat = "analytics";
$page_title = "Estadísticas";

include("../includes/header.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats;

$db = new Querier;
$stats = new Stats($db);
$total_views = $stats->getTotalViews();
$total_per_guide = $stats->getTotalViewsPerGuide();
$total_tab_clicks_per_guide = $stats->getTopTabsPerGuide();
$total_external_link_clicks = $stats->getTopExternalLinks();

?>

<style>
 .total-views {
     font-size:5em;
 }
</style>

<!-- Total Views Last Month -->
<div class="pure-g">
    <div class="pure-u-1-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total de vistas el mes pasado</div>
	    </div>
	    <div class="pluslet_body total-views">
		<?php print_r($total_views[0]['total_views_last_month']); ?>
	    </div>
	</div>
    </div>
</div>

<!-- Total External Link Clicks Per Guide -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total de vistas por guía el mes pasado</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
		    <thead>
			<tr>
			    <td>Guide Name</td>
			    <td>Number of Views</td>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_per_guide as $guide_total) { ?>
			    <tr>
				<td>
				    <?php echo $guide_total['page_title']; ?>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>



<!-- Total Tab Clicks Per Guide -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Haga clic en clics por guía el mes pasado</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
		    <thead>
			<tr>
			    <td>Nombre de la pestaña</td>
			    <td>Guía de forma corta</td>
			    <td>Número de clicks</td>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_tab_clicks_per_guide as $guide_total) { ?>
			    <tr>
				<td>
				    <?php echo $guide_total['tab_name']; ?>
				</td>
				<td>
				    <?php echo $guide_total['subject_short_form']; ?>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>


<!-- Total External Link Clicks -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total de clics de enlace por guía el mes pasado</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
		    <thead>
			<tr>
			    <td>Nombre de la Guía</td>
			    <td>Link URL</td>
			    <td>Número de Clicks</td>
			    
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_external_link_clicks as $guide_total) { ?>
			    <tr>
				<td>
				   <?php echo $guide_total['subject_short_form']; ?>
				</td>
				<td>
				    <a href="<?php echo $guide_total['link_url']; ?>"><?php echo $guide_total['link_url']; ?></a>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>


<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"></link>
<script>
 $(document).ready(function(){
     $('.stats-table').DataTable();
 });
</script>


<?php 
include("../includes/footer.php");
?>
