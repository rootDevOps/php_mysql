<?php
require_once('config.php');

$conn = @new Boletin();
$result = $conn->selectCorreos();
$boletin = "";

foreach($result as $p){
		$posic = strpos($p->body, ":");
		$content .= substr($p->body, $posic + 2);
	}
//config
$namefile = "boletin.txt";

//save file
$file = fopen($namefile, "w") or die("Unable to open file!");
fwrite($file, $content);
fclose($file);

//header download
header("Content-Disposition: attachment; filename=\"" . $namefile . "\"");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");

echo $content;

class Boletin{
	protected $link;
	function __construct(){ $this->link = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);	}
	function __destruct(){	$link = $this->link; mysqli_close($link); }
	
	function selectCorreos(){
		$link = $this->link;
		if (mysqli_connect_errno()){ printf("Falló la conexión: %s\n", mysqli_connect_error()); exit(); }
		$query = " SELECT submission_id,submitted_when,body FROM wb_mod_form_submissions; ";
		if ($result = mysqli_query($link,$query)){ $array = array(); while ($row = mysqli_fetch_object($result)){ $array[] = $row; } return $array; }
	}
}
?>