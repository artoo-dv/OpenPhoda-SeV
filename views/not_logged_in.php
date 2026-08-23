<?php include('views/_headerTest.php');?>

<?php if (!empty($login->login_error)) { ?>
<script type="text/javascript">
    alert(<?php echo json_encode($login->login_error, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>);
</script>
<?php } ?>

<div class="login">
	<p>
	<table>
	<form method="post" action="exp.php" name="exp">
		<tr><td><label for="Access">open dataset</label></td></tr>
		<tr><td><input id="exp_name" type="text" name="exp_name" autocomplete="off" placeholder="experiment name" required /></td></tr>
		<tr><td><input id="exp_password" type="password" name="exp_password" placeholder="password" autocomplete="off" required ></td></tr>
		<tr><td><button class="button" type="submit" name="login" value=1>login</button></td></tr>
	</form>
	<form action="register.php">
	<tr><td></td></tr>
        <tr><td><button class="button" type="submit" name="register" value="register">register</button></td></tr>
	</form>
       	<tr><td style="text-align: right; padding-top: 10px;">
		<a href="recovery.php" style="color: #007cba; text-decoration: none; font-size: 12px;">password recovery</a>
	</td></tr>

	</table>

</div>
<?php include('views/_footer.php'); ?>
