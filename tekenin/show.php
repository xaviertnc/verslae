<?php // verslae/tekenin/show.php - TEKEN IN

include '../app/bootstrap.php';


Ui::init(['base-url' => 'tekenin/']);


class LoginForm {
	public $gebruikernaam = '';
	public $wagwoord = '';
}


switch (Request::$method)
{
	case 'POST':

		do {

			Ui::handle_logout();

			if (Ui::handle_alerts()) { break;}

			// Meer goed..
			$form = unserialize(State::getOld('form'))?:new LoginForm;
			$form->gebruikernaam = Request::get('gebruikernaam');

			State::setOld('form', serialize($form));

			Auth::login($form->gebruikernaam, Request::get('wagwoord'));

		} while (0);

		Redirect::back();

		Errors::raise('Login POST Request Error.');

		break;


	case 'GET':
		$gebruiker = Auth::getAuthUser()?:Auth::getGuestUser();
		$form = unserialize(State::getOld('form'))?:new LoginForm;
		Scripts::addLocalScriptsLate('var $n=$("#username"),$p=$("#passwd"); if($n.val() && !$p.val()) { $p.focus(); } else { $n.focus(); }');
		State::delOld(); // Like "flash" for old INPUT data
		break;


	default:
		Errors::raise('Invalid Request');
}


// TEKEN IN - HTML VIEW ?>
<?php include __SNIPPETS__.'/html.head.snippet.php'; ?>

	<?=Ui::$local_styles_widget?>

	<style>
		.login { background-color: midnightblue; border: 5px solid silver; display: block; margin: 0 auto; padding: 10px; width: 319px; }
		.login input { margin-bottom: 15px; width: 160px; }
		.login label { width: 122px; }
		.login .btn { margin: 10px 0 0; }
	</style>

	<header class="row">
		<h1 class="hc2" title="Teken In">Teken In</h1>
		<div class="hc1 min275 right">
			<ul class="nav">
				<?=Ui::userinfo()?>
			</ul>
		</div>
	</header>

	<?=Ui::supernav(Request::get('ekspo'))?>

	<?=Ui::$alerts_widget?>

	<?=Ui::$flash_widget?>

	<section id="content">
		<br>
		<br>
		<form class="login form" method="post">
			<ul>
				<li>
					<label>Gebruikernaam:&nbsp;</label>
					<input id="username" name="gebruikernaam" type="text" value="<?=$form->gebruikernaam?>">
				</li>
				<li>
					<label>Wagwoord:</label>
					<input id="passwd" name="wagwoord" type="password">
				</li>
				<li class="right">
					<button type="submit" class="btn btn-primary">Teken In</button>&nbsp;
				</li>
			</ul>
			<br>
		</form>
	</section>

	<footer><?=Ui::footer()?></footer>

<?php include __SNIPPETS__.'/html.tail.snippet.php'; ?>
