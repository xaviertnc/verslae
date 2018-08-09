<?php // verslae/kragdag/geskiedenis/show.php - KRAGDAG GESKIEDENIS

include '../../app/bootstrap.php';

include __MODELS__  . '/column.entity.php';
include __MODELS__  . '/geskiedenis.model.php';
include __MODELS__  . '/csv-aflaai.domain.php';


Ui::init(['base-url' => 'kragdag/geskiedenis/', 'itemspp' => 23]);


$gebruiker = Auth::check('super');

$modules = ['Geen', 'Registrasie', 'Ontvangs', 'Verslae'];
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
					<th style="width:160px"><?=Ui::$sort_widget->renderLink('Tyd', 'tyd')?></th>
					<th class="hide-sm" style="width:140px"><?=Ui::$sort_widget->renderLink('Gebruiker', 'gebruiker')?></th>
					<th class="hide-sm" style="width:65px"><?=Ui::$sort_widget->renderLink('Module', 'module_id')?></th>
					<th class="hide-sm" style="width:190px"><?=Ui::$sort_widget->renderLink('Aksie', 'inskrywing_subtiepe')?></th>
					<th><?=Ui::$sort_widget->renderLink('Besonderhede', 'detail')?></th>
<!--
					<th class="actions" style="width:80px">Instruksies</th>
-->
				</tr>
			</thead>
			<tbody class="striped"><?php
				echo PHP_EOL; foreach ($inskrywings as $index => $item):?>
				<tr>
					<td class="hide-sm"><?=Ui::$pager_widget->offset+$index+1?>.</td>
					<td title="<?=$item->tyd?>"><?=$item->tyd?></td>
					<td class="hide-sm nowrap max120" title="<?=$item->gebruiker?>"><?=$item->gebruiker?:'Gebruiker'?></td>
					<td class="hide-sm nowrap max50" title="<?=$modules[$item->module_id]?>"><?=$modules[$item->module_id]?></td>
					<td class="hide-sm nowrap max120" title="<?=$item->inskrywing_subtipe?>"><?=$item->inskrywing_subtipe?></td>
					<td class="nowrap max150" title="<?=$item->detail?>"><?=$item->detail?></td>
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
