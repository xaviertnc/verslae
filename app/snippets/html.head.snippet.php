<?php // HTML HEAD SNIPPET - C. Moller - 23 Apr 2016
if (!Request::ajax()): ?>
<!DOCTYPE html>
<html lang="af">

<head>
	<title><?=__APP_TITLE__?></title>
	<base href="<?=__BASE_URL__?>/">
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="favicon.ico" rel="shortcut icon">
	<?=Ui::$global_styles_widget?>
</head>

<body>

<div class="minPgW" id="page">
<?php endif;
