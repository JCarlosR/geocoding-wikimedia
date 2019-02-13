<?php

$apiKey = 'REPLACE_WITH_YOUR_OWN_G_MAPS_GEOCODING_KEY';

$getLatAndLongFromAddress = function ($address) use ($apiKey)
{
	$google_maps_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) ."&key=$apiKey";
	$google_maps_json = file_get_contents($google_maps_url);
	$google_maps_array = json_decode($google_maps_json, true);
	// var_dump($google_maps_array);
	$results = $google_maps_array["results"];
	
	if (sizeof($results) > 0) {
		$location = $google_maps_array["results"][0]["geometry"]["location"];
		$lat = $location["lat"];
		$lng = $location["lng"];
		return compact('lat', 'lng');
	} else {
		return null;
	}
	
	/*
	$arr = [];
	$arr['lat'] = $lat;
	$arr['lng'] = $lng;
	return $arr;
	*/
};

function showImageFromPage($page)
{
	echo '<p>' . $page['title'] . '</p>';
	$image = $page['imageinfo'][0];

	$width = $image['thumbwidth'];
	$height = $image['thumbheight'];
	$thumbUrl = $image['thumburl'];
	$url = $image['url'];

	echo "<a href='$url' target='_blank'>";
	echo "<img src='$thumbUrl' width='$width' height = '$height'>";
	echo "</a>";
}

function getImagesFromCoords($coords)
{
	$ggscoord = $coords['lat'] .'|' . $coords['lng']; // 51.5|11.95;
	$wikimediaUrl = "https://commons.wikimedia.org/w/api.php?format=json&action=query&generator=geosearch&ggsprimary=all&ggsnamespace=6&ggsradius=500&ggscoord=$ggscoord&ggslimit=1&prop=imageinfo&iilimit=1&iiprop=url&iiurlwidth=200&iiurlheight=200";
	$wikimediaJson = file_get_contents($wikimediaUrl);
	$wikimediaArr = json_decode($wikimediaJson, true);

	echo "<p>URL consultada: $wikimediaUrl</p>";

	if (array_key_exists('query', $wikimediaArr)) {
		$pages = $wikimediaArr['query']['pages'];
		foreach ($pages as $page) {
			showImageFromPage($page);
		}
	} else {
		echo '<p>No se encontraron imágenes en la API de WikiMedia.</p>';
	}
}

if (isset($_GET['direccion']))
{
	$direccion = $_GET['direccion'];
	echo "Se ha ingresado esta dirección: " . $direccion;
	$coords = $getLatAndLongFromAddress($direccion);
	
	if ($coords)
		getImagesFromCoords($coords);
	else
		echo "No se encontraron resultados (en Google Maps Geocoding).";
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

	<?php foreach ($pages as $key => $page): ?>
		<img src="" width="" height="">
	<?php endforeach; ?>
</body>
</html>
