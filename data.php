<?php
		$maximos = array(500, 1000, 2000, 5000, 10000);

		foreach ($maximos as $max) {
			$salida = "[";
			for ($x = 0; $x<$max; $x++ ) {
				$salida .= "{
						code: 000$x,
						name: 'Hotel$x',
						direction: 'Calle$x',
						telef: 'Telf$x',
						geo: {
							lat: $x$x,
							lng: $x$x
						}
					}\n";
				$salida .= ($x + 1 < $max)?",":"";
			}
			$salida .= "]";
			file_put_contents($max.".json", $salida);
			$salida = "";
			for ($x = 0; $x<$max; $x++ ) {
				$salida .= "000$x:Hotel$x:Calle$x:Telf$x:$x$x:$x$x";
				$salida .= ($x + 1 < $max)?"|":"";
			}

			file_put_contents($max.".txt", $salida);

			$salida = '<?xml version="1.0" encoding="UTF-8"?'.'><root>'."\n";
			for ($x = 0; $x<$max; $x++ ) {
				$salida .= "<hotel>
								<code>000$x</code>
								<name>Hotel$x</name>
								<direction>Calle$x</direction>
								<telef>Telf$x</telef>
								<geo>
									<lat>$x$x</lat>
									<lng>$x$x</lng>
								</geo>
							</hotel>";
			}
			$salida .= '</root>';
			file_put_contents($max.".xml", $salida);
		}

	?>