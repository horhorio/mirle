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
$_SESSION[NEWS_VIEW][] = $arResult[ID];
$_SESSION[NEWS_VIEW] = array_unique($_SESSION[NEWS_VIEW]);
?>
<div class='wrapp'>
    <div class='news'>
    	<h1 class="h1-head" title="<?=$arResult[NAME]?>"><span class="before"></span><?=mb_strimwidth($arResult[NAME], 0, 50, "... ", "utf-8")?><span class="after"></span></h1>
    	<div class="news-detail shadow">
        <?
        if(($arResult[DETAIL_PICTURE][ID])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arResult[DETAIL_PICTURE][SRC]))){
        ?>
        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 picture'>
            <img src='<?=DOMAIN.$arResult[DETAIL_PICTURE][SRC]?>' title='<?=$arResult[DETAIL_PICTURE][TITLE]?>' class='responsive-img'/>
        </div>
        <?
        }
        ?>
        <div class='<?=(($arResult[DETAIL_PICTURE][ID])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arResult[DETAIL_PICTURE][SRC])))?"col-lg-6 col-md-6 col-sm-6 ":""?>col-xs-12 text'>
        <?
        echo "<div class='date'>Дата публикации: <strong>".$arResult[DISPLAY_ACTIVE_FROM]." г.</strong></div>";
        if($arResult[DETAIL_TEXT_TYPE] == "html")
            echo $arResult[DETAIL_TEXT];
        else
            echo "<p>".$arResult[DETAIL_TEXT]."</p>";
        ?>
        </div>
        <div class='clear'></div>
        <a href="<?=$arResult[IBLOCK][LIST_PAGE_URL]?>" class="more-news"><i class="glyphicon glyphicon-chevron-left"" aria-hidden="true"></i><?=$arResult[IBLOCK][NAME]?></a>
    </div>
	</div>
</div>