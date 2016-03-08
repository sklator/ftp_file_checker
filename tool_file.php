<?php

define('PASSWORD', 'azertyuiop');

if( ! empty( $_POST['uptime'] ) ){

	if( ! empty($_POST['password']) && $_POST['password'] == PASSWORD ){

		$exclude = '';
		$uptime = intval($_POST['uptime']);
		$findFolder = $_POST['folder'];
		if( ! empty( $findFolder ) ){
			$findFolder = './' . trim(trim($findFolder), '/');
		}
		else {
			$findFolder = '.';
		}

		if( ! empty( $_POST['exclude'] ) ){

			$excludeRows = explode("\n", $_POST['exclude']);
			$exclude = ' \( ';

			foreach( $excludeRows as $path ){
				if (empty( $path )){
					continue;
				}

				$path = './' . trim(trim($path), '/');
				$exclude .= '-path '. $path . ' -o ';
			}

			$exclude = trim($exclude, ' -o ') . ' ';

			$exclude .= '\) -prune -o';
		}

		if( ! is_int($uptime) || $uptime <= 0 || $uptime >= 999 ){
			$uptime = 1;
		}

		$executed = 'find '. $findFolder .' '. $exclude .' -mtime -'. $uptime .' -ls';
		$results = shell_exec($executed);
	}
	else {
		$notification = array(
			'success' => 'danger',
			'message' => '<b>Erreur :</b> Mot de passe incorrect'
		);
	}
}

?>
<html>
<head>
	<title>Check Fichiers modifiés</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<style>
		h1 {
			font-weight:bold;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Check Fichiers modifiés</h1>

			<?php if( ! empty($notification) ): ?>
			<div class="alert alert-<?php echo $notification['success']; ?>" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<?php echo $notification['message']; ?>
			</div>
			<?php endif; ?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<div class="form-group">
					<label for="uptime">Nombre de jours à checker</label>
					<input type="number" id="uptime" name="uptime" class="form-control" value="<?php echo ( ! empty($uptime) ) ? $uptime : ''; ?>">
				</div>
				<div class="form-group">
					<label for="folder">Chercher dans :</label>
					<input type="text" id="folder" name="folder" class="form-control" value="<?php echo ( ! empty($findFolder) ) ? $findFolder : ''; ?>">
				</div>
				<div class="form-group">
					<label for="exclude">Chemins à exclure</label>
					<textarea id="exclude" name="exclude" class="form-control"><?php echo ( ! empty($_POST['exclude']) ) ? $_POST['exclude'] : ''; ?></textarea>
					<p class="help-block">1 chemin par ligne</p>
				</div>
				<div class="form-group">
					<label for="exclude">Mot de passe</label>
					<input type="password" id="password" name="password" class="form-control" value="<?php echo ( ! empty($_POST['password']) ) ? $_POST['password'] : ''; ?>">
				</div>
				<button type="submit" class="btn btn-success btn-lg">Envoyer</button>
			</form>

			<?php if( ! empty($results) ): ?>
			<h2>Fichiers modifiés depuis <b><?php echo $uptime; ?></b> jours :</h2>
			<pre><?php print_r($results); ?></pre>
			<?php endif; ?>

			<?php if( ! empty($executed) ): ?>
			<h2>Commande executée :</h2>
			<pre><?php echo $executed; ?></pre>
			<?php endif; ?>
		</div>
	</div>
</div>
</body>
</html>


