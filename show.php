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


function hetReedsOpgedaag($regs, $regId, $dag)
{
  if ($dag > 1)
  {
    for ($d = ($dag - 1); $d > 0; $d--)
    {
      if (isset($regs[$d][$regId])) { return true; }
    }
  }
}  


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
    
    // Kry die relevante ekspo om die ekspoinligting te kan vertoon.
		$ekspos = EksposRepo::kryEkspos();
		$ekspo = EkspoModel::kiesEeen($ekspos, $ekspo_id);
		$ekspo_dae = EkspoModel::kryAantalDae($ekspo);
    
    // Sommeer opdaginggetalle vir volwasses en kinders per registrasie 
    // oor AL die dae van die ekspo. Die totale sluit dus OPDAGINGS vir ELKE DAG in.
		$opgedaag = OntvangsRepo::kryOpgedaagOpsomming($ekspo_id);
    
    // Uniek: Raporteer slegs die grootste volwasses- en kinders opdaagtellings 
    // vir elke registrasie oor AL die dae van die ekspo. 
		$opgedaag_uniek = OntvangsRepo::kryOpgedaagUniekOpsomming($ekspo_id);
    
    // Gebruik om 'vooraf_nie_opgedaag' te bereken.
		$registrasietipes = RegistrasiesRepo::kryRegTipesOpsomming($ekspo_id);
    
		$voorafregistrasies_opgedaag = $opgedaag_uniek->volwassenes - $registrasietipes->hek;
		$vooraf_nie_opgedaag = $registrasietipes->vooraf - $voorafregistrasies_opgedaag;

		$kinders_hek = [];
		$kinders_hek_gratis = [];
		$volwassenes_hek = [];
		$volwassenes_hek_gratis = [];
		$kinders_vooraf = [];
		$volwassenes_vooraf = [];
		$registrasies_vooraf = [];
		$kinders_weer_opgedaag = [];
		$volwassenes_weer_opgedaag = [];

		$registrasies_vooraf_totaal = 0;
		$volwassenes_hek_totaal = 0;
		$volwassenes_vooraf_totaal = 0;
    $gratis_deurlaat_totaal = 0;
    
    // $dups = [];

    // Kry al die opdagings vir die huidige ekspo...
		$opdagings = OntvangsRepo::lysOpdagings($ekspo_id);
		$registrasies = [];

    // Hardloop nou deur al die opdagings en genereer statistieke.
		foreach ($opdagings as $opdaging)
		{
			$ekspo_dag = $opdaging->dag;

			$reg_id = $opdaging->registrasie_id;
      
      // Hou 'n lys van al die registrasies per ekspodag.
      // Skep 'n nuwe dag-groep indien dit nognie bestaan nie.
      if (empty($registrasies[$ekspo_dag])) {
        $registrasies[$ekspo_dag] = [];
				$kinders_hek[$ekspo_dag] = 0;
        $kinders_hek_gratis[$ekspo_dag] = 0;
				$volwassenes_hek[$ekspo_dag] = 0;
				$volwassenes_hek_gratis[$ekspo_dag] = 0;
				$registrasies_hek[$ekspo_dag] = 0;
				$kinders_vooraf[$ekspo_dag] = 0;
				$volwassenes_vooraf[$ekspo_dag] = 0;
				$registrasies_vooraf[$ekspo_dag] = 0;
				$kinders_weer_opgedaag[$ekspo_dag] = 0;
				$volwassenes_weer_opgedaag[$ekspo_dag] = 0;
      }
        
      // Las 'n nuwe registrasieentiteit by die dag-groep indien dit nognie gelys is nie.
      // Slegs EEN opdaging moet bestaan per registrasie per dag!
			if (empty($registrasies[$ekspo_dag][$reg_id])) {
				$reg = new stdClass();
				$reg->kinders = $opdaging->kinders;
				$reg->volwassenes = $opdaging->volwassenes;
				$registrasies[$ekspo_dag][$reg_id] = $reg;
			}
			else
			{
        // Ignoreer hierdie duplikaat opdaging vir hierdie dag!  Hierdie opdaging moet nie bestaan nie!
        // Moet sommer hier die oortollige opdaging uitvee!? ....
        // $dups[] = $opdaging->id;
        continue;
			}

			if ($opdaging->registrasietipe_id < 7) {
				$kinders_vooraf[$ekspo_dag] += $opdaging->kinders;
				$volwassenes_vooraf[$ekspo_dag] += $opdaging->volwassenes;
				$registrasies_vooraf[$ekspo_dag]++;
        $volwassenes_vooraf_totaal += $opdaging->volwassenes;;
        $registrasies_vooraf_totaal++;
			}

			if ($opdaging->registrasietipe_id >= 7) {
				$kinders_hek[$ekspo_dag] += $opdaging->kinders;
				$volwassenes_hek[$ekspo_dag] += $opdaging->volwassenes;
        $volwassenes_hek_totaal += $opdaging->volwassenes;
				$registrasies_hek[$ekspo_dag]++;
			}
      
			if (in_array($opdaging->registrasietipe_id, [7,10])) {
				$kinders_hek_gratis[$ekspo_dag] += $opdaging->kinders;
				$volwassenes_hek_gratis[$ekspo_dag] += $opdaging->volwassenes;
        $gratis_deurlaat_totaal += $opdaging->volwassenes;
			}
      
		}

    // echo '<pre>DELETE FROM tblopgedaag WHERE id IN (' . implode(',', $dups) . ')</pre>';
    
		for ($ekspo_dag = 2; $ekspo_dag <= $ekspo_dae; $ekspo_dag++)
		{
      if (empty($registrasies[$ekspo_dag])) { break; }
      $dagRegistrasies = $registrasies[$ekspo_dag];
      foreach ($dagRegistrasies as $reg_id => $reg)
      {
        if (hetReedsOpgedaag($registrasies, $reg_id, $ekspo_dag))
        {
          $kinders_weer_opgedaag[$ekspo_dag] += $opdaging->kinders;      
          $volwassenes_weer_opgedaag[$ekspo_dag] += $opdaging->volwassenes;
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
							<th>Registrasies</th>
							<th>Volwasses</th>
							<th>Kinders</th>
						</tr>
					</thead>
					<tbody class="grid">
						<tr>
							<td>&nbsp;</td>
							<td>Ongeregistreerde Besoekers</td>
							<td><?=array_get($registrasies_hek, $ekspo_dag, 0)?></td>
							<td><?=array_get($volwassenes_hek, $ekspo_dag, 0)?></td>
							<td><?=array_get($kinders_hek, $ekspo_dag, 0)?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Geregistreerdes Besoekers</td>
							<td><?=array_get($registrasies_vooraf, $ekspo_dag, 0)?></td>
							<td><?=array_get($volwassenes_vooraf, $ekspo_dag, 0)?></td>
							<td><?=array_get($kinders_vooraf, $ekspo_dag, 0)?></td>
						</tr>
						<?php if ($ekspo_dag > 1):?>
						<tr>
							<td>&nbsp;</td>
							<td><i>Geregistreerde Besoekers (Weer opgedaag)</i></td>
							<td>&nbsp;</td>
							<td><i><?=array_get($volwassenes_weer_opgedaag, $ekspo_dag, 0)?></i></td>
							<td><i><?=array_get($kinders_weer_opgedaag, $ekspo_dag, 0)?></i></td>
						</tr>
						<?php endif;?>
						<tr>
							<td>&nbsp;</td>
							<td><i>Toegangsfooi Betaal</i></td>
							<td>&nbsp;</td>
							<td><i><?=array_get($volwassenes_hek, $ekspo_dag, 0) - array_get($volwassenes_hek_gratis, $ekspo_dag, 0)?></i></td>
							<td>&nbsp;</td>
						</tr>               
						<tr>
							<td>&nbsp;</td>
							<td><i>Gratis deurgelaat</i></td>
							<td>&nbsp;</td>
							<td><i><?=array_get($volwassenes_hek_gratis, $ekspo_dag, 0)?></i></td>
							<td><i><?=array_get($kinders_hek_gratis, $ekspo_dag, 0)?></i></td>
						</tr>            
						<tr class="subtotals">
							<td>&nbsp;</td>
							<td><b>Dag Totaal:</b></td>
							<td><b><?=count(array_get($registrasies, $ekspo_dag, []))?></b></td>
							<td><b><?=$opgedaag->{'volwassenes_dag'.$ekspo_dag}?></b></td>
							<td><b><?=$opgedaag->{'kinders_dag'.$ekspo_dag}?></b></td>
						</tr>
					</tbody>
				<?php endfor; ?>
					<thead>
						<tr style="background-color:#004400">
							<th colspan="2">EKSPO BESOEKERS TOTAAL</th>
							<th>Registrasies</th>
							<th>Volwasses</th>
							<th>Kinders</th>
						</tr>
					</thead>
					<tbody class="grid">
						<tr>
							<td>&nbsp;</td>
							<td>Ongeregistreerde Besoekers:</td>
							<td>&nbsp;</td>
							<td><?=$volwassenes_hek_totaal?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Geregistreerde Besoekers</td>
							<td>&nbsp;</td>
							<td><?=$volwassenes_vooraf_totaal?></td>
							<td>&nbsp;</td>
						</tr>
						<tr class="subtotals">
							<td>&nbsp;</td>
							<td><i>Ekspo Besoekers Totaal (Uniek)</i></td>
							<td>&nbsp;</td>
							<td><i><?=$opgedaag_uniek->volwassenes?></i></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><i>Gratis deurgelaat</i></td>
							<td>&nbsp;</td>
							<td><i><?=$gratis_deurlaat_totaal?></i></td>
							<td>&nbsp;</td>
						</tr>            
						<tr class="totals">
							<td>&nbsp;</td>
							<td><b>Ekspo Totaal</b></td>
							<td><b><?=$opgedaag->registrasies_totaal?></b></td>
							<td><b><?=$opgedaag->volwassenes_totaal?></b></td>
							<td><b><?=$opgedaag->kinders_totaal?></b></td>
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
				<ul class="reg-info framed padded">
					<li><label>Hek Registrasies</label> = <?=$registrasietipes->hek?></li>
					<li><label>Vooraf Registrasies</label> = <?=$registrasietipes->vooraf?></li>
					<li class="totals"><b><label>Alle Registrasies</label> = <?=$registrasietipes->totaal?></b></li>
				</ul>
				<br>
				<ul class="reg-info framed padded">
					<li><label>Vooraf Reg. Opgedaag</label> = <?=$registrasies_vooraf_totaal?> van <?=$registrasietipes->vooraf?></li>
					<li><label>Vooraf Nie Opgedaag</label> = <?=$vooraf_nie_opgedaag?> van <?=$registrasietipes->vooraf?></li>
				</ul>
				<br>
				<ul class="reg-info framed padded">
					<li><label>Toegangfooi Ontvang</label> = <?=$opgedaag->volwassenes_totaal - $gratis_deurlaat_totaal - $volwassenes_vooraf_totaal?></li>
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
