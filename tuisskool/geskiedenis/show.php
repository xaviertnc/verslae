<?php // verslae/tuisskool/geskiedenis/show.php - KRAGDAG GESKIEDENIS

include '../../app/bootstrap.php';

include __MODELS__  . '/column.entity.php';
include __MODELS__  . '/geskiedenis.model.php';
include __MODELS__  . '/csv-aflaai.domain.php';


Ui::init(['base-url' => 'tuisskool/geskiedenis/', 'itemspp' => 23]);


$gebruiker = Auth::check('super');

$modules = ['Geen', 'Admin', 'Uitstaller', 'Besoeker', 'Registrasie', 'Ontvangs', 'Versale', 'Webtuiste', 'Toets'];
$keepParams = ['p', 'ipp', 'dlg', 'sort', 'dir', 'ekspo'];
$removeParams = [];


switch (Request::$method)
{
	case 'POST':

		do {

			Ui::handle_logout();

			if (Ui::handle_alerts()) { break; }

			// Meer goed..
			CsvAflaaiDomain::hanteer_csv_aflaai(Request::get('csv'));

			$removeParams[] = 'p'; // If we get here, we propably posted to change the list state (i.e. Filters or IPP), and we need to remove p to reset its value to start. i.e. = 1

		} while (0);

		$updated_view_url = http_build_query(Request::getUpdatedUrlParams($_POST, $keepParams, $removeParams));

		Redirect::to($updated_view_url ? '?' . $updated_view_url : '');

		break;


	case 'GET':

		$pageno = Request::get('p', 1);
		$itemspp = Request::get('ipp', Ui::$itemspp);
		$itemcount = DB::count('tblgeskiedenis');

		$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);

		Ui::handle_popups(Request::get('dlg'), $keepParams);
		Ui::$pager_widget->config(Ui::$base_url, $itemcount, $itemspp, $pageno, $keepParams);
		Ui::$sort_widget->config(Ui::$base_url, 'desc', $keepParams);

		$orderby = Ui::$sort_widget->orderby();
		if ($orderby) $orderby = ' ORDER BY ' . $orderby;

		$limit = Ui::$pager_widget->limit();
		if ($limit) $limit = ' LIMIT ' . $limit;

		$inskrywings = DB::select('view_geskiedenis' . $orderby . $limit);
    
    $gebruikersLys = GeskiedenisModel::lysGebruikers();
    
    $gebruikersIndeks = [];
    foreach ($gebruikersLys as $gebruiker)
    {
      $gebruikersIndeks[$gebruiker->id] = $gebruiker->naam;
    }

		$opskrif = 'Gebruiker Geskiedenis';

		break;

	default:
		Errors::raise('Invalid Request');
}


// KRAGDAG GESKIEDENIS - HTML VIEW ?>
<?php include __SNIPPETS__.'/html.head.snippet.php'; ?>

	<?=Ui::$local_styles_widget?>

	<style>
		.stats-row { margin-top: 21px; }
		.filters-row { margin-top: 21px; margin-bottom: 7px; }
		.stats-row li { margin: 2px; }
		.pager { margin-top: 5px; }
		.popup-window label { width: 100%; }
		.popup-window input { width: 100%; padding: 3px; font-size: 18px; }
	</style>

	<header class="row">
		<h1 class="hc2" title="<?=$opskrif?>"><?=$opskrif?></h1>
		<div class="hc1 min410 right">
			<ul class="nav">
				<li>
					<form method="post">
						<button class="btn btn-action" name="csv" type="submit" value="geskiedenis">
							<i class="doc-icon"></i><span>&nbsp; Laai af as CSV</span>
						</button>
					</form>
				</li>
				<?=Ui::userinfo()?>
			</ul>
		</div>
	</header>

	<?=Ui::supernav($ekspo_id)?>

	<?=Ui::$alerts_widget?>

	<?=Ui::$flash_widget?>

	<section id="content">
		<div class="row filters-row">
			<div class="col1 right">
				<div class="pager min200"><?=Ui::$pager_widget?></div>
			</div>
		</div>
		<table class="list w100">
			<thead>
				<tr style="background-color:#005500">
					<th class="hide-sm" style="width:44px">#</th>
					<th style="width:160px"><?=Ui::$sort_widget->renderLink('Tyd', 'datum')?></th>
					<th class="hide-sm" style="width:50px"><?=Ui::$sort_widget->renderLink('Gebruiker', 'gebruiker_id')?></th>
					<th class="hide-sm" style="width:65px"><?=Ui::$sort_widget->renderLink('Module', 'module_id')?></th>
					<th class="hide-sm" style="width:130px"><?=Ui::$sort_widget->renderLink('Aksie', 'subtiepe')?></th>
					<th><?=Ui::$sort_widget->renderLink('Besonderhede', 'beskrywing')?></th>
<!--
					<th class="actions" style="width:80px">Instruksies</th>
-->
				</tr>
			</thead>
			<tbody class="striped"><?php
				echo PHP_EOL; foreach ($inskrywings as $index => $item):?>
				<tr>
					<td class="hide-sm"><?=Ui::$pager_widget->offset+$index+1?>.</td>
					<td title="<?=$item->datum?>"><?=$item->datum?></td>
					<td class="hide-sm nowrap max50" title="<?=$item->gebruiker_id?>">
            <?=array_get($gebruikersIndeks, $item->gebruiker_id, 'Onbekend')?>
          </td>
					<td class="hide-sm nowrap max50" title="<?=$modules[$item->module_id]?>"><?=$modules[$item->module_id]?></td>
					<td class="hide-sm nowrap max50" title="<?=$item->subtipe?>"><?=$item->subtipe?></td>
					<td class="nowrap max150" title="<?=$item->beskrywing?>"><?=$item->beskrywing?></td>
<!--
					<td>&nbsp;</td>
-->
				</tr><?php
				echo PHP_EOL; endforeach; ?>
			</tbody>
		</table>
		<div class="pager right">
			<small>Items: <?=Ui::$pager_widget->offset+1?> tot <?=min(Ui::$pager_widget->offset+Ui::$pager_widget->itemspp, Ui::$pager_widget->itemscount)?> van <?=Ui::$pager_widget->itemscount?></small>
		</div>
	</section>

	<br>

	<footer><?=Ui::footer()?></footer>

<?php include __SNIPPETS__.'/html.tail.snippet.php'; ?>
