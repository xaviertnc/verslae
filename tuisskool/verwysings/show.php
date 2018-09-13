<?php // verslae/tuisskool/verwysings/show.php - KRAGDAG VERWYSINGS

include '../../app/bootstrap.php';

include __MODELS__  . '/ekspo.model.php';
include __MODELS__  . '/ekspos.repo.php';
include __MODELS__  . '/column.entity.php';
include __MODELS__  . '/verwysings.model.php';
include __MODELS__  . '/csv-aflaai.domain.php';

include __WIDGETS__  . '/objectview.widget.php';
include __WIDGETS__  . '/listview.widget.php';

Ui::init(['base-url' => 'tuisskool/verwysings/', 'itemspp' => 21]);


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

		$itemcount = DB::count('view_verwysings', 'WHERE ekspo_id=?', [$ekspo_id]);

		Ui::handle_popups(Request::get('dlg'), $keepParams);
		Ui::$pager_widget->config(Ui::$base_url, $itemcount, $itemspp, $pageno, $keepParams);
		Ui::$sort_widget->config(Ui::$base_url, 'desc', $keepParams);

		$orderby = Ui::$sort_widget->orderby();
		if ($orderby) $orderby = ' ORDER BY ' . $orderby;

		$limit = Ui::$pager_widget->limit();
		if ($limit) $limit = ' LIMIT ' . $limit;

		$verwysings = DB::select('view_verwysings WHERE ekspo_id=?' . $orderby . $limit, [$ekspo_id]);
		$ekspos = EksposRepo::kryEkspos();
		$ekspo = EkspoModel::kiesEeen($ekspos, $ekspo_id);

		$opskrif = 'Registrasie Verwysings';

		break;


	default:
		Errors::raise('Invalid Request');
}


$listview_widget = new ListViewWidget();

Scripts::addLocalScripts('var ekspoSel=$("#ekspo_id"); ekspoSel.SumoSelect(); ekspoSel.change(function(){$("#filters-form").submit();return false;});'); // Moet WIDGET word...


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
		.list td { max-width: 100px; }
		.list thead tr { background-color: #005500; }
		.verwyser_col { width: 180px; }
		.vriend1_col { width: 150px; }
		.vriend2_col, .vriend3_col { width: 70px; }
		.epos2_col, .epos3_col { width: 100px; }
	</style>

	<header class="row">
		<h1 class="hc2" title="<?=$opskrif?>"><?=$opskrif?></h1>
		<div class="hc1 min410 right">
			<ul class="nav">
				<li>
					<form method="post">
						<button class="btn btn-action" name="csv" type="submit" value="verwysings">
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

		<?=$listview_widget->render($verwysings, 'list w100', [
      'ekspo_id',
      'besoeker_id',
      'besoekerSelfoon',
      'besoekerTel',
      'besoekerKryNuusbrief',
      'besoekerUnsubscribed'])?>

		<div class="pager right">
			<small>Items: <?=Ui::$pager_widget->offset+1?> tot <?=min(Ui::$pager_widget->offset+Ui::$pager_widget->itemspp, Ui::$pager_widget->itemscount)?> van <?=Ui::$pager_widget->itemscount?></small>
		</div>
	</section>

	<br>

	<footer><?=Ui::footer()?></footer>

<?php include __SNIPPETS__.'/html.tail.snippet.php'; ?>
