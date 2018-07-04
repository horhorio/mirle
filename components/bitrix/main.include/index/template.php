<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("catalog");
$arSlide = array();
$arSelect = array("NAME","CODE","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_LOCAT_TEXT","PROPERTY_BUTTON_TEXT");
$arFilter = array("IBLOCK_ID"=>4, "ACTIVE"=>"Y","!PREVIEW_PICTURE"=>"");
$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);//array("nPageSize"=>1)
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	$arSlide[] = $arFields;
	//echo '<pre>'; print_r($arFields); echo '</pre>';
}
//echo '<pre>'; print_r($arSlide); echo '</pre>';
if(!empty($arSlide)){
	?>
    <div class="index-slids">
    <?
    foreach($arSlide as $slide){
	?>
    <div class="index-slide">
        <img src="<?=DOMAIN.CFile::GetPath($slide["PREVIEW_PICTURE"])?>" alt="<?=$slide["NAME"]?>" class="responsive-img">
        <div class="c-banner-hero__text">
       	  <div class="text <?=($slide[PROPERTY_LOCAT_TEXT_ENUM_ID]==78)?"right":"left"?>">
              <? if($slide["PREVIEW_TEXT"])echo "<div class='tt'><p>".$slide["PREVIEW_TEXT"]."</p></div>";?>
              <a href="<?=DOMAIN.$slide["CODE"]?>" class="more"><strong><?=($slide[PROPERTY_BUTTON_TEXT_VALUE])?$slide[PROPERTY_BUTTON_TEXT_VALUE]:"Посмотреть"?></strong></a>
              <div class="clear"></div>
          </div>
      </div>
    </div>
    <?
	}
	?>
    </div>
    <?
}
$add = array();
$arSelect = array("ID","IBLOCK_ID","NAME","DETAIL_PAGE_URL","DETAIL_PICTURE");
$arFilter = array("IBLOCK_ID"=>1, "ACTIVE"=>"Y","PROPERTY_AKCII"=>array(79,80,81,82,88,89,90,91,92,93,94,95,96,97,98,99,100),"PROPERTY_MAIN"=>50);//"PROPERTY_MAIN"=>50,"SECTION_ID"=>92
$res = CIBlockElement::GetList(array("RAND"=>"ASC","shows"=>"ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	$img = CFile::ResizeImageGet($arFields[DETAIL_PICTURE], array('width'=>620, 'height'=>'290'), BX_RESIZE_IMAGE_EXACT, true);
	if(is_file($_SERVER["DOCUMENT_ROOT"].$img[src])){
    	//echo '<pre>';print_r($arFields);echo '</pre>';
		$AKCII = $AKCII_XML_ID = false;
		$db_props = CIBlockElement::GetProperty($arFields[IBLOCK_ID], $arFields[ID], array(), Array("CODE"=>"AKCII"));
		if($ar_props = $db_props->Fetch()){
			$AKCII = $ar_props[VALUE_ENUM];
			$AKCII_XML_ID = $ar_props[VALUE_XML_ID];
		}
		$add[] = array(
    			"PREVIEW_PICTURE"=>DOMAIN.$img[src],
    			"NAME" => $arFields[NAME],
    			"DETAIL_PAGE_URL" => DOMAIN.$arFields["DETAIL_PAGE_URL"],
				"AKCII" => $AKCII,
				"AKCII_XML_ID" => $AKCII_XML_ID
    	);
	}
}
//echo '<pre>';print_r($add);echo '</pre>';
if((count($add) > 2 && !empty($add))){
	echo '<div class="index-advertising">';
	foreach($add as $arElement) {
		echo "<div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 el-advert'>";
			echo "<a href='".$arElement[DETAIL_PAGE_URL]."' class='advert'>";
				if($arElement[AKCII_XML_ID])
					echo "<div class='status-goods ".$arElement[AKCII_XML_ID]."'><span>".$arElement[AKCII]."</span><i></i></div>";
					
				echo "<div class='img'>";
					echo "<span>Посмотреть сейчас</span>";
					echo "<img src='".$arElement[PREVIEW_PICTURE]."' alt='".$arElement[NAME]."' class='responsive-img'>";
				echo "</div>";
				echo "<p>".$arElement[NAME]."</p>";
			echo "</a>";
		echo "</div>";
	}
	echo "<div class='clear'></div>";
	echo "</div>";
}
$arResult[SECTIONS] = array();
$arSelect = Array("NAME","SECTION_PAGE_URL","PICTURE");
$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE"=>"Y","DEPTH_LEVEL"=>1);
$bIncCnt = Array('CNT_ACTIVE' => "Y");
$ar_result=CIBlockSection::GetList(Array("element_cnt"=>"DESC","SORT"=>"ASC"), $arFilter , $bIncCnt, $arSelect,false); //array("nPageSize"=>8)
while($res = $ar_result->GetNext())
{
	//echo '<pre>';print_r($res);echo '</pre>';
	$arResult[SECTIONS][] = $res;
}

//echo "<pre>"; print_r($arResult[SECTIONS]); echo "</pre>";
if(!empty($arResult[SECTIONS])){
?>
<div class="index-departments index-departments-bg">
	<div class="wrapp">
    <h3 class="h3-head margin-top-none"><span class="before"></span>Популярные разделы<span class="after"></span></h3>
    </div>
    <div class="department wrapp">
    <?
    foreach($arResult[SECTIONS] as $key => $arSection){
    ?>
        <div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 elem<?=($key > 7)?" hidd":""?>'>
            <a href="<?=DOMAIN.$arSection[SECTION_PAGE_URL]?>" class="element shadow">
                <div class="element-hover"></div>
                <div class="element-bg">
                    <div class="img">
                    <?
                    if($arSection["PICTURE"])
						$img = DOMAIN.CFile::GetPath($arSection["PICTURE"]);
					else
						$img = DOMAIN.SITE_TEMPLATE_PATH."/images/product-test/1.jpg";
					?>
                    <img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/bg.jpg' data-original="<?=$img?>" alt="<?=$arSection[NAME]?>" class="responsive-img">
                    </div>
                    <div class="name">
                        <h3 title="<?=$arSection[NAME]?>"><?=$arSection[NAME]?></h3>
                        <?
                        $num = abs($arSection[ELEMENT_CNT]) % 100;
                        $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
                        if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
                            $prd = " товаров";
                        elseif ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
                            $prd = " товара";
                        elseif ($num_x == 1) // иначе если оканчивается на 1
                            $prd = " товар";
                        elseif ($num == 0)
                            $prd = " товаров";
						else
                            $prd = " товаров";
                        ?>
                        <span><?=number_format($arSection[ELEMENT_CNT], 0, '.', ' ').$prd?></span>
                    </div>
                </div>
            </a>
        </div>
    <?
    }
    ?>
    <div class="clear"></div>
    <a href="javascript:void(0)" rel="nofollow" class="view-more shadow"><strong>Посмотреть все</strong></a>
    </div>
</div>
<?
}
$arC = false;
foreach($_SESSION['cityDelivery'] as $c){
	if(mb_strtolower($_SESSION['dataCity'][city]) == mb_strtolower($c[CITY_NAME])){
		$arC = $c[CITY_ID];
		break;
	}
}
if($arC){
?>
<div class="index-confidence">
	<div class="wrapp">
		<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 el'>
	    	<img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/delivery123.png" class='responsive-img'/>
	    	<h4>Оперативная доставка</h4>
	    	<p>Доставим прямо в руки по<br>г. <strong><?=$_SESSION['dataCity'][city]?></strong><br>оплата после проверки товара!</p>
	    </div>
		<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 el'>
			<img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/Gift-100.png" class='responsive-img'/>
	    	<h4>Подарок за покупку</h4>
	    	<a href="/gift/"><p>Получите уютное махровое полотенце в подарок!</p></a>
		</div>
		<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 el'>
			<img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/natura123l.png" class='responsive-img'/>
	    	<h4>100% качество</h4>
	    	<p>Исключительно натуральные материалы, известные бренды!</p>
		</div>
		<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12 el'>
			<img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/cash123.png" class='responsive-img'/>
	    	<h4>Оплата при получении</h4>
	    	<p>Оплатите наличными или картой после получения товара - никаких рисков!</p>
		</div>
		<div class='clear'></div>
  </div>
</div>
<?	
}
global $bbFilter;
$bbFilter = array(
	array(
		"LOGIC" => "AND",
		"PROPERTY"=>array(
			"STATUS"=>35,
			"MAIN"=>50
		)
	),
);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"new",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/cart/",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "RUB",
		"CUSTOM_FILTER" => "",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "RAND",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"FILE_404" => "",
		"FILTER_NAME" => "bbFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "1",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "peges",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "15",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array("BASE","OLD"),
		"PRICE_VAT_INCLUDE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(""),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PROPERTY_CODE" => array("MANUFACTURER",""),
		"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
		"SECTION_CODE_PATH" => $_REQUEST["SECTION_CODE_PATH"],
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array("",""),
		"SEF_MODE" => "Y",
		"SEF_RULE" => "#SECTION_CODE#",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
);?>
<?
$arResultRev = array();
$arSelect = Array("LIST_PAGE_URL","DATE_CREATE","NAME","PREVIEW_TEXT","PROPERTY_PRODUCT_ID","PROPERTY_CITY");
$arFilter = Array("IBLOCK_ID"=>6,"ACTIVE"=>'Y');
$res = CIBlockElement::GetList(array("ID"=>"DESC"), $arFilter, false, array("nPageSize" => '2'), $arSelect);
while($ob = $res->GetNextElement()){ 
	$arResultRev[] = $ob->GetFields();
}
$arResultNews = array();
$arSelect = Array("DATE_CREATE","NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_PAGE_URL");
$arFilter = Array("IBLOCK_ID"=>5,"ACTIVE"=>'Y');
$res = CIBlockElement::GetList(array("ID"=>"DESC"), $arFilter, false, array("nPageSize" => '2'), $arSelect);
while($ob = $res->GetNextElement()){ 
	$arResultNews[] = $ob->GetFields();
}
if((!empty($arResultRev))OR(!empty($arResultNews))){
echo "<div class='index-rev-news wrapp'>";
if(!empty($arResultRev)){
//echo "<pre>"; print_r($arResultRev); echo "</pre>";
?>
<div class="<?=(!empty($arResultNews))?"col-lg-6 col-md-6 col-sm-12 col-xs-12":"col-xs-12"?>">
	<a href="<?=$arResultRev[0][LIST_PAGE_URL]?>">
    <h3 class="h3-head"><span class="before"></span>Отзывы<span class="after"></span></h3>
    </a>
    <?
    foreach($arResultRev as $arElement){
		$arProduct = CIblockElement::GetById($arElement[PROPERTY_PRODUCT_ID_VALUE])->GetNext();
		//echo "<pre>"; print_r($arProduct); echo "</pre>";
		$path = CFile::GetPath($arProduct["PREVIEW_PICTURE"]);
	?>
    <div class="col-xs-12 element no-padding shadow">
    	<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 element-img">
            <a href="<?=DOMAIN.$arProduct[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arProduct[NAME]))?>">
			<?
            if(($arProduct["PREVIEW_PICTURE"])AND(is_file($_SERVER["DOCUMENT_ROOT"].$path))){
                echo"<img src='".DOMAIN.$path."' alt='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arProduct[NAME]))."' class='responsive-img'/>";
            }else{
                echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
            }
            ?>
            </a>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 element-prop no-padding">
            <a href="<?=DOMAIN.$arProduct[DETAIL_PAGE_URL]?>#commentProduct" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arProduct[NAME]))?>">
            	<h2><?=$arProduct[NAME]?></h2>
            </a>
            <p><?=$arElement[PREVIEW_TEXT]?></p>
            <div class="us-date">
            	<span class="user"><?=$arElement[NAME]; echo($arElement[PROPERTY_CITY_VALUE])?" г. ".$arElement[PROPERTY_CITY_VALUE]:""?></span>
            	<span class="date"><?=date( 'd.m.Y', strtotime($arElement[DATE_CREATE]))?></span>
                <div class='clear'></div>
            </div>
        </div>
        <div class='clear'></div>
    </div>
    <?
	}
    ?>
    <div class='clear'></div>
</div>
<?
}
if(!empty($arResultNews)){
?>
<div class="<?=(!empty($arResultRev))?"col-lg-6 col-md-6 col-sm-12 col-xs-12":"col-xs-12"?>">
	<a href="/novosti/">
    <h3 class="h3-head"><span class="before"></span>Новости<span class="after"></span></h3>
    </a>
    <?
    foreach($arResultNews as $arElement){
		$path = CFile::GetPath($arElement["PREVIEW_PICTURE"]);
	?>
    <div class="col-xs-12 element no-padding shadow">
    	<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 element-img">
            <a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
			<?
            if(($arElement["PREVIEW_PICTURE"])AND(is_file($_SERVER["DOCUMENT_ROOT"].$path))){
                echo"<img src='".DOMAIN.$path."' alt='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))."' class='responsive-img'/>";
            }else{
                echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
            }
            ?>
            </a>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 element-prop no-padding">
            <a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
            	<h2><?=$arElement[NAME]?></h2>
            </a>
            <p><?=$arElement[PREVIEW_TEXT]?></p>
            <div class="us-date">
            	<span class="date"><?=date( 'd.m.Y', strtotime($arElement[DATE_CREATE]))?></span>
                <div class='clear'></div>
            </div>
        </div>
        <div class='clear'></div>
    </div>
    <?
	}
    ?>
    <div class='clear'></div>
</div>
<?
}
echo "<div class='clear'></div>";
echo "</div>";
}
$res = $DB->Query("
SELECT 
	b_iblock_element.PREVIEW_TEXT,
	b_iblock_element_prop_s3.PROPERTY_7
FROM 
	b_iblock_element,
	b_iblock_element_prop_s3
WHERE 
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = b_iblock_element.ID
		AND
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = 14
		AND
	b_iblock_element.ACTIVE='Y'
		AND
	b_iblock_element.IBLOCK_ID = 3
");
$row = $res->Fetch();
//echo '<pre>';print_r($row);echo '</pre>';
if(strlen($row['PREVIEW_TEXT']) > 30){
?>
<div class="wrapp">
<div class="col-lg-12 col-md-12 hidden-sm hidden-xs desc-bottom-text">
	<h3 class="h3-head"><span class="before"></span><?=$row['PROPERTY_7']?><span class="after"></span></h3>
	<?=$row['PREVIEW_TEXT']?>
</div>
<div class="clear"></div>
</div>
<?
}
?>