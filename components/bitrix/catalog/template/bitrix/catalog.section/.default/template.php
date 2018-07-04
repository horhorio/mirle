<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.ui.touch-punch.js"></script>
<?
//if($USER->IsAdmin()){
//echo '<pre>'; print_r($arResult); echo '</pre>';
//}
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

$region = $_SESSION['dataCity'][city];

$dir = $APPLICATION->GetCurDir();
$rsResult = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arResult[IBLOCK_ID],"ID" => $arResult[ID]), false, $arSelect = array("DESCRIPTION","UF_SECT_TDESC","UF_SECT_H1"));
if($arRes = $rsResult -> GetNext()){
	//echo '<pre>'; print_r($arRes); echo '</pre>';
	$DESCRIPTION = $arRes[DESCRIPTION];
	$DESCRIPTION_TOP = $arRes[UF_SECT_TDESC];
	$SECT_NAME = $arRes[UF_SECT_H1];
}
?>
<input type="hidden" id="section_id" value="<?=$arResult[ID]?>">
<div class="sections-elements wrapp">
    <div class="col-xs-12">
        <h1 class="h1-head" title="<?=($SECT_NAME) ? $SECT_NAME : $arResult['NAME']?>">
        <span class="before"></span>
        <?=($SECT_NAME) ? mb_strimwidth($SECT_NAME, 0, 40, "... ", "utf-8") : $arResult['NAME']?>
        <span class="after"></span>
        </h1>
        <div class="section row margin-top-30">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filter">
                <? include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/filter.php");?>
            </div>
            
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 elements">
                <?
				if((strlen($DESCRIPTION_TOP) > 50)AND(empty($_GET))){
					echo "<p class='top-sect-desc hidden-sm hidden-xs'>".mb_strimwidth($DESCRIPTION_TOP, 0, 350, "... ", "utf-8")."</p>";
				}

				?>
                <div class="goods goods-list row">
                    <?
					if(empty($arResult[ITEMS])){
						echo "<div class='alert_warning margin-30-15'>К сожалению, раздел временно пуст, попробуйте зайти позже.</div>";
						$APPLICATION->IncludeComponent(
							"bitrix:sale.viewed.product",
							"for-sect-product",
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
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/sorting.php");
					foreach($arResult[ITEMS] as $arElement){
					?>
                    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-6 elem'>
                        <div class="product shadow">
                            <div class="product-hover"></div>
                            <?
							$margintop = false;
							if(($arElement[PROPERTIES][STATUS][VALUE_XML_ID])AND(!$arElement[PROPERTIES][AKCII][VALUE_XML_ID]))
								echo "<div class='status-goods ".$arElement[PROPERTIES][STATUS][VALUE_XML_ID]."'><span>".$arElement[PROPERTIES][STATUS][VALUE]."</span><i></i></div>";
					
							if($arElement[PROPERTIES][AKCII][VALUE_XML_ID])
								echo "<div class='status-goods ".$arElement[PROPERTIES][AKCII][VALUE_XML_ID]."'><span>".$arElement[PROPERTIES][AKCII][VALUE]."</span><i></i></div>";
							if(($arElement[PROPERTIES][DISCONT][VALUE])AND($DISCONT_CARD)AND(!$arElement[PROPERTIES][AKCII][VALUE_XML_ID])AND(!$arElement[PRICES][OLD][VALUE])AND($arElement[PROPERTIES][STATUS][VALUE_ENUM_ID] != 114)){
								if($arElement[PROPERTIES][STATUS][VALUE_XML_ID])
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
                                /*foreach($arElement[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE] as $b){
									$MANUFACTURER = $b[NAME];
								}*/
								?>
                                <p class="collection">« <?=$arElement[DISPLAY_PROPERTIES][MANUFACTURER][LINK_ELEMENT_VALUE][$arElement[DISPLAY_PROPERTIES][MANUFACTURER][VALUE]][NAME]?> »</p>
                                <p class="name" title="<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?>"><?=mb_strimwidth($arElement[NAME], 0, 65, "... ",'utf-8')?></p>
                                </a>
                                <?
								if($arElement[PROPERTIES][SIZE][VALUE])
                                	echo "<p class='size'>".$arElement[PROPERTIES][SIZE][VALUE]."</p>";
								?>
                                <div class="price">
                                    <?
									$price = false;
									if(($arElement[PROPERTIES][DISCONT][VALUE])AND($DISCONT_CARD)AND(!$arElement[PROPERTIES][AKCII][VALUE_XML_ID])AND(!$arElement[PRICES][OLD][VALUE])AND($arElement[PROPERTIES][STATUS][VALUE_ENUM_ID] != 114)){
										$COUPON = 0;	
										if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
											$COUPON = $BIRTHDATE_DISCONT;
										}elseif($COUPON_USER > 0){
											$COUPON = $COUPON_USER;
										}
										$discont_sum = ceil(($COUPON * $arElement[PRICES][BASE][VALUE])/100);
										$price = ceil($arElement[PRICES][BASE][VALUE] - $discont_sum);
										
										if($arElement[PRICES][BASE][VALUE] > $price){
											echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][BASE][PRINT_VALUE])."</span>";
											echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($price,$arElement[PRICES][BASE][CURRENCY]))."</span>";
										}else{
											echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][BASE][PRINT_VALUE])."</span>";
										}
									}else{
										if($arElement[PRICES][OLD][PRINT_VALUE])
                                        	echo "<span class='old'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][OLD][PRINT_VALUE])."</span>";
											echo "<span class='current'>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', $arElement[PRICES][BASE][PRINT_VALUE])."</span>";
									}
                                    ?>
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
					}//if(empty($arResult[ITEMS])){
					?>
                    <div class="clear"></div>
                    
                </div>
            </div>
			<?
            if((strlen($DESCRIPTION) > 50)AND(empty($_GET))){
                echo '<div class="col-xs-12 hidden-sm hidden-xs bottom-sect-desc">';
                echo $DESCRIPTION;
                echo "</div>";
				echo '<div class="clear"></div>';
            }
            ?>
            
            

            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?
$end = end($arResult[ITEMS]);
//echo '<pre>'; print_r($parrentSect); echo '</pre>';
$endSect = end($parrentSect);
?>
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
		<?
		echo($endSect[ID] != $v[ID])?"},":"}";
		}
		?>
	]
}
</script>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Product",
	"name": "<?=($row['UF_TITLE']) ? $row['UF_TITLE'] : $arResult['NAME']?>",
	"url":"<?=DOMAIN.$arResult['CURRENT_BASE_PAGE']?>",
	"aggregateRating": {
    	"@type": "AggregateRating",
    	"ratingValue": "4.5",     
    	"reviewCount": "10"
    },
	"offers": [
		<?
		foreach($arResult[ITEMS] as $arElement){
		?>
		{
			"@type": "Offer",
			"url": "<?=DOMAIN.$arElement[DETAIL_PAGE_URL]?>",
			"name": "<?=str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arElement[NAME]))?> <?=$arElement[PROPERTIES][SIZE][VALUE]?>",
			"image": "<?=DOMAIN.$arElement[PREVIEW_PICTURE][SRC]?>",
			"priceCurrency": "<?=$arElement[PRICES][BASE][CURRENCY]?>",
			"price": "<?=$arElement[PRICES][BASE][VALUE]?>",
			"availability": "В наличии"  
		<?
		echo ($end[ID] != $arElement[ID])?"},":"}";
		}
		?>			
	]      
}
</script>
<script>
$( "#slider-range" ).slider({
	range: true,
	step: 100,
	min: <?=$arResult[minmax_price][MIN]?>,
	max: <?=$arResult[minmax_price][MAX]?>,
	values: [<?=($_GET[PRICE_MIN])?($_GET[PRICE_MIN]):$arResult[minmax_price][MIN]?>, <?=($_GET[PRICE_MAX])?($_GET[PRICE_MAX]):$arResult[minmax_price][MAX]?> ],
	slide: function( event, ui ) {
		$( "#amountMin" ).val(ui.values[ 0 ]);
		$( "#amountMax" ).val(ui.values[ 1 ]);
		$( ".wt-range .res .left strong" ).text(number_format(ui.values[ 0 ], 0, ".", " "));
		$( ".wt-range .res .right strong" ).text(number_format(ui.values[ 1 ], 0, ".", " "));
	},
	change: function(event, ui) {
		//$('#amountMax')
		var strNoGet = window.location.pathname;
		var strGet = window.location.search.replace( '?', '');
		var name = $(this).attr('name');
		var value = encodeURI($(this).val());
		var res = '';
		var new_url = '';
		var keyval = '';
		var params = '';
		var url = location.href;
		var zn = '';
		params = strGet.split("&");
		for(var i = 0; i < params.length; i++) { 
			keyval = params[i].split("="); 
			if(keyval[0] != "PRICE_MIN" && keyval[0] != "PRICE_MAX") { 
				res += "&"+params[i];
			}
		}
		
		res = res.substr(1);
		//alert(res);
		if(res != '')
			new_url = strNoGet+"?"+res;
		else
			new_url = strNoGet;
			
		if(res != "")
			zn = '&';
		else
			zn = '?';
			
		var urldata = new_url+""+zn+"PRICE_MIN="+$( "#amountMin" ).val()+"&PRICE_MAX="+$( "#amountMax" ).val();  
		window.history.pushState(null, null, urldata);
		var sect = parseInt($('#section_id').val());
		if(sect > 0){
			$.ajax({ 
				type: "POST",
				url: "/ajax/elementFilter.php",
				data: {SECTION: sect,GET_PARAM: window.location.search.replace( '?', '')},
				beforeSend: function(data){
					$("#resAction").html("<div class='overload'><div class='load'></div></div>");
				},
				success: function(data){
					$('.goods-list').animate({'opacity':'0'},500);
					setTimeout(function() { 
						$('.goods-list').html(data);
						$("#resAction").empty();
						$('html, body').animate({scrollTop: $('.elements').offset().top}, 500);
					}, 500);
					$('.goods-list').animate({'opacity':'1'},500);
	
				},
				error: function(data){
					$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
					setTimeout(function(){$('.alert_error_add_item').remove()},5000);
				}
			});
		}
	}
});
</script>