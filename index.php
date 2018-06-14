<?php
	if (isset($_GET['direccion']))
	{
		$direccion = $_GET['direccion'];
		echo "Se ha ingresado 1 dirección: " . $direccion;

		$google_maps_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($direccion) ."&key=AIzaSyApj6gLFFjVQ2sFXJ6k-U1yyiCfvBchybI";
		$google_maps_json = file_get_contents($google_maps_url);
		$google_maps_array = json_decode($google_maps_json, true);
		$lat = $google_maps_array["results"][0]["geometry"]["location"]["lat"];
		$lng = $google_maps_array["results"][0]["geometry"]["location"]["lng"];
		echo $lat . "  " . $lng;

		$wikimedia_url = "https://commons.wikimedia.org/w/api.php?format=json&action=query&generator=geosearch&ggsprimary=all&ggsnamespace=6&ggsradius=500&ggscoord=51.5|11.95&ggslimit=1&prop=imageinfo&iilimit=1&iiprop=url&iiurlwidth=200&iiurlheight=200";
		$wikimedia_json = file_get_contents($wikimedia_url);
		$wikimedia_array = json_decode($wikimedia_json, true);
		var_dump($wikimedia_array["query"]["pages"]["28971703"]["imageinfo"]);
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Ejemplo</title>
</head>
<body>
	<form action="" method="GET">
		<label for="direccion">Ingrese dirección:</label>
		<input type="text" name="direccion">

		<button type="submit">Consultar</button>
	</form>
</body>
</html>