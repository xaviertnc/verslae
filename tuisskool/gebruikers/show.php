<?php // verslae/tuisskool/gebruikers/show.php - KRAGDAG GEBRUIKERS

include '../../app/bootstrap.php';

//include __MODELS__  . '/column.entity.php';
//include __MODELS__  . '/gebruiker.model.php';
//include __MODELS__  . '/csv-aflaai.domain.php';


Ui::init(['base-url' => 'tuisskool/gebruikers/', 'itemspp' => 23]);


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
		$itemcount = DB::count('tblgebruikers');

		$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);

		Ui::handle_popups(Request::get('dlg'), $keepParams);
		Ui::$pager_widget->config(Ui::$base_url, $itemcount, $itemspp, $pageno, $keepParams);
		Ui::$sort_widget->config(Ui::$base_url, 'desc', $keepParams);

		$orderby = Ui::$sort_widget->orderby();
		if ($orderby) $orderby = ' ORDER BY ' . $orderby;

		$limit = Ui::$pager_widget->limit();
		if ($limit) $limit = ' LIMIT ' . $limit;

		$gebruikers = DB::select('tblgebruikers' . $orderby . $limit);

		$opskrif = 'Stelsel Gebruikers';

		break;


	default:
		Errors::raise('Invalid Request');
}


// KRAGDAG GEBRUIKERS - HTML VIEW ?>
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
						<button class="btn btn-action" name="csv" type="submit" value="gebruikers">
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
			<div class="col1 right">
				<div class="pager min200"><?=Ui::$pager_widget?></div>
			</div>
		</div>
		<table class="list w100">
			<thead>
				<tr style="background-color:#005500">
					<th class="hide-sm" style="width:35px">#</th>
					<th style="width:40px"><?=Ui::$sort_widget->renderLink('ID', 'id')?></th>
					<th style="width:140px"><?=Ui::$sort_widget->renderLink('Gebruiker', 'naam')?></th>
					<th style="width:100px"><?=Ui::$sort_widget->renderLink('Intekennaam', 'gebruikernaam')?></th>
					<th style="width:100px"><?=Ui::$sort_widget->renderLink('Wagwoord', 'ou_wagwoord')?></th>
					<th style="width:80px"><?=Ui::$sort_widget->renderLink('Toegang', 'toegang')?></th>
					<th class="hide-sm" style="width:120px"><?=Ui::$sort_widget->renderLink('Tuisskakel', 'tuisskakel')?></th>
					<th class="hide-sm" style="width:150px"><?=Ui::$sort_widget->renderLink('Geskep Op', 'geskep_op')?></th>
				</tr>
			</thead>
			<tbody class="striped"><?php
				echo PHP_EOL; foreach ($gebruikers as $index => $item):?>
				<tr>
					<td class="hide-sm"><?=Ui::$pager_widget->offset+$index+1?>.</td>
					<td><?=$item->id?></td>
					<td class="nowrap max120" title="<?=$item->naam?>"><?=$item->naam?></td>
					<td class="nowrap max50" title="<?=$item->gebruikernaam?>"><?=$item->gebruikernaam?></td>
					<td class="nowrap max120" title="<?=$item->wagwoord?>"><?=$item->ou_wagwoord?></td>
					<td><?=$item->toegang?></td>
					<td class="hide-sm" title="<?=$item->tuisskakel?>"><?=$item->tuisskakel?></td>
					<td class="hide-sm" title="<?=$item->geskep_op?>"><?=$item->geskep_op?></td>
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
