<?php // verslae/kragdag/daggrafiek/show.php - KRAGDAG DAGGRAFIEK

include '../../app/bootstrap.php';

include __MODELS__  . '/ekspo.model.php';
include __MODELS__  . '/ekspos.repo.php';
//include __MODELS__  . '/column.entity.php';
//include __MODELS__  . '/gebruiker.model.php';
//include __MODELS__  . '/csv-aflaai.domain.php';


Ui::init(['base-url' => 'kragdag/daggrafiek/', 'itemspp' => 23]);


$gebruiker = Auth::check('super');

$keepParams = ['p', 'ipp', 'dlg', 'sort', 'dir', 'ekspo'];
$removeParams = [];


switch (Request::$method)
{
	case 'POST':

		do {

			Ui::handle_logout();

			if (Ui::handle_alerts()) { break; }

			// Meer goed..
			// CsvAflaaiDomain::hanteer_csv_aflaai(Request::get('csv'));

			$removeParams[] = 'p'; // If we get here, we propably posted to change the list state (i.e. Filters or IPP), and we need to remove p to reset its value to start. i.e. = 1

		} while (0);

		$updated_view_url = http_build_query(Request::getUpdatedUrlParams($_POST, $keepParams, $removeParams));

		Redirect::to($updated_view_url ? '?' . $updated_view_url : '');

		break;


	case 'GET':

		$pageno = Request::get('p', 1);
		$itemspp = Request::get('ipp', Ui::$itemspp);
		$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);

		$ekspos = EksposRepo::kryEkspos();
		$ekspo = EkspoModel::kiesEeen($ekspos, $ekspo_id);

		$itemcount = DB::count('view_besoekersperuur', 'WHERE ekspo_id=?', [$ekspo_id]);


		Ui::handle_popups(Request::get('dlg'), $keepParams);
		Ui::$pager_widget->config(Ui::$base_url, $itemcount, $itemspp, $pageno, $keepParams);
		Ui::$sort_widget->config(Ui::$base_url, 'desc', $keepParams);

		$orderby = Ui::$sort_widget->orderby();
		if ($orderby) $orderby = ' ORDER BY ' . $orderby;

		$limit = Ui::$pager_widget->limit();
		if ($limit) $limit = ' LIMIT ' . $limit;

		$besoekers = DB::select('view_besoekersperuur WHERE ekspo_id=?' . $orderby . $limit, [$ekspo_id]);

		$opskrif = 'Besoekers Per Uur';

		break;


	default:
		Errors::raise('Invalid Request');
}

Scripts::addLocalScripts('var ekspoSel=$("#ekspo_id"); ekspoSel.SumoSelect(); ekspoSel.change(function(){$("#filters-form").submit();return false;});');

// KRAGDAG DAGGRAFIEK - HTML VIEW ?>
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
<!--
				<li>
					<form method="post">
						<button class="btn btn-action" name="csv" type="submit" value="daggrafiek">
							<i class="doc-icon"></i><span>&nbsp; Laai af as CSV</span>
						</button>
					</form>
				</li>
-->
				<?=Ui::userinfo()?>
			</ul>
		</div>
	</header>

	<?=Ui::supernav($ekspo_id)?>

	<?=Ui::$alerts_widget?>

	<?=Ui::$flash_widget?>

	<section id="content">
		<div class="row filters-row">
			<form class="filters-form" id="filters-form" method="post">
				<select id="ekspo_id" name="ekspo"><?php
					echo PHP_EOL; foreach ($ekspos as $ekspo_option): ?>
					<option value="<?=$ekspo_option->id?>"<?=($ekspo_option->id==$ekspo_id)?' selected':''?>><?=$ekspo_option->naam?></option><?php
					echo PHP_EOL; endforeach; ?>
				</select>
			</form>
			<div class="col1 right">
				<div class="pager min200"><?=Ui::$pager_widget?></div>
			</div>
		</div>
		<table class="list w100">
			<thead>
				<tr style="background-color:#005500">
					<th class="hide-sm" style="width:80px">#</th>
					<th style="width:100px"><?=Ui::$sort_widget->renderLink('Dag', 'Dag')?></th>
					<th style="width:100px"><?=Ui::$sort_widget->renderLink('Uur', 'Uur')?></th>
					<th style="width:100px"><?=Ui::$sort_widget->renderLink('Besoekers', 'Besoekers')?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody class="striped"><?php
				echo PHP_EOL; foreach ($besoekers as $index => $item):?>
				<tr>
					<td class="hide-sm"><?=Ui::$pager_widget->offset+$index+1?>.</td>
					<td><?=$item->Dag?></td>
					<td><?=$item->Uur?>:00</td>
					<td><?=$item->Besoekers?></td>
					<td>&nbsp;</td>
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
