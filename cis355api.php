<?php 
/*
	filename 	: cis355api.php
	author   	: Anthony Galea
	course   	: cis355 (winter2020)
	description	: demonstrate JSON API functions
				  return number of new covid19 cases
	input    	: https://api.covid19api.com/summary
	functions   : main()
	                curl_get_contents()
*/

main();

#-----------------------------------------------------------------------------
# FUNCTIONS
#-----------------------------------------------------------------------------
function main () {
	
	$apiCall = 'https://api.covid19api.com/summary';
	// line below stopped working on CSIS server
	// $json_string = file_get_contents($apiCall); 
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);
	$countries = Array();
	foreach ($obj->Countries as $country) {
		array_push($countries, $country);
	}
	$top10 = Array();
	for ($x=0; $x < 10; $x++) {
		$maxCountry = $countries[0];
		foreach($countries as $country) {
			if ($country->TotalDeaths > $maxCountry->TotalDeaths) {
				$in=false;
				foreach($top10 as $t1c) {
					if ($t1c->Country == $country->Country) {
						$in=true;
					}
				}
				if (!$in) {
					$maxCountry=$country;
					//echo $country->Country;
				}
			}
		}
		array_push($top10, $maxCountry);
	}
	//$data = $obj->Countries->NewConfirmed;

	// echo html head section
	echo '<html>';
	echo '<head>';
	echo '	<link rel="icon" href="img/cardinal_logo.png" type="image/png" />';
	echo '</head>';
	
	// open html body section
//	echo '<body onload="loadDoc()">';
	echo '<body>';
	echo '<div>';
	$myObjString = '<a href="https://github.com/anthonycgalea/CIS355-Public">Github</a>' ;
	echo $myObjString;
	echo '</div>';
	
	echo '<div>';
	echo '<table>';
	echo '<tr>';
	echo '<th> Country </th>';
	echo '<th> Deaths </th>';
	echo '</tr>';
	for ($x = 0; $x < 10; $x++) {
	echo '<tr>';
	echo '<th>'. $top10[$x]->Country.'</th>';
	echo '<th> '.$top10[$x]->TotalDeaths.' </th>';
	echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
	
	echo '<div id="demo">';
	echo '</div>';
/*	echo '<script>';
	echo '
		var country_usa;
		function loadDoc() {
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  country_usa = JSON.parse(this.responseText).Global.NewConfirmed;
			  document.getElementById("demo").innerHTML = country_usa;
			 
			}
		  };
		  var api = "https://api.covid19api.com/summary";
		  xhttp.open("GET", api, true);
		  xhttp.send();
		}
	';
	echo '</script>';
	*/
	// close html body section
	echo '</body>';
	echo '</html>';
}


#-----------------------------------------------------------------------------
// read data from a URL into a string
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>












