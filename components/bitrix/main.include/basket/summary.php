<p class="top-desc">
Нажмите <a href="<?=DOMAIN?>/cart/cart-prop/">"Оформить заказ"</a>, чтобы выбрать условия доставки и оплаты.<?=($arC)?'Или <a href="javascript:void(0)" onclick="orderOneClickBasket()">"В один клик"</a>, чтобы наш оператор сделал это за Вас по телефону.':''?>
</p>
<?
foreach($arResult["ITEMS"] as $key => $arElement){
	$arProduct = CIblockElement::GetById($arElement[PRODUCT_ID])->GetNext(); 
	
	$STATUS = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"STATUS"));
	if($ar_props = $db_props->Fetch()){
		$STATUS = $ar_props[VALUE_ENUM];
		$STATUS_XML_ID = $ar_props[VALUE_XML_ID];
	}
	$MANUFACTURER = false;
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"MANUFACTURER"));
	if($ar_props = $db_props->Fetch()){
		$MANUFACTURER = CIblockElement::GetById($ar_props["VALUE"])->GetNext();
	}
	$SIZE = false;	
	$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arElement[PRODUCT_ID], array(), Array("CODE"=>"SIZE"));
	if($ar_props = $db_props->Fetch()){
		$SIZE = $ar_props["VALUE"];
	}
	//echo '<pre>';print_r($ar_props);echo '</pre>';
?>
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6" id="elb_<?=$arElement[ID]?>">
        <div class="product shadow">
            <div class="product-hover"></div>
            <?
			$margintop = false;
			if($STATUS_XML_ID)
				echo "<div class='status-goods ".$STATUS_XML_ID."'><span>".$STATUS."</span><i></i></div>";
			if(($arElement[DISCOUNT_VALUE])AND($_SESSION[DATA_PROMOCODE][IBLOCK_ID] != 12)){
				if($STATUS_XML_ID)
					$margintop = ' margintop';
				echo "<div class='discount".$margintop."'><span>Скидка -".$arElement[DISCOUNT_VALUE]."%</span><i></i></div>";
			}
			?>
            <div class="product-bg">
                <div class="product-img">
                    <a class="quick-view" href="javascript:void(0);" onclick="elementSynopsis(<?=$arElement[PRODUCT_ID]?>)" id="q-<?=$arElement[PRODUCT_ID]?>" rel="nofollow">Быстрый просмотр</a>
                    <?
					if(($arProduct[DETAIL_PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arProduct[DETAIL_PICTURE])))){
						echo"<img src='".DOMAIN.CFile::GetPath($arProduct[DETAIL_PICTURE])."' class='responsive-img'/>";
					}else{
						echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
					}
					?>                                       
                    </a>
                </div>
                <a href="<?=DOMAIN.$arProduct[DETAIL_PAGE_URL]?>">
                	<p class="collection">« <?=$MANUFACTURER[NAME]?> »</p>
                	<p class="name"><?=$arProduct[NAME]?></p>
                </a>
                <p class="size"><?=$SIZE?></p>                                
                <div class="price">
                    <?
                    if($arElement[DISCOUNT_VALUE]){
						//$arPrice = CPrice::GetBasePrice($arElement[PRODUCT_ID]);
						if($arElement[BASE_PRICE] > $arElement[PRICE]){
							echo "<div class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arElement[BASE_PRICE]*$arElement[QUANTITY], $arElement[CURRENCY]))."</div>";
							echo "<div class='current'><strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arElement[PRICE]*$arElement[QUANTITY], $arElement[CURRENCY]))."</strong></div>";
						}else{
							echo "<div class='current'><strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arElement[PRICE]*$arElement[QUANTITY], $arElement[CURRENCY]))."</strong></div>";
						}
					}else{
						echo "<div class='current'><strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arElement[PRICE]*$arElement[QUANTITY], $arElement[CURRENCY]))."</strong></div>";
					}
					?>
                </div>
                <div class="pm-box">
                    <?
                    if($arElement[DISCOUNT_VALUE] <= BIRTHDATE_DISCONT){
					?>
                    <div class='col-xs-3 no-padding'>
                    <a href="javascript:void(0);" onClick="minus('quantity-<?=$arElement[ID]?>','<?=$arElement[PRICE]?>',<?=$arElement[ID]?>)" class="minus"><strong><i class="glyphicon glyphicon-minus"></i></strong></a>
                    </div>
                    <div class='col-xs-6 padding-10'>
                    <input class="counter" type="text" id="quantity-<?=$arElement[ID]?>" value="<?=intval($arElement[QUANTITY])?>" readonly/>
                    </div>
                    <div class='col-xs-3 no-padding'>
                    <a href="javascript:void(0);" onClick="plus('quantity-<?=$arElement[ID]?>','<?=$arElement[PRICE]?>',0,<?=$arElement[ID]?>)" class="plus"><strong><i class="glyphicon glyphicon-plus"></i></strong></a>
                    </div>
                    <div class="clear"></div>
                    <?
					}else{
						echo "<div class='gift'>Подарок</div>";
					}
					?>
                    <div class="not-available">
                        <a href="javascript:void(0);" rel="nofollow" id="btn-del-<?=$arElement[ID]?>" onclick="javascript:elementBasketDel(<?=$arElement[ID]?>)"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?
}
?>
<div class="clear"></div>
