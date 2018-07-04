<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
//echo "<pre>"; print_r($arResult); echo "</pre>";
if($_SESSION['arUser']){
	$COUPON_USER = $_SESSION['arUser'][UF_DISCONT];
	$DISCONT_CARD = $_SESSION['arUser'][UF_DISCONT_CARD];
	$UF_AMOUNT = $_SESSION['arUser'][UF_AMOUNT];

	if(PLUS_DISCONT > 0) 
		$COUPON_USER = $COUPON_USER+PLUS_DISCONT;
	
	if($UF_AMOUNT >= BIRTHDATE_DISCONT_ON){
		$BIRTHDATE_MONTH = $DB->FormatDate(substr($_SESSION['arUser'][PERSONAL_BIRTHDAY],0,5), "DD.MM", "MMDD");
		$date_from = date("md", strtotime(BIRTHDATE_DISCONT_FROM." day"));
		$date_to = date("md", strtotime(BIRTHDATE_DISCONT_TO." day"));
		if(PLUS_BIRTHDATE_DISCONT > 0) 
			$BIRTHDATE_DISCONT = BIRTHDATE_DISCONT+PLUS_BIRTHDATE_DISCONT;
		else	
			$BIRTHDATE_DISCONT = BIRTHDATE_DISCONT;
	}
}
if(empty($arResult[SEARCH])){
	echo "<div class='alert_warning margin-30-15'>К сожалению, по вашему запросу ничего не найдено...</div>";
	$APPLICATION->IncludeComponent(
		"bitrix:sale.viewed.product",
		"for-search-product",
		Array(
			"VIEWED_COUNT" => "20",
			"VIEWED_NAME" => "Y",
			"VIEWED_IMAGE" => "Y",
			"VIEWED_PRICE" => "Y",
			"VIEWED_CURRENCY" => "default",
			"VIEWED_CANBUY" => "Y",
			"VIEWED_CANBUSKET" => "Y",
			"VIEWED_IMG_HEIGHT" => "300",
			"VIEWED_IMG_WIDTH" => "300",
			"BASKET_URL" => "/cart/",
			"ACTION_VARIABLE" => "action",
			"ITEM_ID_VARIABLE" => "id",
			"SET_TITLE" => "N"
		)
	);
}else{
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
	$cntSearch = '';
	if(is_object($arResult["NAV_RESULT"]))
		$cntSearch = $arResult["NAV_RESULT"]->SelectedRowsCount();
	if($cntSearch){
		$num = abs($cntSearch) % 100;
		$num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
		if ($num > 10 && $num < 20){ // если число принадлежит отрезку [11;19]
			$prd = " товаров";
			$prdS = "Найдено: ";
		}elseif ($num_x > 1 && $num_x < 5){ // иначе если число оканчивается на 2,3,4
			$prd = " товара";
			$prdS = "Найдено: ";
		}elseif ($num_x == 1){ // иначе если оканчивается на 1
			$prd = " товар";
			$prdS = "Найден: ";
		}elseif ($num == 0){
			$prd = " товаров";
			$prdS = "Найдено: ";
		}else{
			$prd = " товаров";
			$prdS = "Найдено: ";
		}
		echo "<div class='cnt-search'>".$prdS."<strong>".number_format($cntSearch, 0, '.', ' ')."</strong>".$prd."</div>";
	}
		
	foreach($arResult[SEARCH] as $arElement){
	$ex = array_values(array_diff(explode("/",$arElement[URL]), array('')));
	//echo '<pre>'; print_r($ex); echo '</pre>';
	if(count($ex) > 1){//является товаром
		$arProduct = CIblockElement::GetById($arElement[ITEM_ID])->GetNext();
		$PREVIEW_PICTURE = CFile::GetPath($arProduct[PREVIEW_PICTURE]);	
		$MANUFACTURER = false;
		$db_prop = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[ITEM_ID], array(), Array("CODE"=>"MANUFACTURER"));
		if($ar_prop = $db_prop->Fetch()){
			$ress = $DB->Query("SELECT NAME,CODE FROM b_iblock_element WHERE IBLOCK_ID=2 AND ID=".intval($ar_prop[VALUE]));
			$row = $ress->Fetch();
			$MANUFACTURER = $row["NAME"];
		}
		$SIZE = false;
		$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[ITEM_ID], array(), Array("CODE"=>"SIZE"));
		if($ar_props = $db_props->Fetch()){
			$SIZE = $ar_props["VALUE"];
		}
		$STATUS = $STATUS_XML_ID = $STATUS_ENUM_ID = false;
		$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[ITEM_ID], array(), Array("CODE"=>"STATUS"));
		if($ar_props = $db_props->Fetch()){
			$STATUS = $ar_props[VALUE_ENUM];
   		 	$STATUS_XML_ID = $ar_props[VALUE_XML_ID];
			$STATUS_ENUM_ID = $ar_props[VALUE];
		}
		//echo '<pre>';print_r($ar_props);echo '</pre>';
		$AKCII = $AKCII_XML_ID = false;
		$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[ITEM_ID], array(), Array("CODE"=>"AKCII"));
		if($ar_props = $db_props->Fetch()){
			$AKCII = $ar_props[VALUE_ENUM];
   		 	$AKCII_XML_ID = $ar_props[VALUE_XML_ID];
		}
		
		$ar_res = CCatalogProduct::GetByID($arElement[ITEM_ID]);
		
		$OLD_PRICE = $arPrice = false;
		$db_res = CPrice::GetList(array(),array("PRODUCT_ID" => $arElement[ITEM_ID],"CATALOG_GROUP_ID" => array(1,3)));
		while($res = $db_res->Fetch()){
			if($res[CATALOG_GROUP_ID] == 1)
				$arPrice = $res;
			if($res[CATALOG_GROUP_ID] == 3)
				$OLD_PRICE = $res;
			//echo '<pre>';print_r($res);echo '</pre>';
		}
			
		//$arPrice = CPrice::GetBasePrice($arElement[ITEM_ID]);
		
		$DISCONT = false;
		$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[ITEM_ID], array(), Array("CODE"=>"DISCONT"));
		if($ar_props = $db_props->Fetch()){
			$DISCONT = $ar_props["VALUE"];
		}
	}else{//явлфется разделом
		
		$MANUFACTURER = "Раздел";
		$arrPr = array();
		$SIZE_SECT = array();
		$idSect = preg_replace('/[^0-9\s]/ui', ' ', $arElement[ITEM_ID]);
		$res = CIBlockSection::GetByID($idSect);
		$arsect = $res->GetNext();
		//echo '<pre>';print_r($arsect);echo '</pre>';
		$PREVIEW_PICTURE = CFile::GetPath($arsect[PICTURE]);
		$arElement[URL] = $arsect[SECTION_PAGE_URL];
		if($idSect){
			$rsResult = CIBlockSection::GetList(array(), array("IBLOCK_ID" =>1,"ID" => $idSect), false, $arSelect = array("UF_SECT_H1"));
			if($arRes = $rsResult -> GetNext()){
				//echo '<pre>'; print_r($arRes); echo '</pre>';
				$arElement[TITLE_FORMATED] = ($arRes[UF_SECT_H1])?$arRes[UF_SECT_H1]:$arElement[TITLE_FORMATED];
			}
			$SIZE_SECT = array();
			$arFilter = array(
				"IBLOCK_ID"=>1, 
				"ACTIVE"=>"Y", 
				"SECTION_ID"=>$idSect,
			);
			$arSelect = Array("PROPERTY_SIZE");
			$res = CIBlockElement::GetList(array("catalog_PRICE_1"=>"ASC"), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				$arrPr[] = $arFields[CATALOG_PRICE_1];
				$arrPrCur = $arFields[CATALOG_CURRENCY_1];
				$SIZE_SECT[] = trim($arFields[PROPERTY_SIZE_VALUE]);
				//echo '<pre>';print_r($arFields);echo '</pre>';
			}
			$arrPr = array_unique($arrPr);
			$SIZE_SECT = array_unique($SIZE_SECT);
			//echo '<pre>';print_r($SIZE_SECT);echo '</pre>';
		}
	}
	//echo '<pre>';print_r($OLD_PRICE);echo '</pre>';
?>
	<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6'>
		<div class="product shadow">
			<div class="product-hover"></div>
			<?
			$margintop = false;
			if(count($ex) > 1){
				
				if(($STATUS_XML_ID)AND(!$AKCII_XML_ID))
					echo "<div class='status-goods ".$STATUS_XML_ID."'><span>".$STATUS."</span><i></i></div>";
		
				if($AKCII_XML_ID)
					echo "<div class='status-goods ".$AKCII_XML_ID."'><span>".$AKCII."</span><i></i></div>";
					
				if(($DISCONT)AND($DISCONT_CARD)AND(!$AKCII_XML_ID)AND(!$OLD_PRICE[PRICE])AND($STATUS_ENUM_ID != 114)){
					if($STATUS_XML_ID)
						$margintop = ' margintop';
					if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
						echo "<div class='discount".$margintop."'><span>Скидка -".$BIRTHDATE_DISCONT."%</span><i></i></div>";
					}elseif($COUPON_USER > 0){
						echo "<div class='discount".$margintop."'><span>Скидка -".$COUPON_USER."%</span><i></i></div>";
					}
				}
			}
			?>
            <div class="product-bg">
				<div class="product-img">
					<?
                    if(count($ex) > 1){
					foreach($_SESSION[PRODUCT_VIEW] as $idItem){
						if($idItem == $arElement[ITEM_ID]){
							echo '<div class="view">Просмотрен</div>';
						}
					}
					?>
                        <a class="quick-view" href="javascript:void(0);" onClick='elementSynopsis(<?=$arElement[ITEM_ID]?>)' id="q-<?=$arElement[ITEM_ID]?>" rel="nofollow">Быстрый просмотр</a>
                        <a href="<?=DOMAIN.$arElement[URL]?>"  target="_blank" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>">
                        <?
						$grayscale = false;
                        if(($arProduct[PREVIEW_PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].$PREVIEW_PICTURE))){
							if($ar_res[QUANTITY] < 1)$grayscale = ' grayscale';
							echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/bg.jpg' data-original='".DOMAIN.$PREVIEW_PICTURE."' class='responsive-img".$grayscale."'/>";
                        }else{
                            echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
                        }
                        ?>
                        
                        </a>
                    <?
					}else{
					?>	
						<a href="<?=DOMAIN.$arElement[URL]?>" target="_blank">
                        <?
						if(($arsect[PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].$PREVIEW_PICTURE))){
							echo"<img src='".DOMAIN.$PREVIEW_PICTURE."' class='responsive-img'/>";
						}else{
							echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
						}
                    	?>
                        </a>
                    <?
					}
					?>
				</div>
				<a href="<?=DOMAIN.$arElement[URL]?>"  target="_blank" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[TITLE]))?>">
				<p class="collection">« <?=$MANUFACTURER?> »</p>
				<p class="name"><?=$arElement[TITLE_FORMATED]?></p>
				</a>
				<?
				//echo '<pre>';print_r($SIZE_SECT);echo '</pre>';
				if(count($ex) > 1){
					echo "<p class='size'>".$SIZE."</p>";
				}else{
					if(count($SIZE_SECT) > 1)
						$title = " title='".implode(", ",$SIZE_SECT)."'";
					echo "<p class='size'".$title.">";
						if(count($SIZE_SECT) > 1)
							echo implode(", ",$SIZE_SECT);
						else
							echo $SIZE_SECT[0];
					echo "</p>";
				}
				?>
				<div class="price">
					<?
                    if(count($ex) > 1){
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
								echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"], $arPrice["CURRENCY"]))."</span>";
								echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($price,$arPrice["CURRENCY"]))."</span>";
							}else{
								echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"], $arPrice["CURRENCY"]))."</span>";
							}
						}else{
							//if($OLD_PRICE[PRICE])
								//echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($OLD_PRICE[PRICE], $OLD_PRICE["CURRENCY"]))."</span>";
							echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arPrice["PRICE"], $arPrice["CURRENCY"]))."</span>";
						}
					}else{
					//echo '<pre>';print_r($arrPr);echo '</pre>';
					?>
                    <span class="current sect">
                    <?
						if(empty($arrPr)){
							echo "нет товаров";
						}else{
							if(min($arrPr) < max($arrPr)){
						?>
						от <?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat(min($arrPr), $arrPrCur))?> до <?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat(max($arrPr), $arrPrCur));
							}else{
						?>
                        от <?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat(min($arrPr), $arrPrCur));
							}
						?>
						</span>
						<?
						}
					}
					?>
				</div>
				<div class="btn-box">
					<?
					if(count($ex) > 1){
						if($ar_res[QUANTITY] > 0){
							$inBasket = false;
							foreach($_SESSION[ITEM_ID_IN_BASKET] as $idItemBasket){
								echo "<pre>"; print_r($idItemBasket); echo "</pre>";
								if($idItemBasket == $arElement[ITEM_ID]){
									$inBasket = "Уже в корзине";
								}
							}
						?>
						<div class="add-buy">
							<a href="javascript:void(0);" rel="nofollow" id="btn-add-<?=$arElement[ITEM_ID]?>" onClick="yaCounter43070549.reachGoal('ADD_BASKET'); basketElementAdd(<?=$arElement[ITEM_ID]?>,1); return false;"><?=($inBasket) ? $inBasket : "В корзину";?></a>
						</div>
						<?
						}else{
						?>
						<div class="not-available">
							<a href="javascript:void(0);" rel="nofollow" id="btn-av-<?=$arElement[ITEM_ID]?>" onClick='subscribeElementAdd(<?=$arElement[ID]?>)'>Оповестить</a>
						</div>
						<?
						}
					}else{
					?>
                    <div class="add-buy">
                        <a href="<?=$arElement[URL]?>" target="_blank">Перейти в раздел</a>
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
	echo "<div class='clear'></div>";
	echo $arResult["NAV_STRING"];
}
?>