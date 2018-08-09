<?php // HTML TAIL SNIPPET - C. Moller - 23 Apr 2016 ?>
	<?=Ui::$popup_widget?>

	<?=Ui::$local_scripts_widget?>
<?php if (!Request::ajax()): ?>
</div> <!-- end: #page -->

<?=Ui::$global_scripts_widget?>

<script src="js/app.js"></script>

</body>

</html>
<?php endif;?>
