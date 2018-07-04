<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//echo "<pre>"; print_r($arResult); echo "</pre>";
?>
<div class='information wrapp'>
    <h1 class="h1-head"><span class="before"></span><?=$arResult['NAME']?><span class="after"></span></h1>
<?
if(empty($arResult["ITEMS"])){
	echo "<div class='manufactures-list'><div class='alert_warning'>Список <strong>".$arResult['NAME']."</strong> временно пуст!</div></div>";
}else{
	echo "<div class='manufactures-list'>";
	foreach($arResult["ITEMS"] as $arItem){
	?>
	<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="brands-item shadow">
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 brands-pic">
                <a href="<?=DOMAIN.$arItem['DETAIL_PAGE_URL']?>">
                <?
                if(($arItem['PREVIEW_PICTURE']['SRC'])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arItem['PREVIEW_PICTURE']['SRC'])))
                    echo"<img src='".DOMAIN.$arItem['PREVIEW_PICTURE']['SRC']."' alt='".$arItem['PREVIEW_PICTURE'][ALT]."' class='responsive-img'/>";
                else
                    echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";	
                ?>
                </a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 brands-info">
                <div class="brands-info__head">
                    <p>Бренд: <strong><?=$arItem['NAME']?></strong></p>
                    <?
                    if($arItem['DISPLAY_PROPERTIES']['SITE']['VALUE'])
                        echo "<p>".$arItem['DISPLAY_PROPERTIES']['SITE']['NAME'].":  <a href='".DOMAIN."/go.php?url=".$arItem['DISPLAY_PROPERTIES']['SITE']['VALUE']."' class='site' target='_blank'>".preg_replace('#https?://#i', '', $arItem['DISPLAY_PROPERTIES']['SITE']['VALUE'])."</a></p>";
                        
                    if($arItem['DISPLAY_PROPERTIES']['COUNTRY']['VALUE'])
                        echo "<p>".$arItem['DISPLAY_PROPERTIES']['COUNTRY']['NAME'].": <strong>".$arItem['DISPLAY_PROPERTIES']['COUNTRY']['VALUE']."</strong></p>";
                        
                    if($arItem['DISPLAY_PROPERTIES']['CITY']['VALUE'])
                        echo "<p>".$arItem['DISPLAY_PROPERTIES']['CITY']['NAME'].": <strong>".$arItem['DISPLAY_PROPERTIES']['CITY']['VALUE']."</strong></p>";
                    ?>
                </div>
                <div class="brands-info__desc"><p><?=$arItem['PREVIEW_TEXT']?></p></div>
                <div class="brands-link">
                    <a href="<?=DOMAIN.$arItem['DETAIL_PAGE_URL']?>" class="more" title="<?=$arItem['PREVIEW_PICTURE'][TITLE]?>">Подробнее...</a>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
	<?
	}
	echo "</div>";
	echo '<div class="clear"></div>';
	echo $arResult["NAV_STRING"];
}
?>
</div>