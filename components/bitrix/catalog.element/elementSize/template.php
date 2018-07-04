<? 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$CITY = $_SESSION['dataCity'][city];
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
$FUSER_ID = CSaleBasket::GetBasketUserID();
//echo '<pre>'; print_r($arResult); echo '</pre>';
$arrSize = array();
if($arResult[PROPERTIES][VN_CODE][VALUE]){
	$arSelect = Array("ID","PROPERTY_SIZE_RANGE","PROPERTY_SIZE");
	$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ACTIVE"=>"Y","PROPERTY_VN_CODE"=>$arResult[PROPERTIES][VN_CODE][VALUE],"!PROPERTY_SIZE_RANGE"=>"");
	$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){ 
		$arField = $ob->GetFields();
		//echo "<pre>"; print_r($arField); echo "</pre>";
		$arrSize[] = $arField;
	}
}
//echo "<pre>"; print_r($_SESSION[PRODUCT_ID_IN_BASKET]); echo "</pre>";
$arrCombiColor = array();
if(($arResult[PROPERTIES][COMBINE_BY_COLOR][VALUE])AND($arResult[PROPERTIES][COLOR][VALUE])){       
	
	$arrCombiColor[] = array(
		"ID"=>$arResult[ID],
		"PROPERTY_COLOR_VALUE"=>$arResult[PROPERTIES][COLOR][VALUE],
		"VALUE_XML_ID"=>$arResult[PROPERTIES][COLOR][VALUE_XML_ID]
	);
	
	$arSelect = Array("ID","PROPERTY_COLOR");
	$arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ACTIVE"=>"Y","ID"=>$arResult[PROPERTIES][COMBINE_BY_COLOR][VALUE],"!ID"=>$arResult[ID]);
	$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){ 
		$arField = $ob->GetFields();
		$db_prop = CIBlockElement::GetProperty($arResult["IBLOCK_ID"], $arField[ID], array(), Array("CODE"=>"COLOR"));
		if($ar_prop = $db_prop->Fetch()){
			//echo "<pre>"; print_r($ar_prop); echo "</pre>";
			$arField[VALUE_XML_ID] = $ar_prop[VALUE_XML_ID];
			$arrCombiColor[] = $arField;
		} 
		//echo "<pre>"; print_r($arField); echo "</pre>";
	}
	//echo "<pre>"; print_r($arrCombiColor); echo "</pre>";
}

$commProduct = array();
$arSelect = Array("ID","DATE_CREATE","NAME","PREVIEW_TEXT","LIST_PAGE_URL","CREATED_DATE","PROPERTY_EMAIL","PROPERTY_USER_ID","PROPERTY_RATING","PROPERTY_CITY");
$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y","PROPERTY_PRODUCT_ID"=>$arResult[ID]);
$res = CIBlockElement::GetList(array("ID"=>"DESC"), $arFilter, false, array("nPageSize" => '15'), $arSelect);
while($ob = $res->GetNextElement()){ 
	$arField = $ob->GetFields();
	//echo "<pre>"; print_r($arField); echo "</pre>";
	$commProduct[] = $arField;
}

$inLike = false;
$arSelect = Array("ID","CODE");
$arFilter = Array("IBLOCK_ID"=>8,"ACTIVE"=>"Y","CODE"=>$arResult[ID],"PROPERTY_USER_ID"=>CUser::GetID());
$res = CIBlockElement::GetList(array("ID"=>"DESC"), $arFilter, false, array("nPageSize" => '1'), $arSelect);
while($ob = $res->GetNextElement()){ 
	$arField = $ob->GetFields();
	//echo "<pre>"; print_r($arField); echo "</pre>";
	$arProduct = CIblockElement::GetById($arField[CODE])->GetNext(); 
	//echo "<pre>"; print_r($arProduct); echo "</pre>";
	if(($arField[ID])AND($arProduct[ID])AND($arProduct[ACTIVE] == "Y")){
		$inLike = $arField[CODE];
		$inLikeID = $arField[ID];
	}
}

if(($arResult[PROPERTIES][STATUS][VALUE_XML_ID])AND(!$arResult[PROPERTIES][AKCII][VALUE_XML_ID]))
	echo "<div class='status-goods ".$arResult[PROPERTIES][STATUS][VALUE_XML_ID]."'><span>".$arResult[PROPERTIES][STATUS][VALUE]."</span><i></i></div>";

if($arResult[PROPERTIES][AKCII][VALUE_XML_ID])
	echo "<div class='status-goods ".$arResult[PROPERTIES][AKCII][VALUE_XML_ID]."'><span>".$arResult[PROPERTIES][AKCII][VALUE]."</span><i></i></div>";

if(($arResult[PROPERTIES][DISCONT][VALUE])AND($DISCONT_CARD)AND(!$arResult[PROPERTIES][AKCII][VALUE_XML_ID])AND(!$arResult[PRICES][OLD][VALUE])AND($arResult[PROPERTIES][STATUS][VALUE_ENUM_ID] != 114)){
	if($arResult[PROPERTIES][STATUS][VALUE_XML_ID])
		$margintop = ' margintop';
		
	if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
		echo "<div class='discount".$margintop."'><span>Скидка -".$BIRTHDATE_DISCONT."%</span><i></i></div>";
	}elseif($COUPON_USER > 0){
		echo "<div class='discount".$margintop."'><span>Скидка -".$COUPON_USER."%</span><i></i></div>";
	}
}
?>
<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 detail-img-box no-padding'>
	<div class="detail-img">
	<?
	if(($arResult[DETAIL_PICTURE][SRC])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arResult[DETAIL_PICTURE][SRC]))){
		if($arResult[CATALOG_QUANTITY] < 1)$grayscale = ' grayscale';
		echo "<a href='".DOMAIN.$arResult[DETAIL_PICTURE][SRC]."' target='_blank'  data-caption='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT]))."'>";
		echo "<img src='".DOMAIN.$arResult[DETAIL_PICTURE][SRC]."' alt='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT]))."' class='responsive-img".$grayscale."'/>";
		echo "</a>";
		if(!empty($arResult[PROPERTIES][MORE_PICTURE][VALUE])){
			foreach($arResult[PROPERTIES][MORE_PICTURE][VALUE] as $img){
				echo "<a href='".DOMAIN.CFile::GetPath($img)."' target='_blank' style='dispaly:none'></a>";
			}
		}
	}else{
		echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";	
	}
	?>
	</div>
	<?
	if(!empty($arResult[PROPERTIES][MORE_PICTURE][VALUE])){
	?>
	<div class='detail-more-img-box'>
		<div class='detail-more-img row-flex'>
			<?
			if(($arResult[DETAIL_PICTURE][SRC])AND(is_file($_SERVER["DOCUMENT_ROOT"].$arResult[DETAIL_PICTURE][SRC]))){
			echo "<a href='".DOMAIN.$arResult[DETAIL_PICTURE][SRC]."' style='display: none;'>";
			echo "<img src='".DOMAIN.$arResult[DETAIL_PICTURE][SRC]."' class='responsive-img".$grayscale."'/>";
			echo "</a>";
			}
			foreach($arResult[PROPERTIES][MORE_PICTURE][VALUE] as $img){
			$file = CFile::ResizeImageGet($img, array('width'=>200, 'height'=>'200'), BX_RESIZE_IMAGE_EXACT, true); 
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 photo">
				<a href="<?=CFile::GetPath($img)?>" target="_blank"  data-caption="<?=preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT])?>">
				<img src='<?=DOMAIN.$file[src]?>'  alt='<?=preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT])?>' class='responsive-img'/>
				</a>
			</div>
			<?
			}
			?>
		</div>
	</div>
	<?
	}
	if(($arResult[PROPERTIES][STATUS][VALUE_ENUM_ID]==114)AND($arResult[PROPERTIES][GIFT_ELEMENT][VALUE])){			
		$arGift = CIblockElement::GetById($arResult[PROPERTIES][GIFT_ELEMENT][VALUE])->GetNext(); 
		//echo "<pre>"; print_r($arGift); echo "</pre>";
		if($arGift[ID]){
		echo "<div class='gift'>";
			echo "<h3>У нас для Вас подарок</h3>";
			echo "<div class='gift-el'>";
				echo "<div class='col-lg-3 col-md-3 col-sm-4 col-xs-12 img'>";
				
				if(($arGift[PREVIEW_PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arGift[PREVIEW_PICTURE])))){
					$file = CFile::ResizeImageGet($arGift[PREVIEW_PICTURE], array('width'=>200, 'height'=>'200'), BX_RESIZE_IMAGE_EXACT, true); 
					echo "<a href='".DOMAIN.CFile::GetPath($arGift[DETAIL_PICTURE])."' target='_blank'>";
					echo"<img src='".DOMAIN.$file[src]."' class='responsive-img'/>";
					echo "</a>";
				}else{
					echo "<a href='".$arGift[DETAIL_PAGE_URL]."' target='_blank'>";
					echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
					echo "</a>";
				}
				
				echo "</div>";
				echo "<div class='col-lg-9 col-md-9 col-sm-8 col-xs-12 prop'>";
					echo "<a href='".$arGift[DETAIL_PAGE_URL]."' target='_blank'>".$arGift[NAME]."</a>";
					echo "<p class='desc'>".mb_strimwidth(preg_replace("/\s{2,}/"," ",str_replace($r, $l, preg_replace('/[^a-zа-я-_,.0-9\s]/ui', '', $arGift[PREVIEW_TEXT]))), 0, 140, "... ",'utf-8')."</p>";
				echo "</div>";
				echo "<div class='clear'></div>";
			echo "</div>";
		echo "</div>";
		}
	}
	if(!empty($arrSize)){
		echo "<div class='size-range'>";
			echo "<h3>Размерный ряд</h3>";
			foreach($arrSize as $val){
				$act = $onclick = false;
				if(($arResult[PROPERTIES][SIZE_RANGE][VALUE][0]==$val[PROPERTY_SIZE_RANGE_VALUE][0])AND(count($arrSize > 1)))
					$act = " act";
				else
					$onclick = " onClick='javascript:elementSize(".intval($val[ID]).")'";
				if(!empty($val[PROPERTY_SIZE_RANGE_VALUE])){
					if($val[PROPERTY_SIZE_VALUE]){
						echo "<div class='td".$act."'".$onclick.">";
							echo "<h5>".$val[PROPERTY_SIZE_VALUE].":</h5>";
							foreach($val[PROPERTY_SIZE_RANGE_VALUE] as $val){
								echo "<p>".$val."</p>";
							}
						echo "</div>";
					}
				}
			}
		echo "</div>";
	}
	?>
	
</div>
<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 detail-prop-box no-padding'>
	<?
	$arrSortAlown = array('id'=> 'ID');
	$_sort = isset($arrSortAlown[$_GET['sort']]) ? $arrSortAlown[$_GET['sort']] : 'ID';
	$sort_url = 'sort=ID&order=asc';
	
	$res = CIBlockElement::GetList(
	  array("$_sort" => $_order),
	  Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ACTIVE"=>"Y", "SECTION_ID" => $arResult["IBLOCK_SECTION_ID"],"PROPERTY_MAIN"=>50),
	  false, 
	  array("nPageSize" => "1","nElementID" => $arResult["ID"]), 
	  array_merge(Array("ID", "NAME","DETAIL_PAGE_URL"), array_values($arrSortAlown)) 
	);
	$navElement = array();
	while($ob = $res->GetNext()){
		$navElement[] = $ob;
	}
	if(count($navElement) == 2)
		$page = 1;
	else 
		$page = 2;
	?>
	<div class="navElement">
		<? if(($navElement[0][RANK] == 1) AND ($arResult[ID] == $navElement[0][ID])):?>
			<a href="<?=DOMAIN.$navElement[$page]['DETAIL_PAGE_URL']?>?<?=$sort_url?>" class="r" rel="nofollow"><strong>Предыдущий товар</strong></a><span class="ls"><strong>Следующий товар</strong></span>
		<? elseif(count($navElement) == 2):?>
			<span class="rs"><strong>Предыдущий товар</strong></span><a href="<?=DOMAIN.$navElement[0]['DETAIL_PAGE_URL']?>?<?=$sort_url?>" class="l" rel="nofollow"><strong>Следующий товар</strong></a>
		<? else:?>
			<a href="<?=DOMAIN.$navElement[$page]['DETAIL_PAGE_URL']?>?<?=$sort_url?>" class="r" rel="nofollow"><strong>Предыдущий товар</strong></a><a href="<?=DOMAIN.$navElement[0]['DETAIL_PAGE_URL']?>?<?=$sort_url?>" class="l" rel="nofollow"><strong>Следующий товар</strong></a>
		<? endif;?>
		<div class="clear"></div>
	</div>

	<div class="detail-property">
		<? 
		if($arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][NAME]){
		?>
		<div class="property">
			<p class="key-array"><?=$arResult[DISPLAY_PROPERTIES][MANUFACTURER][NAME]?>:</p>
			<p class="res-array"><a href='<?=DOMAIN.$arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][DETAIL_PAGE_URL]?>' target="_blank"><strong><?=$arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][NAME];?></strong></a></p>
		</div>
		<? 
		}
		
		$country = false;
		$arSelect = Array("ID","PROPERTY_COUNTRY");
		$arFilter = Array($arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]]["IBLOCK_ID"],"NAME"=>$arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][NAME]);
		$res = CIBlockElement::GetList(array("ID"=>"ASC"), $arFilter, false, array("nPageSize" => '1'), $arSelect);
		while($ob = $res->GetNextElement()){ 
			$arField = $ob->GetFields();
			//echo "<pre>"; print_r($arField); echo "</pre>";
			$country = $arField[PROPERTY_COUNTRY_VALUE];
		}
		if($country){
		?>
		<div class="property">
			<p class="key-array">Страна:</p>
			<p class="res-array"><strong><?=$country?></strong></p>
		</div>
		<?
		}
		
		if($arResult[PROPERTIES][SIZE][VALUE]){
		?>
		<div class="property">
			<p class="key-array"><?=$arResult[PROPERTIES][SIZE][NAME]?>:</p>
			<p class="res-array"><strong><?=$arResult[PROPERTIES][SIZE][VALUE];?></strong></p>
		</div>
		<?
		}
		
		if($arResult[PROPERTIES][MATERIAL][VALUE]){
		?>
		<div class="property">
			<p class="key-array"><?=$arResult[PROPERTIES][MATERIAL][NAME]?>:</p>
			<p class="res-array"><strong><?=$arResult[PROPERTIES][MATERIAL][VALUE];?></strong></p>
		</div>
		<?
		}
		
		if($arResult[PROPERTIES][DRAWING][VALUE]){
		?>
		<div class="property">
			<p class="key-array"><?=$arResult[PROPERTIES][DRAWING][NAME]?>:</p>
			<p class="res-array"><strong><?=$arResult[PROPERTIES][DRAWING][VALUE];?></strong></p>
		</div>
		<?
		}
		
		if($arResult[PROPERTIES][COLOR][VALUE]){
		?>
		<div class="property">
			<p class="key-array"><?=$arResult[PROPERTIES][COLOR][NAME]?> <span>(<?=$arResult[PROPERTIES][COLOR][VALUE];?>)</span>:</p>
			<p class="res-array">
				<?
				$color = $arResult[PROPERTIES][COLOR][VALUE_ENUM_ID];
				?>
				<strong<? if(($color!=33)AND($color!=15))echo " style='color:".$arResult[PROPERTIES][COLOR][VALUE_XML_ID]."'"?>><?=$arResult[PROPERTIES][COLOR][VALUE];?></strong>
			</p>
		</div>
		<?
		}
		?>
		<div class="property">
			<a href="javascript:void(0)" onClick="elementLikes(<?=$arResult[ID]?>)" class="likes-prod" title="нажмите, если вам нравится этот продукт"><i class="glyphicon glyphicon-heart"></i> <strong><?=number_format(intval($arResult[PROPERTIES][LIKES][VALUE]), 0, '.', ' ')?></strong> людям нравится</a>
		</div>
		<? 
		if(count($commProduct) > 0){
		?>
		<div class="property">
			<a href='javascript:void(0)' onClick="elementScroll('#commentProduct')" class='countComment'>Отзывов о товаре: <strong><?=number_format(count($commProduct), 0, '.', ' ')?></strong></a>
		</div>
		<?
		}
		?>
	</div>
	<?
	if(count($arrSize) > 1){
	?>
	<div class="size">
		<div class='col-lg-3 col-md-3 col-sm-4 col-xs-4'>
			<strong>Размеры: </strong>
		</div>
		<div class='col-lg-9 col-md-9 col-sm-8 col-xs-8'>
			<div class="sortes">
			<?
			foreach($arrSize as $val){
				if($arResult[ID]==$val[ID]){
					echo $val[PROPERTY_SIZE_VALUE];
					break;
				}
			}
			?>
			</div>
			<div id="section_menu">
				<?
				$end = end($arrSize);
				foreach($arrSize as $val){
				?>
				<a href='javascript:void(0);' rel="nofollow" onClick="javascript:elementSize(<?=intval($val[ID])?>)" class="mrow<? if($arResult[ID]==$val[ID])echo " act"?>"><i class="glyphicon glyphicon-resize-full"></i> <?=$val[PROPERTY_SIZE_VALUE]?></a>
				<?
				if($end[ID] != $val[ID])
					echo '<div class="sep"></div>';
				}
				?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?
	}
	?>
	<?
	if(count($arrCombiColor) > 1){
	?>
	<div class="color">
		<div class='col-lg-3 col-md-3 col-sm-4 col-xs-4'>
			<strong>Цвета: </strong>
		</div>
		<div class='col-lg-9 col-md-9 col-sm-8 col-xs-8'>
			<div class="sortes-color">
			<?=$arResult[PROPERTIES][COLOR][VALUE]?>
			</div>
			<div id="section_menu_color">
				<?
				$end = end($arrCombiColor);
				foreach($arrCombiColor as $val){
				?>
				<a href='javascript:void(0);' rel="nofollow" onClick="javascript:elementSize(<?=intval($val[ID])?>)" class="mrow<? if($arResult[ID]==$val[ID])echo " act"?>"><i class="glyphicon glyphicon-tint" <?=(($val[PROPERTY_COLOR_ENUM_ID]!=33)AND($val[PROPERTY_COLOR_ENUM_ID]!=15))?" style='color:".$val[VALUE_XML_ID]."'":""?>></i> <?=$val[PROPERTY_COLOR_VALUE]?></a>
				<?
				if($end[ID] != $val[ID])
					echo '<div class="sep"></div>';
				}
				?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?
	}
	?>
	<div class="detail-price">
		<div class="price">Цена: <strong><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arResult[PRICES][BASE][PRINT_VALUE])?></strong></div>
        <?
		if($arResult[CATALOG_QUANTITY] > 0){
			$inBasket = false;
			foreach($_SESSION[PRODUCT_ID_IN_BASKET] as $idItemBasket){
				if($idItemBasket == $arResult[ID]){
					$inBasket = "Уже в корзине";
				}
			}
			if($inBasket){
				$dbBasketItems = CSaleBasket::GetList(
				array(),
				array("FUSER_ID" => $FUSER_ID,"LID" => SITE_ID,"ORDER_ID" => "NULL","PRODUCT_ID" => $arResult[ID]),
				false,
				false,array("QUANTITY","CURRENCY","PRICE"));
				while ($arItems = $dbBasketItems->Fetch()){
					$INBASKET_CNT = $arItems[QUANTITY];
					$PRICE_BASKET = CurrencyFormat($arItems["PRICE"]*$arItems[QUANTITY], $arItems["CURRENCY"]);
				}
			}
		}
		$price = $quantityprice = false;
		if(($arResult[PROPERTIES][DISCONT][VALUE])AND($DISCONT_CARD)AND(!$arResult[PROPERTIES][AKCII][VALUE_XML_ID])AND(!$arResult[PRICES][OLD][VALUE])AND($arResult[PROPERTIES][STATUS][VALUE_ENUM_ID] != 114)){
			$COUPON = 0;	
			if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
				$COUPON = $BIRTHDATE_DISCONT;
			}elseif($COUPON_USER > 0){
				$COUPON = $COUPON_USER;
			}
			$discont_sum = ceil(($COUPON * $arResult[PRICES][BASE][VALUE])/100);
			$price = ceil($arResult[PRICES][BASE][VALUE] - $discont_sum);
		}
		if($price){
			$price_view = ($PRICE_BASKET)?$PRICE_BASKET:CurrencyFormat($price,$arResult[PRICES][BASE][CURRENCY]);
			echo "<div class='price itog'>Итого: <strong id='pp'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $price_view)."</strong></div>";
			$quantityprice = $price;
		}else{
			$price_view = ($PRICE_BASKET)?$PRICE_BASKET:$arResult[PRICES][BASE][PRINT_VALUE];
			echo "<div class='price itog'>Итого: <strong id='pp'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $price_view)."</strong></div>";
			$quantityprice = $arResult[PRICES][BASE][VALUE];
		}
		if($arResult[PRICES][OLD][PRINT_VALUE]){
		?>
			<div class='price old'>Старая цена: <strong><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arResult[PRICES][OLD][PRINT_VALUE])?></strong></div>
		<?
		}
		?>
	</div>
	
	<div class="btn-box">
		<?
		if($arResult[CATALOG_QUANTITY] > 0){
			$inBasket = false;
			foreach($_SESSION[PRODUCT_ID_IN_BASKET] as $idItemBasket){
				if($idItemBasket == $arResult[ID]){
					$inBasket = "Уже в корзине";
				}
			}
		?>
			<div class='quantity'>
				<a href="javascript:void(0);" onClick="minus('quantity','<?=$quantityprice?>',0)" class="minus"><strong><i class="glyphicon glyphicon-minus"></i></strong></a>
				<input class="counter" type="text" id="quantity" name="quantity" value="<?=($INBASKET_CNT)?$INBASKET_CNT:1?>" />
				<a href="javascript:void(0);" onClick="plus('quantity','<?=$quantityprice?>','<?=intval($arResult[CATALOG_QUANTITY])?>',0)" class="plus"><strong><i class="glyphicon glyphicon-plus"></i></strong></a>
			</div>
			<div class="add-buy">
				<a href="javascript:void(0);" rel="nofollow" id="btn-add-<?=$arResult[ID]?>" onClick="yaCounter43070549.reachGoal('ADD_BASKET'); basketElementAdd(<?=$arResult[ID]?>,quantity.value);  return false;"><?=($inBasket) ? $inBasket : "В корзину";?></a>
			</div>
		<?
		}else{
		?>
			<div class="not-available">
				<a href="javascript:void(0);" rel="nofollow" id="btn-av-<?=$arResult[ID]?>" onClick='subscribeElementAdd(<?=$arResult[ID]?>)'>Оповестить</a>
			</div>
		<?
		}
		if(CUser::GetID()){
		?>
		<div class="add-like<?=($inLike)?" act":""?>" id="add-like-<?=$arResult[ID]?>">
			<a href="javascript:void(0);" rel="nofollow" title="<?=($inLike)?"Товар сохранен в избранное, удалить?":"Сохранить товар в избранное"?>" id="btn-like-<?=$arResult[ID]?>" onClick="elementFavorites(<?=intval($inLikeID)?>,<?=$arResult[ID]?>,'<?=($inLike)?"del":"add"?>')"><i class="glyphicon <?=($inLike) ? "glyphicon-heart-empty" : "glyphicon-heart";?>"></i></a>
		</div>
		<?
		}
		?>
	</div><!--btn-box-->
	<?
	$arrSect = array();
	$res = CIBlockElement::GetElementGroups($arResult[ID], true); 
	while($ob = $res->Fetch()){
		//echo '<pre>';print_r($ob);echo '</pre>';
		$arrSect[] = $ob;
	}
	if(!empty($arrSect)){
	?>
	<div class="other-categories">
		<h4>Другие категории:</h4>
		<ul>
			<?
			foreach($arrSect as $v){
			if($arResult[SECTION]['ID'] == $v[ID])	
			echo "<li><a href='".DOMAIN."/".$v[CODE]."/' class='act'>".$v[NAME]."</a></li>\n" ;
			else
			echo "<li><a href='".DOMAIN."/".$v[CODE]."/'>".$v[NAME]."</a></li>\n" ;
			}
			?>
		</ul>
	</div>
	<?
	}
	?>
	<div class="delivery-box"></div>
	<?
	
	if(strlen($arResult[DETAIL_TEXT]) > 10){
	?>
	<div class="detail-text"><?=($arResult[DETAIL_TEXT_TYPE] == 'html')?$arResult[DETAIL_TEXT]:"<p>".$arResult[DETAIL_TEXT]."</p>"?></div>
	<?
	}
	?>
	
</div><!--detail-prop-box-->
<div class="clear"></div>
<div class='col-xs-12 comment-product no-padding' id="commentProduct">
	<h3><strong>Отзывы о товаре: </strong><?=$arResult[NAME]?></h3>
	
	<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
	<?
	if(!empty($commProduct)){
		foreach($commProduct as $val){
			?>
			<div class='comment'>
				<div class='name-date'>
					<div class='name'><?=$val[NAME];if($val[PROPERTY_CITY_VALUE])echo", ".$val[PROPERTY_CITY_VALUE]?></div>
				</div>
				<p class='text-comm'><?=$val[PREVIEW_TEXT]?></p>
			</div>
			<?
		}
	}
	?>
	</div>
	<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 form-comment-box'>
		<h6>Вы можете оставить отзыв</h6>
		<p class="text">
		Используйте данную форму только для того, чтобы оставить отзыв о товаре.<br><br>
		<strong>Комментарий будет опубликован после проверки.</strong><br><br>
		<span style="color:RED">Поля со * обязательны для заполнения.</span>
		</p>
		<form action="javascript:void(0);" method="post" class="form-box" id="elementReviewsForm">
			<input name="ID_ITEM" id="ID_ITEM" type="hidden" value="<?=$arResult[ID]?>">
			<input name="FIO" type="text" id="FIO" placeholder="Ваше имя*" value='<?=$USER->GetParam("NAME")?>'>
			<input name="MAIL" id="MAIL" type="text" placeholder="Ваша эл. почта" value='<?=$USER->GetParam("EMAIL")?>'>
			<input name="PHONE" id="PHONE" class="mask" type="text" placeholder="Ваш номер телефона 8(___)__-__-___" value=''>
			<textarea name="TEXT" id="TEXT" placeholder="Текст сообщения*"></textarea>
            <div class="sog">Нажимая на кнопку <strong>"Добавить отзыв"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/personal-data-policy/" target="_blank">в соответствии с условиями</a></div>
			<button name="submit" type="button" class="add-comm-element" id="btnRev" onclick="javascript:sendData('elementComment.php','elementReviewsForm','resAction','btnRev','Добавить отзыв'); return false;"><strong>Добавить отзыв</strong></button>
		</form>
	</div>
	<div class="clear"></div>
	
</div>
<div class="clear"></div>
<script>
$('.h1-head').html('<span class="before"></span><?=$arResult['NAME']?><span class="after"></span>');
$('.detail-img, .detail-more-img').slickLightbox();
if ($(".detail-more-img").size()>0) {
	$(".detail-more-img").slick({
		dots: false,
		infinite: false,
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplaySpeed: 5000,
		autoplay: true,
		adaptiveHeight: true
	});
};
elementBasketAddDeliveryList();
$(".sortes").click(function(){
	if(!$("#section_menu").hasClass('shown')){
		$("#section_menu").addClass('shown');
		$("#section_menu_color").removeClass('shown');
	}else{
		$("#section_menu").removeClass('shown');
	}
});
$(".sortes-color").click(function(){
	if(!$("#section_menu_color").hasClass('shown')){
		$("#section_menu_color").addClass('shown');
		$("#section_menu").removeClass('shown');
	}else{
		$("#section_menu_color").removeClass('shown');
	}
});
if ($(".mask").size()>0) {
	$(".mask").mask("8(999)999-99-99");
}
window.history.pushState(null, null, "<?=DOMAIN.$arResult[DETAIL_PAGE_URL]?>");

</script>