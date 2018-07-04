<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//echo '<pre>'; print_r($arResult); echo '</pre>';

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<div class="paginations">
<ul class="pagination_list">
	
    <? while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
		<? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
        <li class="pagination_item  pagination_item__active"><a class="pagination_link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span><?=$arResult["nStartPage"]?></span></a></li>
        <? elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
        <li class="pagination_item"><a class="pagination_link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span><?=$arResult["nStartPage"]?></span></a></li>
        <? else:?>
        <li class="pagination_item"><a class="pagination_link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><span><?=$arResult["nStartPage"]?></span></a></li>
        <? endif?>
        <? $arResult["nStartPage"]++?>
    <? endwhile?>
</ul>
</div>
<?
/*if($arResult[NavPageNomer] == 1){
	$APPLICATION->AddHeadString("<link rel='next' href='".DOMAIN.$arResult["sUrlPath"]."?".$strNavQueryString."PAGEN_".$arResult["NavNum"]."=2' />", true);
}else{
	if($_GET["PAGEN_".$arResult["NavNum"]] == 2){
        $next = $arResult[NavPageNomer]+1;
		$prev = $arResult[NavPageNomer]-1;
		$nEndPage = $arResult[nEndPage]+1;
		if($next < $nEndPage)		
                       $APPLICATION->AddHeadString("<link rel='next' href='".DOMAIN.$arResult["sUrlPath"]."?".$strNavQueryString."PAGEN_".$arResult["NavNum"]."=".($arResult[NavPageNomer]+1)."' />", true);
		$APPLICATION->AddHeadString("<link rel='prev' href='".DOMAIN.$arResult["sUrlPath"].$strNavQueryStringFull."' />", true);
	}else{
		$next = $arResult[NavPageNomer]+1;
		$prev = $arResult[NavPageNomer]-1;
		$nEndPage = $arResult[nEndPage]+1;
		if($next < $nEndPage)
			$APPLICATION->AddHeadString("<link rel='next' href='".DOMAIN.$arResult["sUrlPath"]."?".$strNavQueryString."PAGEN_".$arResult["NavNum"]."=".$next."' />", true);
		$APPLICATION->AddHeadString("<link rel='prev' href='".DOMAIN.$arResult["sUrlPath"]."?".$strNavQueryString."PAGEN_".$arResult["NavNum"]."=".$prev."' />", true);
	}
}*/
//echo '<pre>'; print_r($arResult); echo '</pre>';
?>