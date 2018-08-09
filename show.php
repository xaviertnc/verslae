<?php // verslae/show.php - KRAGDAG OORSIG / INDEKS

include 'app/bootstrap.php';

include __MODELS__  . '/ekspo.model.php';
include __MODELS__  . '/ekspos.repo.php';
include __MODELS__  . '/registrasies.repo.php';
include __MODELS__  . '/ontvangs.repo.php';

include __WIDGETS__  . '/objectview.widget.php';
include __WIDGETS__  . '/listview.widget.php';


Ui::init();


$gebruiker = Auth::check('super');

$keepParams = ['p', 'ipp', 'ekspo', 'dlg', 'sort', 'dir'];
$removeParams = [];

switch (Request::$method)
{
	case 'POST':
		do {

			Ui::handle_logout();

			if (Ui::handle_alerts()) { break; }

			// Meer goed..

			$removeParams[] = 'p'; // If we get here, we propably posted to change the list state (i.e. Filters or IPP), and we need to remove p to reset its value to start. i.e. = 1

		} while (0);

		$updated_view_url = http_build_query(Request::getUpdatedUrlParams($_POST, $keepParams, $removeParams));

		Redirect::to($updated_view_url ? '?' . $updated_view_url : '');

		break;


	case 'GET':

		$ekspo_id = Request::get('ekspo', __HUIDIGE_EKSPO_ID__);
		$ekspos = EksposRepo::kryEkspos();
		$ekspo = EkspoModel::kiesEeen($ekspos, $ekspo_id);
		$ekspo_dae = EkspoModel::kryAantalDae($ekspo);
		$ontvangs = OntvangsRepo::kryOntvangsOpsomming($ekspo_id);
		$registrasies = RegistrasiesRepo::kryRegistrasiesOpsomming($ekspo_id);
		$vooraf_opgedaag = $ontvangs->volwasses_uniek - $registrasies->hek;
		$vooraf_nie_opgedaag = $registrasies->vooraf - $vooraf_opgedaag;

		$volwasses_hek = [];
		$volwasses_hek_gratis = [];
		$volwasses_hek_betaal = [];
		$volwasses_vooraf = [];
		$volwasses_vooraf_uniek = [];
		$kinders_hek = [];
		$kinders_vooraf = [];

		$opdagings = [];

		for ($ekspo_dag = 1; $ekspo_dag <= $ekspo_dae; $ekspo_dag++)
		{
			$volwasses_hek[$ekspo_dag] = 0;
			$volwasses_hek_gratis[$ekspo_dag] = 0;
			$volwasses_hek_betaal[$ekspo_dag] = 0;
			$volwasses_vooraf[$ekspo_dag] = 0;
			$volwasses_vooraf_uniek[$ekspo_dag] = 0;
			$kinders_hek[$ekspo_dag] = 0;
			$kinders_vooraf[$ekspo_dag] = 0;

			$opdagings[$ekspo_dag] = OntvangsRepo::kryOpdagingsPerBesoeker($ekspo_id, $ekspo_dag);

			foreach ($opdagings[$ekspo_dag] as $opdaging)
			{
				if (in_array($opdaging->registrasietipe_id, [1,4])) {
					$kinders_vooraf[$ekspo_dag] += $opdaging->kinders;
					$volwasses_vooraf[$ekspo_dag] += $opdaging->volwasses;
					$volwasses_vooraf_uniek[$ekspo_dag] += $opdaging->volwasses_uniek;
				}
				if (in_array($opdaging->registrasietipe_id, [7,10])) {
					$kinders_hek[$ekspo_dag] += $opdaging->kinders;
					$volwasses_hek[$ekspo_dag] += $opdaging->volwasses;
					$volwasses_hek_gratis[$ekspo_dag] += $opdaging->volwasses;
				}
				if (in_array($opdaging->registrasietipe_id, [9,12])) {
					$kinders_hek[$ekspo_dag] += $opdaging->kinders;
					$volwasses_hek[$ekspo_dag] += $opdaging->volwasses;
					$volwasses_hek_betaal[$ekspo_dag] += $opdaging->volwasses;
				}
			}
		}

		break;


	default:
		Errors::raise('Invalid Request');
}


// $objectview_widget = new ObjectViewWidget();
// $listview_widget = new ListViewWidget();

Scripts::addLocalScripts('var ekspoSel=$("#ekspo_id"); ekspoSel.SumoSelect(); ekspoSel.change(function(){$("#filters-form").submit();return false;});');


// KRAGDAG OORSIG / INDEKS - HTML VIEW ?>
<?php include __SNIPPETS__.'/html.head.snippet.php'; ?>

	<?=Ui::$local_styles_widget?>

	<style>
		.sub-title { margin-bottom: 4px; }
		.stats-row { margin: 10px 7px; }
		.list { font-size: 14px; width: 95%; margin-top: 10px; }
		.list b { color: white; }
		.list i { color: palegoldenrod; font-size: 12px; }
		.grid td { border: 1px solid dimgrey; }
		.filters-form { display: block; margin-bottom: 10px; }
		.ekspo-info, .reg-info { display: block; font-size: 14px; }
		.ekspo-info label { width: 120px; display: inline-block; }
		.reg-info label { width: 160px; display: inline-block; }
		.sidemenu { display: block; padding: 15px; }
		.sidemenu li { margin: 18px 0; text-align: center; }
		.sidemenu .btn { display:inline-block; width: 150px; text-align: center; }
		.totals b { color: gold; }
		.totals, .subtotals { background-color: #111; }
		.list th { line-height: 20px; padding-left: 3px; }

	</style>

	<header class="row">
		<h1 class="hc2" title="KragDag Verslae - Oorsig">KragDag Oorsig</h1>
		<div class="hc1 min270 right">
			<ul class="nav">
				<?=Ui::userinfo()?>
			</ul>
		</div>
	</header>

	<?=Ui::supernav($ekspo_id)?>

	<?=Ui::$alerts_widget?>

	<?=Ui::$flash_widget?>

	<section id="content">
		<div class="row stats-row">
			<div class="col2">
				<form class="filters-form" id="filters-form" method="post">
					<select id="ekspo_id" name="ekspo"><?php
						echo PHP_EOL; foreach ($ekspos as $ekspo_option): ?>
						<option value="<?=$ekspo_option->id?>"<?=($ekspo_option->id==$ekspo_id)?' selected':''?>><?=$ekspo_option->naam?></option><?php
						echo PHP_EOL; endforeach; ?>
					</select>
				</form>
				<table class="list">
				<?php for ($ekspo_dag = 1; $ekspo_dag <= $ekspo_dae; $ekspo_dag++): ?>
					<thead>
						<tr style="background-color:#112211">
							<th colspan="2">Dag <?=$ekspo_dag?></th>
							<th>Volwasses</th>
							<th>Kinders</th>
						</tr>
					</thead>
					<tbody class="grid">
						<tr>
							<td>&nbsp;</td>
							<td>Hek / Instap Registrasies:</td>
							<td><?=$volwasses_hek[$ekspo_dag]?></td>
							<td><?=$kinders_hek[$ekspo_dag]?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><i>&nbsp;&nbsp;&nbsp;- Hek Gratis Deurgelaat</i></td>
							<td><i><?=$volwasses_hek_gratis[$ekspo_dag]?></i></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Vooraf Geregistreerdes Opgedaag:</td>
							<td><?=$volwasses_vooraf_uniek[$ekspo_dag]?></td>
							<td><?=$kinders_vooraf[$ekspo_dag]?></td>
						</tr>
						<?php if ($ekspo_dag > 1):?>
						<tr>
							<td>&nbsp;</td>
							<td>Vooraf  Geregistreerdes Weer Opgedaag:</td>
							<td><?=$volwasses_vooraf[$ekspo_dag] - $volwasses_vooraf_uniek[$ekspo_dag]?></td>
							<td>&nbsp;</td>
						</tr>
						<?php endif;?>
						<tr class="subtotals">
							<td>&nbsp;</td>
							<td><b>Dag Totaal:</b></td>
							<td><b><?=$ontvangs->{'volwasses_dag'.$ekspo_dag}?></b></td>
							<td><b><?=$ontvangs->{'kinders_dag'.$ekspo_dag}?></b></td>
						</tr>
					</tbody>
				<?php endfor; ?>
					<thead>
						<tr style="background-color:#004400">
							<th colspan="2">EKSPO BESOEKERS TOTAAL</th>
							<th>Volwasses</th>
							<th>Kinders</th>
						</tr>
					</thead>
					<tbody class="grid">
						<tr>
							<td>&nbsp;</td>
							<td>Hek / Instap Registrasies</td>
							<td><?=$registrasies->hek?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><i>&nbsp;&nbsp;&nbsp;- Hek Gratis Deurgelaat</i></td>
							<td><i><?=$registrasies->hek_gratis?></i></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Vooraf Geregistreerdes Opgedaag</td>
							<td><?=$vooraf_opgedaag?></td>
							<td>&nbsp;</td>
						</tr>
						<tr class="subtotals">
							<td>&nbsp;</td>
							<td><b>Ekspo Besoekers Totaal (Uniek)</b></td>
							<td><b><?=$ontvangs->volwasses_uniek?></b></td>
							<td>&nbsp;</td>
						</tr>
						<tr class="totals">
							<td>&nbsp;</td>
							<td><b>Ekspo Besoekers Totaal</b></td>
							<td><b><?=$ontvangs->volwasses_totaal?></b></td>
							<td><b><?=$ontvangs->kinders_totaal?></b></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col1">
				<ul class="ekspo-info framed padded">
					<li><b><?=$ekspo->naam?></b></li>
					<li><label>id</label> = <?=$ekspo->id?></li>
					<li><label>Begin Datum</label> = <?=$ekspo->begindatum?></li>
					<li><label>Eind Datum</label> = <?=$ekspo->einddatum?></li>
					<li><label>Toegangsfooi</label> = R<?=$ekspo->toegangsfooi?></li>
					<li><label>Seminarefooi</label> = R<?=$ekspo->seminarefooi?></li>
				</ul>
				<br>
				<ul class="ekspo-info framed padded">
					<li><label>Alle Registrasies</label> = <?=$registrasies->totaal?></li>
				</ul>
				<br>
				<ul class="reg-info framed padded">
					<li><label>Vooraf Registrasies</label> = <?=$registrasies->vooraf?></li>
					<li><label>Vooraf Nie Opgedaag</label> = <?=$vooraf_nie_opgedaag?></li>
				</ul>
				<br>
				<ul class="sidemenu framed">
					<li><a class="btn btn-primary" href="kragdag/gebruikers/">Gebruikers</a></li>
					<li><a class="btn btn-primary" href="kragdag/geskiedenis/">Geskiedenis</a></li>
					<li><a class="btn btn-primary" href="kragdag/verwysings/?ekspo=<?=$ekspo_id?>">Verwysings</a></li>
					<li><a class="btn btn-primary" href="kragdag/daggrafiek/?ekspo=<?=$ekspo_id?>">Besoekers / Uur</a></li>
				</ul>
			</div>
		</div>
		<br>

	</section>

	<footer><?=Ui::footer()?></footer>

<?php include __SNIPPETS__.'/html.tail.snippet.php'; ?>
