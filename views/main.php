<?php include('_header.php');
?>
<div class="page-card">
<div style="text-align:left;" class="dashboard">
    <div class="menu">
        <div style="float: left;">
            OpenPhoda-SeV
        </div>

        <div style="float: right; font-size: 18px; margin-top: 8px;">
            <strong>Welcome, <?php echo htmlspecialchars($_SESSION['exp_name']); ?></strong><br>
            <a href="password_change.php">Change Password</a> |
            <a href="exp.php?logout=logout">Logout</a>
        </div>

        <div style="clear: both;"></div>
    </div>
</div>

	<div class="sections">
<?php

// load the login class
require_once('classes/Main.php');
// create a Main object.
$main = new Main();

?>

</div>
</div>

<?php include('_footer.php');?>
		
