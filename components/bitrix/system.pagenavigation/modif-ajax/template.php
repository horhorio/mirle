<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//echo '<pre>'; print_r($arResult); echo '</pre>';

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

//$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
//$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

//echo modifAjaxPagen('PAGEN_'.$arResult["NavNum"]).$arResult["nStartPage"];
?>

<div class="paginations">
<ul class="pagination_list">
	
    <? while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
    <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
    <li class="pagination_item  pagination_item__active"><a class="pagination_link" href="javascript:void(0)" onClick="javascript:modifAjaxPagen('<?="PAGEN_".$arResult[NavNum]?>',<?=($arResult["nStartPage"])?$arResult["nStartPage"]:1?>)"><span><?=$arResult["nStartPage"]?></span></a></li>
    <? elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
    <li class="pagination_item"><a class="pagination_link" href="javascript:void(0)" onClick="javascript:modifAjaxPagen('<?="PAGEN_".$arResult[NavNum]?>',<?=($arResult["nStartPage"])?$arResult["nStartPage"]:1?>)"><span><?=$arResult["nStartPage"]?></span></a></li>
    <? else:?>
    <li class="pagination_item"><a class="pagination_link" href="javascript:void(0)" onClick="javascript:modifAjaxPagen('<?="PAGEN_".$arResult[NavNum]?>',<?=($arResult["nStartPage"])?$arResult["nStartPage"]:1?>)"><span><?=$arResult["nStartPage"]?></span></a></li>
    <? endif?>
    <? $arResult["nStartPage"]++?>
    <? endwhile?>
    
<?
//echo '<pre>'; print_r($arResult); echo '</pre>';
?>
</ul>
</div>