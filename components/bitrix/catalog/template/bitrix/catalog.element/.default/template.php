<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

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
//https://mirle.ru/postelnoe-bele-komplekty/komplekt-postelnogo-belya-classik-arabic-semejnoe/?bitrix_include_areas=N
$_SESSION[PRODUCT_VIEW][] = $arResult[ID];
$_SESSION[PRODUCT_VIEW] = array_unique($_SESSION[PRODUCT_VIEW]);

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
//echo "<pre>"; print_r($arrCombiColor); echo "</pre>";
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
$parrentSect = array();
$nav = CIBlockSection::GetNavChain(false, $arResult[SECTION]['ID'],array("ID","NAME","CODE")); 
while($res = $nav->ExtractFields("nav_")){ 
	$parrentSect[] = $res;
}
$endSect = end($parrentSect);
//echo "<pre>"; print_r($parrentSect); echo "</pre>";

$r = array("ltbgt","ltpgt","quot","nbsp","ltbrgt","ltprgt");  
$l = array("","","","",);
$description = preg_replace("/\s{2,}/"," ",str_replace($r, $l, preg_replace('/[^a-zа-я-_,.0-9\s]/ui', '', $arResult[META_TAGS][DESCRIPTION])));
$title = preg_replace("/\s{2,}/"," ",str_replace($r, $l, preg_replace('/[^a-zа-я-_,.0-9\s]/ui', '', $arResult[META_TAGS][BROWSER_TITLE])));

?>


<div class="wrapp">
    <div class='col-xs-12'>
        <h1 class="h1-head detail-product-name" title="<?=($arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE])?$arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE]:$arResult[NAME]?>">
        <span class="before"></span>
        <?=($arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE])?$arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE]:$arResult[NAME]?>
        <span class="after"></span>
        </h1>
    </div>
    <div class="clear"></div>
    <div class="product-detail">
    	<?
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
        	<?
            //if(($arResult[PROPERTIES][AKCII][VALUE])AND($arResult[PRICES][OLD][VALUE]))
				//echo "<div class='discont'>".$arResult[PROPERTIES][AKCII][VALUE]."</div>";
			?>
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
						<a href="<?=CFile::GetPath($img)?>" target="_blank"  data-caption="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT]))?>">
						<img src='<?=DOMAIN.$file[src]?>'  alt='<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arResult[PREVIEW_PICTURE][ALT]))?>' class='responsive-img'/>
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
					<p class="key-array"><?=$arResult[PROPERTIES][COLOR][NAME]?> <span>( <?=$arResult[PROPERTIES][COLOR][VALUE];?> )</span>:</p>
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
							//echo '<pre>';print_r($val);echo '</pre>';
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
				?>
                	<div class='quantity'>
                        <a href="javascript:void(0);" onClick="minus('quantity','<?=$quantityprice?>',0)" class="minus"><strong><i class="glyphicon glyphicon-minus"></i></strong></a>
                        <input name="quantity" type="text" readonly class="counter" id="quantity" value="<?=($INBASKET_CNT)?$INBASKET_CNT:1?>" />
                        <a href="javascript:void(0);" onClick="plus('quantity','<?=$quantityprice?>','<?=intval($arResult[CATALOG_QUANTITY])?>',0)" class="plus"><strong><i class="glyphicon glyphicon-plus"></i></strong></a>
                    </div>
                    <div class="add-buy">
                        <a href="javascript:void(0);" rel="nofollow" id="btn-add-<?=$arResult[ID]?>" onClick="yaCounter43070549.reachGoal('ADD_BASKET'); basketElementAdd(<?=$arResult[ID]?>,quantity.value); return false;"><?=($inBasket) ? $inBasket : "В корзину";?></a>
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
        	<h3><strong>Отзывы о товаре: </strong><?=($arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE])?$arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE]:$arResult[NAME]?></h3>
            
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
                  	<input name="PHONE" id="PHONE" class="mask" type="tel" placeholder="Ваш номер телефона 8(___)__-__-___" value=''>
                    <textarea name="TEXT" id="TEXT" placeholder="Текст сообщения*"></textarea>
                    <div class="sog">Нажимая на кнопку <strong>"Добавить отзыв"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/personal-data-policy/" target="_blank">в соответствии с условиями</a></div>
                  	<button name="submit" type="button" class="add-comm-element" id="btnRev" onclick="javascript:sendData('elementComment.php','elementReviewsForm','resAction','btnRev','Добавить отзыв'); return false;"><strong>Добавить отзыв</strong></button>
                </form>
            </div>
            <div class="clear"></div>
            
        </div>
        <div class="clear"></div>
    </div>
    <?
	if((empty($arResult[PROPERTIES][RECOMMEND][VALUE])) AND (empty($arResult[PROPERTIES][SIMILAR_PROD][VALUE]))){
		$APPLICATION->IncludeComponent(
			"bitrix:sale.viewed.product",
			"for-detail-product",
			Array(
				"VIEWED_COUNT" => "15",
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
				"PRODUCT_ID_VARIABLE" => "id",
				"SET_TITLE" => "N"
			)
		);	
	}else{
    global $bbbFilter;
    $bbbFilter = array(
        "ID"=>$arResult[PROPERTIES][SIMILAR_PROD][VALUE]
    );
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "element-similar",
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
            "FILTER_NAME" => "bbbFilter",
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
	global $bbFilter;
    $bbFilter = array(
        "ID"=>$arResult[PROPERTIES][RECOMMEND][VALUE]
    );
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "element-recomendet",
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
	}
	?>
</div>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "BreadcrumbList",
	"itemListElement": [
		{
			"@type": "ListItem",
			"position": 1,
			"item": {
	  			"@id": "<?=DOMAIN?>",
	  			"name": "Главная"
			}
		},
		<?
		foreach($parrentSect as $k => $v){
		?>
		{
			"@type": "ListItem",
			"position": <?=$k+2?>,
			"item": {
	  			"@id": "<?=DOMAIN."/".$v[CODE]."/"?>",
	  			"name": "<?=$v[NAME]?>"
			}
		},
		<?
		}
		?>
		{
			"@type": "ListItem",
			"position": <?=count($parrentSect)+2?>,
			"item": {
	  			"@id": "<?=DOMAIN.$arResult[DETAIL_PAGE_URL]?>",
	  			"name": "<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', ($arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE])?$arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE]:$arResult[NAME]))?>"
			}
		}
	]
}
</script>
<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "Product",
	"name": "<?=($arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE])?$arResult[IPROPERTY_VALUES][ELEMENT_PAGE_TITLE]:$arResult[NAME]?> - <?=SITE?>",
	"image": "<?=DOMAIN.$arResult[DETAIL_PICTURE][SRC]?>",
	"description": "<?=$description?>",
	"brand": {
		"@type": "Brand",
		"name": "<?=$arResult[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arResult[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][NAME]?>"
	},
	"offers": {
		"@type": "Offer",
		"priceCurrency": "<?=$arResult[PRICES][BASE][CURRENCY]?>",
		"price": "<?=$arResult[PRICES][BASE][VALUE]?>",
		"availability": "В наличии",
		"seller": {
			"@type": "Organization",
			"name": "<?=COMPANY?>"
		}
	},
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue": "5",
		"reviewCount": "<?=(count($commProduct) == 0)?1:count($commProduct)?>"
	}
}
</script>
