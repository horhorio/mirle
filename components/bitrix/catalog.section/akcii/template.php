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
if(empty($arResult[ITEMS])){
	echo "<div class='alert_warning margin-30-15'>К сожалению, акционных товаров пока нет, попробуйте зайти позже.</div>";
}else{
?>
<div class="goods">
<?
foreach($arResult[ITEMS] as $arElement){
?>
<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6'>
	<div class="product shadow">
		<div class="product-hover"></div>
		<?
		if($arElement[PROPERTIES][AKCII][VALUE_XML_ID])
			echo "<div class='status-goods ".$arElement[PROPERTIES][AKCII][VALUE_XML_ID]."'><span>".$arElement[PROPERTIES][AKCII][VALUE]."</span><i></i></div>";
		?>
		<div class="product-bg">
			<div class="product-img">
				<?
                foreach($_SESSION[PRODUCT_VIEW] as $idItem){
                    if($idItem == $arElement[ID]){
                        echo '<div class="view">Просмотрен</div>';
                    }
                }
                ?>
                <a class="quick-view" href="javascript:void(0);" onClick='elementSynopsis(<?=$arElement[ID]?>)' id="q-<?=$arElement[ID]?>" rel="nofollow">Быстрый просмотр</a>
                <a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[PREVIEW_PICTURE][TITLE]))?>">
                <?
                $grayscale = false;
                if(($arElement[PREVIEW_PICTURE][SRC])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arElement[PREVIEW_PICTURE][SRC]))){
                    if($arElement[CATALOG_QUANTITY] < 1)$grayscale = ' grayscale';
					echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/bg.jpg' data-original='".DOMAIN.$arElement[PREVIEW_PICTURE][SRC]."' alt='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[PREVIEW_PICTURE][ALT]))."' class='responsive-img".$grayscale."'/>";
                }else{
                    echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
                }
                ?>
                
                </a>
            </div>
			<a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
			<?
			foreach($arElement[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE] as $b){
				$MANUFACTURER = $b[NAME];
			}
			?>
			<p class="collection">« <?=$MANUFACTURER?> »</p>
			<p class="name" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>"><?=mb_strimwidth($arElement[NAME], 0, 65, "... ",'utf-8')?></p>
			</a>
            <?
			if($arElement[PROPERTIES][SIZE][VALUE])
				echo "<p class='size'>".$arElement[PROPERTIES][SIZE][VALUE]."</p>";
			?>
			<div class="price">
				<?
				if($arElement[PRICES][OLD][PRINT_VALUE])
					echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][OLD][PRINT_VALUE])."</span>";
				?>
				<span class="current"><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][BASE][PRINT_VALUE])?></span>
			</div>
			<div class="btn-box">
				<?
				if($arElement[CATALOG_QUANTITY] > 0){
					$inBasket = false;
					foreach($_SESSION[PRODUCT_ID_IN_BASKET] as $idItemBasket){
						if($idItemBasket == $arElement[ID]){
							$inBasket = "Уже в корзине";
						}
					}
				?>
				<div class="add-buy">
					<a href="javascript:void(0);" rel="nofollow" id="btn-add-<?=$arElement[ID]?>" onClick="yaCounter43070549.reachGoal('ADD_BASKET'); basketElementAdd(<?=$arElement[ID]?>,1); return false;"><?=($inBasket) ? $inBasket : "В корзину";?></a>
				</div>
				<?
				}else{
				?>
				<div class="not-available">
					<a href="javascript:void(0);" rel="nofollow" id="btn-av-<?=$arElement[ID]?>" onClick='subscribeElementAdd(<?=$arElement[ID]?>)'>Оповестить</a>
				</div>
				<?
				}
				?>
			</div>
		</div>
	</div>
</div>
<?
}
echo '<div class="clear"></div>';
echo $arResult["NAV_STRING"];
?>
<div class="clear"></div>
</div>
<?
}//if(empty($arResult[ITEMS])){
?>

