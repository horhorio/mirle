<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//echo '<pre>';print_r($arResult);echo '</pre>';
$dir = $APPLICATION->GetCurDir();
?>
<?if (!empty($arResult)):?>
<ul class="personal-menu">
	<?
    foreach($arResult as $arItem){
    ?>
    <li class="col-sm-3 col-xs-12"><a <?=($dir!=$arItem["LINK"]) ? "href='".DOMAIN.$arItem["LINK"]."'" : "";?>  <?=($arItem[SELECTED])?" class='act'":""?>title="<?=$arItem["PARAMS"][0]?>" class="<?=$arItem["PARAMS"][1]?>"><i class="glyphicon <?=$arItem["PARAMS"][2]?>"></i><?=$arItem["TEXT"]?></a></li>
    <?
	}
    ?>
</ul>
<div class="border-line"></div>
<?endif?>