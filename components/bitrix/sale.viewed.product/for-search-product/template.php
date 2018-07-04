<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? 
if($_SESSION['arUser']){
	$COUPON_USER = $_SESSION['arUser'][UF_DISCONT];
	$DISCONT_CARD = $_SESSION['arUser'][UF_DISCONT_CARD];
	$BIRTHDATE_MONTH = $DB->FormatDate(substr($_SESSION['arUser'][PERSONAL_BIRTHDAY],0,5), "DD.MM", "MMDD");
	$date_from = date("md", strtotime(BIRTHDATE_DISCONT_FROM." day"));
	$date_to = date("md", strtotime(BIRTHDATE_DISCONT_TO." day"));
	if(PLUS_DISCONT > 0) 
		$COUPON_USER = $COUPON_USER+PLUS_DISCONT;
	
	if(PLUS_BIRTHDATE_DISCONT > 0) 
		$BIRTHDATE_DISCONT = BIRTHDATE_DISCONT+PLUS_BIRTHDATE_DISCONT;
	else	
		$BIRTHDATE_DISCONT = BIRTHDATE_DISCONT;
}
//echo "<pre>"; print_r($arResult); echo "</pre>";
if(!empty($arResult)){
	echo "<div class='alert_warning margin-30-15'>Возможно Вам подойдут следующие товары.</div>";
	foreach($arResult as $arElement){
	
	$PREVIEW_PICTURE = CFile::GetPath($arElement[PREVIEW_PICTURE]);	
	$arProduct = CIblockElement::GetById($arElement[PRODUCT_ID])->GetNext(); 

	
	$MANUFACTURER = false;
	$db_prop = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"MANUFACTURER"));
	if($ar_prop = $db_prop->Fetch()){
		$ress = $DB->Query("SELECT NAME,CODE FROM b_iblock_element WHERE IBLOCK_ID=2 AND ID=".intval($ar_prop[VALUE]));
		$row = $ress->Fetch();
		$MANUFACTURER = $row["NAME"];
	}
	
	$SIZE = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"SIZE"));
	if($ar_props = $db_props->Fetch()){
		$SIZE = $ar_props["VALUE"];
	}
	
	$STATUS = $STATUS_XML_ID = $STATUS_ENUM_ID = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"STATUS"));
	if($ar_props = $db_props->Fetch()){
		$STATUS = $ar_props[VALUE_ENUM];
		$STATUS_XML_ID = $ar_props[VALUE_XML_ID];
		$STATUS_ENUM_ID = $ar_props[VALUE];
	}
	//echo '<pre>';print_r($ar_props);echo '</pre>';
	$AKCII = $AKCII_XML_ID = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"AKCII"));
	if($ar_props = $db_props->Fetch()){
		$AKCII = $ar_props[VALUE_ENUM];
		$AKCII_XML_ID = $ar_props[VALUE_XML_ID];
	}
	
	$ar_res = CCatalogProduct::GetByID($arElement[PRODUCT_ID]);	
	
	$DISCONT = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"DISCONT"));
	if($ar_props = $db_props->Fetch()){
		$DISCONT = $ar_props[VALUE];
	}
	$ar_res = CCatalogProduct::GetByID($arElement[PRODUCT_ID]);	
	
	$OLD_PRICE = $arPrice = false;
	$db_res = CPrice::GetList(array(),array("PRODUCT_ID" => $arElement[PRODUCT_ID],"CATALOG_GROUP_ID" => array(1,2)));
	while($res = $db_res->Fetch()){
		if($res[CATALOG_GROUP_ID] == 1)
			$arPrice = $res;
		if($res[CATALOG_GROUP_ID] == 2)
			$OLD_PRICE = $res;
		//echo '<pre>';print_r($res);echo '</pre>';
	}
	//echo "<pre>"; print_r($ar_props); echo "</pre>";
	?>
	<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6'>
		<div class="product shadow">
			<div class="product-hover"></div>
            <?
			$margintop = false;
            if(($STATUS_XML_ID)AND(!$AKCII_XML_ID))
				echo "<div class='status-goods ".$STATUS_XML_ID."'><span>".$STATUS."</span><i></i></div>";
	
			if($AKCII_XML_ID)
				echo "<div class='status-goods ".$AKCII_XML_ID."'><span>".$AKCII."</span><i></i></div>";
				
			if(($DISCONT)AND($DISCONT_CARD)AND(!$AKCII_XML_ID)AND(!$OLD_PRICE[PRICE])AND($STATUS_ENUM_ID != 114)){
				if($AKCII_XML_ID)
					$margintop = ' margintop';
				if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
					echo "<div class='discount".$margintop."'><span>Скидка -".$BIRTHDATE_DISCONT."%</span><i></i></div>";
				}elseif($COUPON_USER > 0){
					echo "<div class='discount".$margintop."'><span>Скидка -".$COUPON_USER."%</span><i></i></div>";
				}
			}
			?>
			<div class="product-bg">
				<div class="product-img">
					<?
					foreach($_SESSION[PRODUCT_VIEW] as $idItem){
						if($idItem == $arElement[PRODUCT_ID]){
							echo '<div class="view">Просмотрен</div>';
						}
					}
					?>
                    <a class="quick-view" href="javascript:void(0);" onClick='elementSynopsis(<?=$arElement[PRODUCT_ID]?>)' id="q-<?=$arElement[PRODUCT_ID]?>" rel="nofollow">Быстрый просмотр</a>
					<a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
					<?
					$grayscale = false;
					if(($arElement[PREVIEW_PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].$PREVIEW_PICTURE))){
						if($ar_res[QUANTITY] < 1)$grayscale = ' grayscale';
						echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/bg.jpg' data-original='".DOMAIN.$PREVIEW_PICTURE."' alt='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))."' class='responsive-img".$grayscale."'/>";
					}else{
						echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
					}
					?>
					
					</a>
				</div>
				<a href="<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
				<p class="collection">« <?=$MANUFACTURER?> »</p>
				<p class="name" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>"><?=mb_strimwidth($arElement[NAME], 0, 65, "... ",'utf-8')?></p>
				</a>
				<?
				if($SIZE)
					echo "<p class='size'>".$SIZE."</p>";
				?>
				<div class="price">
					<?
					$price = false;
					if(($DISCONT)AND($DISCONT_CARD)AND(!$AKCII_XML_ID)AND(!$OLD_PRICE[PRICE])AND($STATUS_ENUM_ID != 114)){
						$COUPON = 0;	
						if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
							$COUPON = $BIRTHDATE_DISCONT;
						}elseif($COUPON_USER > 0){
							$COUPON = $COUPON_USER;
						}
						$discont_sum = ceil(($COUPON * $arPrice["PRICE"])/100);
						$price = ceil($arPrice["PRICE"] - $discont_sum);
						
						if($arPrice["PRICE"] > $price){
							echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"],$arPrice[CURRENCY]))."</span>";
							echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($price,$arPrice[CURRENCY]))."</span>";
						}else{
							echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"],$arPrice[CURRENCY]))."</span>";
						}
					}else{
						if($OLD_PRICE[PRICE])
							echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($OLD_PRICE["PRICE"],$OLD_PRICE[CURRENCY]))."</span>";
						echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"],$arPrice[CURRENCY]))."</span>";
					}
					?>
				</div>
				<div class="btn-box">
					<?
					if($ar_res[QUANTITY] > 0){
						$inBasket = false;
						foreach($_SESSION[PRODUCT_ID_IN_BASKET] as $idItemBasket){
							if($idItemBasket == $arElement[PRODUCT_ID]){
								$inBasket = "Уже в корзине";
							}
						}
					?>
					<div class="add-buy">
						<a href="javascript:void(0);" rel="nofollow" id="btn-add-<?=$arElement[PRODUCT_ID]?>" onClick="yaCounter43070549.reachGoal('ADD_BASKET'); basketElementAdd(<?=$arElement[PRODUCT_ID]?>,1); return false;"><?=($inBasket) ? $inBasket : "В корзину";?></a>
					</div>
					<?
					}else{
					?>
					<div class="not-available">
						<a href="javascript:void(0);" rel="nofollow" id="btn-av-<?=$arElement[PRODUCT_ID]?>" onClick='subscribeElementAdd(<?=$arElement[PRODUCT_ID]?>)'>Оповестить</a>
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
}else{
	$SEARCH = str_replace('quot', " ", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $_GET[q]));
	$search = explode(" ", mb_strtolower($SEARCH));
	foreach($search as $word){
		if (!in_array($word, $_SESSION['stopWord'])) {
			$sql[]= "%".$word."%";
		}
	}
	//echo '<pre>'; print_r($sql); echo '</pre>';
	global $bbFilter;
	$bbFilter = array(
		"SEARCHABLE_CONTENT" => $sql
	);
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"alternative-search",
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
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"CONVERT_CURRENCY" => "N",
			"CURRENCY_ID" => "RUB",
			"CUSTOM_FILTER" => "",
			"DETAIL_URL" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "Y",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "sort",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_ORDER2" => "asc",
			"FILE_404" => "",
			"FILTER_NAME" => "bbFilter",
			"HIDE_NOT_AVAILABLE" => "Y",
			"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
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
			"PAGE_ELEMENT_COUNT" => "20",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array("BASE", "OLD"),
			"PRICE_VAT_INCLUDE" => "N",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(""),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("MANUFACTURER", ""),
			"SECTION_CODE" => "",
			"SECTION_CODE_PATH" => $_REQUEST["SECTION_CODE_PATH"],
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array("", ""),
			"SEF_MODE" => "N",
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
	);
}
?>