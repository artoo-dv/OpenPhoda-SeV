<?php include('views/_headerTest.php');?>

<?php if ($register->registration_successful) { ?>
<div style="background: #fffacd; padding: 20px; border: 3px solid #ffd700; margin: 5% auto; max-width: 30em; text-align: center; box-shadow: 0 1.5em 1.5em rgba(0, 0, 0, 0.7);">
    <h2 style="color: #d32f2f;">IMPORTANT: Save Your Recovery Code</h2>
    <div style="font-size: 28px; font-weight: bold; color: #000; background: white; padding: 10px; border: 2px solid #333; letter-spacing: 2px;">
        <?php echo htmlspecialchars($register->recovery_code); ?>
    </div>
    <p style="font-size: 16px; margin-top: 15px;">
        <strong>Write this code down and store it safely!</strong><br>
        You will need this code if you ever forget your password.
    </p>
    <p style="color: #d32f2f; font-weight: bold;">
        This code will NOT be shown again!
    </p>
    <p>You will be redirected to the main page in <span id="countdown">30</span> seconds...</p>
</div>
<script>
    var secondsLeft = 30;
    var countdownEl = document.getElementById('countdown');
    var timer = setInterval(function () {
        secondsLeft--;
        countdownEl.textContent = secondsLeft;
        if (secondsLeft <= 0) {
            clearInterval(timer);
            window.location.href = "index.php";
        }
    }, 1000);
</script>
<?php } else { ?>
<?php if ($register->name_taken) { ?>
<script type="text/javascript">alert("This experiment name is taken");</script>
<?php } ?>
<div class="login">
	<p>
	<label for="register">Create new dataset</label>
	<table ><form method="post" action="register.php" name="registerform">

	    <tr><td><label for="exp_name">Experiment name</label></td></tr>
	    <tr><td><input id="exp_name" type="text" pattern="[a-zA-Z0-9]{2,64}" name="exp_name" placeholder="experiment name" required autocomplete="off" /></td></tr>

	    <tr><td><label for="password">Password</label></td></tr>
	    <tr><td><input id="password" type="password" name="password" placeholder="experiment password" pattern=".{6,}" required autocomplete="off" /></td></tr>

    	<tr><td><button class="button" type="submit" name="register" value="register">register</button></td></tr>
	</form>
	</table>
	</p>
	<br>
	<form action="exp.php">
    <button class="button" type="submit" name="" value="">Login</button>
	</form>
</div>
<?php } ?>

<?php include('views/_footer.php'); ?>
