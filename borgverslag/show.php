<?php // verslae/borgverslag/show.php - SOLIDARITEIT VERSLAG

include '../app/bootstrap.php';

include __MODELS__  . '/ekspo.model.php';
include __MODELS__  . '/ekspos.repo.php';
include __MODELS__  . '/column.entity.php';
include __MODELS__  . '/registrasies.repo.php';
include __MODELS__  . '/borgverslag.model.php';
include __MODELS__  . '/csv-aflaai.domain.php';

Ui::init(['base-url' => 'borgverslag/', 'itemspp' => 17]);


$gebruiker = Auth::check('borg');


$keepParams = ['p', 'ipp', 'ekspo', 'dlg', 'sort', 'dir'];
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
		$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);
    
		$opsomming = RegistrasiesRepo::kryRegistrasiesOpsomming($ekspo_id);

		$totaal_borgverslag = $opsomming->reedsborglid + $opsomming->nie_lede_kontak;
    
		Ui::handle_popups(Request::get('dlg'), $keepParams);
		Ui::$pager_widget->config(Ui::$base_url, $totaal_borgverslag, $itemspp, $pageno, $keepParams);
		Ui::$sort_widget->config(Ui::$base_url, 'desc', $keepParams);
        
		$registrasies = RegistrasiesRepo::lysBorgRegistrasies($ekspo_id, Ui::$pager_widget->limit(), Ui::$sort_widget->orderby());
		$ekspos = EksposRepo::kryEkspos();

		$geselekteerde_ekspo = EkspoModel::kiesEeen($ekspos, $ekspo_id);

		$opskrif = $geselekteerde_ekspo ? $geselekteerde_ekspo->naam : 'Tuisskool Borg';

		Scripts::addLocalScripts('var ekspoSel=$("#ekspo_id"); ekspoSel.SumoSelect(); ekspoSel.change(function(){$("#filters-form").submit();return false;});'); // Moet WIDGET word...

		break;


	default:
		Errors::raise('Invalid Request');
}

// SOLIDARITEIT VERSLAG - HTML VIEW ?>
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
						<button class="btn btn-action" name="csv" type="submit" value="borgverslag">
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
		<div class="row stats-row">
			<div class="col1">
				<ul class="framed padded min275">
					<li>Alle Registrasies: <?=$opsomming->totaal?></li>
					<li>Totaal Borg Verwant: <?=$totaal_borgverslag?:0?></li>
				</ul>
			</div>
			<div class="col1 right">
				<ul class="framed padded min275">
					<li>Kontakbare nie-lede: <?=$opsomming->nie_lede_kontak?:0?></li>
					<li>Borg kan my kontak: <?=$opsomming->borgkanmykontak?:0?></li>
					<li>Bestaande Borg lede: <?=$opsomming->reedsborglid?:0?></li>
				</ul>
			</div>
		</div>
		<div class="row filters-row">
			<form class="col1" id="filters-form" method="post">
				<select class="min276" id="ekspo_id" name="ekspo"><?php
					echo PHP_EOL; foreach ($ekspos as $ekspo): ?>
					<option value="<?=$ekspo->id?>"<?=($ekspo->id==$ekspo_id)?' selected':''?>><?=$ekspo->naam?></option><?php
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
					<th class="hide-sm" style="width:44px">#</th>
					<th style="width:80px"><?=Ui::$sort_widget->renderLink('Datum', 'registrasiedatum')?></th>
					<th class="hide-sm" style="width:180px"><?=Ui::$sort_widget->renderLink('Besoeker', 'volleNaam')?></th>
					<th style="width:100px">Selfoon</th>
					<th style="width:280px"><?=Ui::$sort_widget->renderLink('Epos', 'epos')?></th>
					<th class="center" style="width:50px"><?=Ui::$sort_widget->renderLink('Kontak', 'borgkanmykontak')?></th>
					<th class="center" style="width:50px"><?=Ui::$sort_widget->renderLink('Lid', 'reedsborglid')?></th>
				</tr>
			</thead>
			<tbody class="striped"><?php
				echo PHP_EOL; foreach ($registrasies as $index => $item):?>
				<tr>
					<td class="hide-sm"><?=Ui::$pager_widget->offset+$index+1?>.</td>
					<td title="<?=$item->registrasiedatum?>"><?=substr($item->registrasiedatum,0,11)?></td>
					<td class="hide-sm nowrap max120" title="<?=$item->volleNaam?>"><?=$item->volleNaam?></td>
					<td class="nowrap max50" title="<?=$item->selfoon?:$item->telefoon?>"><?=$item->selfoon?:$item->telefoon?></td>
					<td class="nowrap max150" title="<?=$item->epos?>"><?=$item->epos?:'-'?></td>
					<td class="center"><?=$item->borgkanmykontak?'<span class="green">Ja</span>':'<span class="red">Nee</span>'?></td>
					<td class="center"><?=$item->reedsborglid?'<span class="red">Ja</span>':'<span class="green">Nee</span>'?></td>
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
