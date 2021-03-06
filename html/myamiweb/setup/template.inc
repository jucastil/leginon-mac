<?php
define('SETUP_CONFIG', "Web Tools Setup Wizard");
define('DB_INITIALIZATION', "Database Initialization");

class template{
	
	function wizardHeader($header, $title, $cssfile = "../css/setup.css"){

		$html = "<html><head>\n";
		$html .= "<title>$header</title>\n";
		$html .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
		$html .= "<link href='$cssfile' media='all' rel='stylesheet' type='text/css' />\n";
		
		$html .= "</head><body>\n";
		$html .= "<div id='pageWrapper'>\n";
		$html .= "<div>\n";
		$html .= "<table><tr><td width='130px' valign='top' style='padding:5px 0px 10px 15px'><img src='../img/nramm_logo.jpg'></td>\n";
		$html .= "<td id='header'width=100%><p>$title</p></td>";
		$html .= "<td width='80px' align='center' style='padding:0px 20px 0px 20px'><img src='../img/NCRR.jpg'></td></tr></table>\n";
		$html .= "</div>\n";
		$html .= "<div id='nav'><h3>". $header . "</h3></div>\n";
		$html .= "<div id='main'>\n";
		
		echo $html;
	}
	
	function wizardFooter(){
		$html = "</div>\n";
		$html .= "<div id='footer'>\n";
		$html .= "<p>New York Structural Biology Center. National Resource for "; 
		$html .= "Automated Molecular Microscopy (<a href='http://nramm.nysbc.org'>NRAMM</a>)</p>\n";
		$html .= "</div></div></body></html>\n";
		
		echo $html;
	}

	function textEntry($title, $fieldname, $example='', $size=50){
		$value = $_POST[$fieldname] ? $_POST[$fieldname] : '';
		$html .= "<h3>$title:</h3>\n";
		if ($example)
			$html .= "<p>example: <i>$example</i></p>\n";
		$html .= "<input type='text' size='$size' name='$fieldname' value='$value'/>\n";
		$html .= "<br/><br/>\n";
		echo $html;
	}

}

?>
