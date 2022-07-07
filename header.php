<?
	if(!isset($_SESSION['name']))
		$_SESSION['name'] = "File Manager"
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top mb-5" style="background-image: url('img/header.png');">
	<div class="container">
		<a class="navbar-brand" href="/"><?php echo "PHP File Browser"; ?></a>
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<button class="nav-link btn-warning form-control" type="submit">Home</button>
				</li>
			</ul>
		</div>
	</div>
</nav>