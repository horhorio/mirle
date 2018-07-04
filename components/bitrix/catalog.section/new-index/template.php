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
?>
<?
//echo '<pre>';print_r($arResult[ITEMS]);echo '</pre>';
//if(!empty($arResult[ITEMS])){
?>
<div class="index-advertising">
	<?
    foreach($arResult[ITEMS] as $arElement){
		$img = CFile::ResizeImageGet($arElement['DETAIL_PICTURE']['ID'], array('width'=>650, 'height'=>'320'), BX_RESIZE_IMAGE_EXACT, true);
	?>
    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12'>
        <a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" class="advert">
        	<div class="img">
            	<span>Посмотреть сейчас</span>
                <img src="<?=DOMAIN.$img[src]?>" alt="<?=$arElement['NAME']?>" class="responsive-img">
            </div>
            <p><?=$arElement['NAME']?></p>
        </a>
    </div>
    <?
	}
	?>
    <div class="clear"></div>
</div>
<?
//}
?>
