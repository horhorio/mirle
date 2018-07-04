<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//echo '<pre>';print_r($arResult);echo '</pre>';
$dir = $APPLICATION->GetCurDir();
?>
<?if (!empty($arResult)):?>
<ul class="footer-menu">
	<?
    foreach($arResult as $arItem){
    ?>
    <li><a <?=($dir!=$arItem["LINK"]) ? "href='".DOMAIN.$arItem["LINK"]."'" : "";?> title="<?=$arItem["PARAMS"][0]?>" class="<?=$arItem["PARAMS"][1]?>"><?=$arItem["TEXT"]?></a></li>
    <?
	}
    ?>
</ul>
<?endif?>