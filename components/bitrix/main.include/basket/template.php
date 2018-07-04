<?
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

if((CModule::IncludeModule("statistic"))AND(!$_SESSION['goBasket'])AND(!$USER->IsAdmin())){
	$event1 = "eStore";
	$event2 = "goBasket";
	$event3 = $USER->Login();
	CStatistic::Set_Event($event1, $event2, $event3);
	$_SESSION['goBasket'] = CSaleBasket::GetBasketUserID();
}
$arC = false;
foreach($_SESSION['cityDelivery'] as $c){
	if($_SESSION['dataCity'][city] == $c[CITY_NAME]){
		$arC = $c[CITY_ID];
		break;
	}
}
//echo '<pre>';print_r($_SESSION[DATA_PROMOCODE]);echo '</pre>';
unset($_SESSION[BASKET]);
$allPrice = 0;
?>
<div class='wrapp'>
    <div class='basket'>
        <h1 class="h1-head"><span class="before"></span><?=($_GET['ORDER_ID'])?"Информация о заказе":"Корзина покупок"?><span class="after"></span></h1>
        <?
        if($_GET['ORDER_ID']){
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
		}else{
			$arResult["ITEMS"] = array();
			$dbBasketItems = CSaleBasket::GetList(
				array(),
				array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"),
				false,
				false,
				array("ID","PRODUCT_ID","PRICE","BASE_PRICE","CURRENCY","QUANTITY","DISCOUNT_VALUE")
			);
			while ($arItems = $dbBasketItems->Fetch()){
				//echo '<pre>';print_r($arItems);echo '</pre>';
				$ar_res = CCatalogProduct::GetByID($arItems[PRODUCT_ID]);
				if($ar_res[QUANTITY] < 1){	
					CSaleBasket::Delete($arItems[ID]);
				}else{
					$arResult["ITEMS"][] = $arItems;
					$count_pr += $arItems[QUANTITY];
					$allPrice += $arItems[PRICE] * $arItems["QUANTITY"]; 
					$PRODUCT_ID_IN_BASKET[] = $arItems[PRODUCT_ID];
				}
				$CURRENCY = $arItems[CURRENCY];
			}
			$p = str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($allPrice, $CURRENCY));
			$_SESSION[BASKET] = array("Q"=>$count_pr,"P"=>$p,"USER"=>intval(CUser::GetID()));
			$_SESSION[PRODUCT_ID_IN_BASKET] = array_unique($PRODUCT_ID_IN_BASKET);
			if(empty($arResult["ITEMS"])){
				echo "<div class='col-xs-12'>";
				echo "<div class='alert_warning'>К сожалению, Ваша корзина покупок пуста. <a href='".MAIN_URL_CATALOG."'><i class='glyphicon glyphicon-eye-open'></i> Посмотреть каталог</a></div>";
				echo "</div>";
				echo "<div class='clear'></div>";
			}else{
				echo '<div class="summary goods">';
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
					echo "<div class='border-line'></div>";
					
					echo "<div class='col-lg-7 col-md-7 col-sm-6 col-xs-12'></div>";
					echo "<div class='col-lg-5 col-md-5 col-sm-6 col-xs-12 promo'>";
						echo "<span class='promo-text'>Если у Вас есть наш промо-код, воспользуйтесь данным полем.</span>";
						echo "<div class='col-xs-".(($_SESSION[DATA_PROMOCODE])?8:10)." no-padding'>";
							echo '<input id="promo" placeholder="ML_XXXXXXXX_XX_XX_XXXX" type="text">';
						echo "</div>";
						echo "<div class='col-xs-2 no-padding'>";
							if($_SESSION[DATA_PROMOCODE])$radius = ' radius';
							echo "<a href='javascript:void(0)' class='add".$radius."' id='promoAdd' rel='nofollow' onClick='javascript:basketPromo(); return false;'><strong><i class='glyphicon glyphicon-refresh'></i></strong></a>";
						echo "</div>";
						if($_SESSION[DATA_PROMOCODE]){
							echo "<div class='col-xs-2 no-padding'>";
							echo "<a href='javascript:void(0)' class='promoAddDel' rel='nofollow' onClick='javascript:basketPromoDel(); return false;'><strong><i class='glyphicon glyphicon-trash' title='Удалить Промо-код'></i> </strong></a>";
							echo "</div>";
						}
						echo "<div class='clear'></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
					
					echo "<div class='itog'>";
					if(($_SESSION[DATA_PROMOCODE])AND($_SESSION[DATA_PROMOCODE][IBLOCK_ID] == 12)){
						//echo '<pre>';print_r($_SESSION[DATA_PROMOCODE]);echo '</pre>';
						//die;
						$discont_sum = ceil(($_SESSION[DATA_PROMOCODE][DISCONT] * $allPrice)/100);
						$allPrice = ceil($allPrice - $discont_sum);
					}
					if(($_SESSION[DATA_PROMOCODE])AND($_SESSION[DATA_PROMOCODE][IBLOCK_ID] == 15)){
						//echo '<pre>';print_r($_SESSION[DATA_PROMOCODE]);echo '</pre>';
						//die;
						$allPrice = ceil($allPrice - $_SESSION[DATA_PROMOCODE][DISCONT]);
					}
						echo "Итого: <strong>".$p = str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($allPrice, $CURRENCY))."</strong>";
					echo "</div>";
					echo "<div class='clear'></div>";
					
					echo "<div class='col-xs-12 no-padding'>";
						echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>";
						
						if($arC){
							echo "<a href='javascript:void(0)' class='checkout checkout-onclc' onclick='orderOneClickBasket()'><strong>В один клик</strong></a>";
						}
						echo "</div>";
						echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>";
							echo "<a href='".DOMAIN."/cart/cart-prop/' class='checkout'><strong>Оформить заказ</strong></a>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";
					
					echo "<div class='clear'></div>";
				echo "</div>";
			}
		}
		?>
    </div>
</div>
<script>
$(".head .my-cart span").text("<?=intval($count_pr)?>");
</script>