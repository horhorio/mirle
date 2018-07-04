<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//echo '<pre>'; print_r($arResult); echo '</pre>';

$TIMESTAMP_X = $arResult[TIMESTAMP_X];
if($TIMESTAMP_X){
	//[TIMESTAMP_X] => 26.04.2017 09:41:38
	$date = new DateTime($TIMESTAMP_X);
	
	$LastModified_unix = $date->getTimestamp(); // время последнего изменения страницы
	$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
	$IfModifiedSince = false;
	if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
		$IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));  
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		$IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
	if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
		exit;
	}
	header('Last-Modified: '. $LastModified);
}
?>