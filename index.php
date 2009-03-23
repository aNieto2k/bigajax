<?php
	$items = intval($_GET["items"]);
	$maximos = array(500, 1000, 2000, 5000, 10000);	
	if (!in_array($items, $maximos)) $items = 500;
	
	$type = $_GET["type"];	
	if (!eregi('(xml|json|txt)', $type)) $type = 'txt';
	
	$file = $items.".".$type;
	
?><html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="suggest.js"></script>
		<script type="text/javascript">
		
		function pintaHotel(hotel){
			var salida = '<dl>';
			for (var x in hotel) {
				var dd = (hotel[x] instanceof Object)?pintaHotel(hotel[x]):hotel[x];
				salida += '<dt>' + x + '</dt><dd>' + dd + '</dd>';
			}
			salida += '</dt>';
			return salida;
		}

		function XML(){
			var time1 = new Date().getTime();
			$.get("<?=$file?>", function(data){
				var time2 = new Date().getTime();
				
				
				window.hotelDescription = data, hotelList = [];
				var root = data.getElementsByTagName("root");
				var data = data.getElementsByTagName("hotel");
				for (var x in data) {
					var hotel = data[x];
					if (!hotel.getElementsByTagName) continue;
					hotelList.push(hotel.getElementsByTagName("name")[0].firstChild.data);
					
						// Descripción de cada hotel
						hotelDescription[hotel.getElementsByTagName("name")[0].firstChild.data] = {
							code: hotel.getElementsByTagName("code")[0].firstChild.data,
							name: hotel.getElementsByTagName("name")[0].firstChild.data,
							direction: hotel.getElementsByTagName("direction")[0].firstChild.data,
							telf: hotel.getElementsByTagName("telef")[0].firstChild.data,
							geo: {
								lat: hotel.getElementsByTagName("lat")[0].firstChild.data,
								lng: hotel.getElementsByTagName("lng")[0].firstChild.data
							}
						};
					
				}
				$("#suggest").autocompleteArray(hotelList, {
					onSelectItem: function(item){
						var info = hotelDescription[$(item).val()];
						$("#results").html(pintaHotel(info));
					}
				});
				$("#results").html("Carga: " + ((new Date().getTime() - time1) - (new Date().getTime() - time2))  + "ms.<br />Proceso: " + (new Date().getTime() - time2) + "ms.<br />Items: " + hotelList.length);
			});
		}

		function JSON(){
				var time1 = new Date().getTime();
				$.getJSON("<?=$file?>", function(data){
					var time2 = new Date().getTime();
					
					window.hotelDescription = data, hotelList = [];
					
					for (var x in data) hotelList.push(data[x].name);
					
					function pintaHotel(hotel){
						var salida = '<dl>';
						for (var x in hotel) {
							var dd = (hotel[x] instanceof Object)?pintaHotel(hotel[x]):hotel[x];
							salida += '<dt>' + x + '</dt><dd>' + dd + '</dd>';
						}
						salida += '</dt>';
						return salida;
					}
					
					$("#suggest").autocompleteArray(hotelList, {
						onSelectItem: function(item){
							var info = hotelDescription[$(item).val()];
							$("#results").html(pintaHotel(info));
						}
					});
					$("#results").html("Carga: " + ((new Date().getTime() - time1) - (new Date().getTime() - time2))  + "ms.<br />Proceso: " + (new Date().getTime() - time2) + "ms.<br />Items: " + hotelList.length);
				});
					
		}
		
		function TXT(){
			var time1 = new Date().getTime();
			$.get("<?=$file?>", function(data){
				var time2 = new Date().getTime();
				
				var tmp = data.split("|");
				var hotelList = [];
				window.hotelDescription = [];
				for (var x = 0, len = tmp.length; x<len; x++) {
					var hotel = tmp[x].split(":");
					
					// Listado de hoteles
					hotelList.push(hotel[1]);
					
					// Descripción de cada hotel
					hotelDescription[hotel[1]] = {
						code: hotel[0],
						name: hotel[1],
						direction: hotel[2],
						telf: hotel[3],
						geo: {
							lat: hotel[4],
							lng: hotel[5]
						}
					};
				}
				
				$("#suggest").autocompleteArray(hotelList, {
					onSelectItem: function(item){
						var info = hotelDescription[$(item).val()];
						$("#results").html(pintaHotel(info));
					}
				});
				$("#results").html("Time1: " + ((new Date().getTime() - time1) - (new Date().getTime() - time2))  + "ms.\nTime2: " + (new Date().getTime() - time2) + "ms.\nHoteles: " + hotelList.length);
			});
		}
		
		$(document).ready(function(){
			$("#page a.charge").bind("click", function(){
				<?php echo strtoupper($type); ?>();
			});
		});
		</script>
		<link href="style.css" rel="stylesheet" media="all" />
	</head>
	<body>
		<div id="page">
			<div id="header">
				<h1>bigAjax</h1>
				<h2>Pruebas de rendimiento de ajax</h2>
			</div>
			<p>
				Selecciona un número de items y el formato en el que quieres que se carguen.
			</p>
			<ul id="options">
				<?php foreach ($maximos as $item) :?>
					<li class="subitems">
						<h3><a href="#"><?=$item?></a></h3>
						<div><a href="?type=xml&amp;items=<?=$item?>">XML</a> / <a href="?type=json&amp;items=<?=$item?>">JSON</a> / <a href="?type=txt&amp;items=<?=$item?>">TXT</a>
						</div>
					</li>
				<?php endforeach;?>
			</ul>
			<div id="content">
				<h2>Has seleccionado <?=$items?> en formato <?=$type?>.</h2>
				<a class="charge" href="#page">Cargar</a>
				<input id="suggest" name="suggest" />
			</div>
			<div id="results"></div>
		</div>
	</body>
</html>