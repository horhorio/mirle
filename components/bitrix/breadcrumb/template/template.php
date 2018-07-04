<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";
$strReturn = '<div class="brbs">';
$strReturn .= '<ul class="breadcrumbs wrapp">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
	//echo '<pre>'; print_r($arResult); echo '</pre>';
	if($index > 0)
		$strReturn .= '<li class="breadcrumbs_link"></li>';
		//$strReturn .= ' &rarr; ';

	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if($arResult[$index]["LINK"] <> "")
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'">'.$title.'</a></li>';
	else
		$strReturn .= '<li><strong>'.$title.'</strong></li>';
}
$strReturn .= '</ul>';
$strReturn .= '</div>';
return $strReturn;
?>
