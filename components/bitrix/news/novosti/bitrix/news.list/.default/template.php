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
//echo "<pre>"; print_r($arResult["ITEMS"]); echo "</pre>";
?>
<div class='wrapp'>
    <div class='news'>
    	<h1 class="h1-head"><span class="before"></span>Новости интернет-магазина<span class="after"></span></h1>
        <div class="news-list">
        <?
        if(empty($arResult["ITEMS"])){
            echo "<div class='alert_warning margin-30-15'>К сожалению, новостей о мероприятиях магазина пока нет.</div>";
        }else{
            foreach($arResult["ITEMS"] as $arElement){
        ?>
            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12' id="news-<?=$arElement[ID]?>">
                <div class="element shadow">
                    <div class='top'>
                        <div class='box-name'>Новость: <span class="date"><?=$arElement[DISPLAY_ACTIVE_FROM]?> г.</span></div>
                    </div>
                    <div class='box-title'><strong><?=$arElement[NAME]?></strong></div>
                    <div class='cont-mod'>
                        <div class='picture'>
                            <?
                            foreach($_SESSION[NEWS_VIEW] as $idItem){
                                if($idItem == $arElement[ID]){
                                    echo '<div class="view">Просмотрена</div>';
                                }
                            }
                            ?>
                            <a href="<?=$arElement[DETAIL_PAGE_URL]?>">
                            <?
                            if(($arElement[PREVIEW_PICTURE][ID])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arElement[PREVIEW_PICTURE][SRC]))){
                                echo"<img src='".DOMAIN.$arElement[PREVIEW_PICTURE][SRC]."' title='".$arElement[PREVIEW_PICTURE][TITLE]."' class='responsive-img'/>";
                            }else{
                                echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
                            }
                            ?>
                            </a>
                        </div>
                        <div class='property'>
                            <p><?=mb_strimwidth($arElement[PREVIEW_TEXT], 0, 180, "... ", "utf-8")?></p>
                            <a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="more"><strong>Подробнее...</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        <?
            }
            echo "<div class='clear'></div>";
            echo $arResult["NAV_STRING"];
        }
        ?>
        </div>
	</div>
</div>