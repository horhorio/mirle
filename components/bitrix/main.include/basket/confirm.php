<?
$dbR = $DB->Query("SELECT ID,USER_ID FROM b_sale_order WHERE ACCOUNT_NUMBER='".$_GET[ORDER_ID]."' OR ID='".$_GET[ORDER_ID]."'");
$arR = $dbR->Fetch();
//echo '<pre>';print_r($arR);echo '</pre>';
$cur_user = CUser::GetID();
if($arR[ID]){
	$arOrder = CSaleOrder::GetByID($arR[ID]);
	//echo '<pre>';print_r($arOrder);echo '</pre>';
	$dbBasketItems = CSaleBasket::GetList(
		array(),
		array("LID" => SITE_ID,"ORDER_ID" => $arR[ID]),
		false,false,
		array("*")
	);
	while ($arItems = $dbBasketItems->Fetch()){
		//echo '<pre>';print_r($arItems);echo '</pre>';
		$price = false;
		if(intval($arItems[DISCOUNT_VALUE])){
			$discont_sum = ceil(($arItems[DISCOUNT_VALUE] * $arItems["BASE_PRICE"])/100);
			$price = ceil($arItems["BASE_PRICE"] - $discont_sum);
			$arFields = array("PRICE" => $price,"DISCOUNT_PRICE" => $discont_sum);
			CSaleBasket::Update($arItems[ID], $arFields);
		}else{
			$price = $arItems["PRICE"];
		}
		$totalPrice += $price * intval($arItems["QUANTITY"]);
		
	}
	if(($_SESSION[DATA_PROMOCODE])AND($_SESSION[DATA_PROMOCODE][IBLOCK_ID] == 12)){
		//echo '<pre>';print_r($_SESSION[DATA_PROMOCODE]);echo '</pre>';
		//die;
		$discont_sum = ceil(($_SESSION[DATA_PROMOCODE][DISCONT] * $totalPrice)/100);
		$totalPrice = ceil($totalPrice - $discont_sum);
	}
	if(($_SESSION[DATA_PROMOCODE])AND($_SESSION[DATA_PROMOCODE][IBLOCK_ID] == 15)){
		//echo '<pre>';print_r($_SESSION[DATA_PROMOCODE]);echo '</pre>';
		//die;
		$totalPrice = ceil($totalPrice - $_SESSION[DATA_PROMOCODE][DISCONT]);
	}
	
	$totalAllPrice = $totalPrice + $arOrder[PRICE_DELIVERY];
	//die;	
	$arFields = array(
		"PRICE" => $totalAllPrice,
		"DISCOUNT_VALUE" => $discont_sum
	);
   	CSaleOrder::Update($arR[ID], $arFields);
	
	if((!$cur_user)AND($arOrder[USER_ID] == 3)){
		$cur_user = $arOrder[USER_ID];
		$USER = new CUser;
		$USER->Authorize($cur_user);
	}
	if(($cur_user) AND ($cur_user == $arOrder[USER_ID])){
		
		$db_props = CSaleOrderPropsValue::GetOrderProps($arR[ID]);
		while ($arProps = $db_props->Fetch()){
			//echo '<pre>';print_r($arProps);echo '</pre>';
			if($arProps[CODE] == 'EMAIL')
				$EMAIL = $arProps[VALUE];
			if($arProps[CODE] == 'FIO')
				$FIO = $arProps[VALUE];
			if($arProps[CODE] == 'PHONE')
				$PHONE = $arProps[VALUE];
			if($arProps[CODE] == 'CITY')
				$CITY = $arProps[VALUE];
			if($arProps[CODE] == 'ADDRESS')
				$ADDRESS = $arProps[VALUE];
			if($arProps[CODE] == 'COMMENT')
				$COMMENT = $arProps[VALUE];
		}
		//unset($_SESSION[DATA_PROMOCODE]);
		?>
        <div class="order-confirm">
        	<div class='alert_success'>Заказ успешно сформирован, спасибо за покупку в нашем интернет-магазине! Ждем Вас еще.</div>
            <h2 class="thank"><?=$FIO?>, спасибо за заказ!</h2>
            <p class="slog">В течение ближайшего времени мы позвоним вам для уточнения условий и сроков доставки. Наши операторы работают с 10.00 до 19.00 по будням</p>
            
            <div class="info-ord">
                <p><strong>Информация о заказе:</strong></p>
                <p>Номер заказа: <strong><?=$_GET[ORDER_ID]?></strong><?=(!is_numeric($_GET[ORDER_ID]))?" или <strong>( ".$arR[ID]." )</strong>":""?></p>
                <p>Получатель: <strong><?=$FIO?></strong></p>
                <p>Телефон: <strong><?=$PHONE?></strong></p>
                <?
				if($ADDRESS)
					echo "<p>Адрес доставки: <strong>г. ".$CITY.", ".$ADDRESS."</strong></p>";
                //if($cur_user != 3){
					$arDelivSys = CSaleDelivery::GetByID($arOrder[DELIVERY_ID]);
					$arPaySys = CSalePaySystem::GetByID($arOrder[PAY_SYSTEM_ID]);
                ?>
                	<p>Доставка: <strong><?=$arDelivSys[NAME]?></strong></p>
                    <p>Оплата: <strong><?=$arPaySys[NAME]?></strong></p>
                <?
                //}
                ?>
            </div>
            <div class="price-ord">
			<?
            if($arOrder[PRICE_DELIVERY] > 0){
                echo "Итого: <strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($totalPrice, $arOrder[CURRENCY]))."</strong><br>";
                echo "Доставка: <strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arOrder[PRICE_DELIVERY], $arOrder[CURRENCY]))."</strong><br>";
                echo "Итого с доставкой: <strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($totalPrice+$arOrder[PRICE_DELIVERY], $arOrder[CURRENCY]))."</strong>";
            }else{
                echo "Итого: <strong>".str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($totalPrice, $arOrder[CURRENCY]))."</strong><br>";
            }
            ?>
            </div>
            
            <div class="product-prd">
            	<a href="javascript:void(0)" class="confirm-view-prod" rel="nofollow">Посмотреть список заказанных товаров</a>
                <div class="product-prd-list">
                	<div class="el">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <p><b>Товар</b></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
                            <p class="centered"><b>Цена</b></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
                            <p class="centered"><b>Кол-во</b></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
                            <p class="centered"><b>Итого</b></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?
                    $dbBasket = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arOrder[ID]));
                    while ($arBasket = $dbBasket->Fetch()){
                        //echo "<pre>"; print_r($arBasket); echo "</pre>";
                    ?>
                    <div class="el">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <p><a href="<?=DOMAIN.$arBasket[DETAIL_PAGE_URL]?>" target="_blank"><?=$arBasket[NAME]?></a></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <p class="centered"><?=CurrencyFormat($arBasket[PRICE], $arBasket[CURRENCY]);?></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <p class="centered"><?=(int)$arBasket[QUANTITY]?> шт.</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <p class="centered"><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arBasket[PRICE] * $arBasket[QUANTITY], $arBasket[CURRENCY])); ?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?
                    }
                    ?>
                </div>
            </div>
            <?
            if(CUser::GetID()!=3){
					?>
					<a href="<?=DOMAIN?>/personal/order/" class="pr-us">Перейти в личный кабинет</a>
					<?
					if(($arOrder[PAYED] != "Y")AND($arOrder[STATUS_ID] != "P")AND($arOrder[PAY_SYSTEM_ID] == 2)){
					?>
					<a href="<?=DOMAIN?>/cart/pay/?ORDER_ID=<?=$arR[ID]?>" class="checkout"><strong>Оплатить заказ</strong></a>
					<?
					}else{
						if(($arOrder[PAYED] == "Y")OR($arOrder[STATUS_ID] == "P")){
					?>
						<div class='alert_success' style="margin-top:15px">Заказ уже оплачен!</div>
					<?
						}
					}
			}
			?>
        </div>
        <?
	}else{
		echo "<div class='col-xs-12'><div class='alert_warning'>К сожалению, Вы не можете просматривать данные заказа <strong>№ ".$_GET[ORDER_ID]."</strong>. <a href='".MAIN_URL_CATALOG."'><i class='glyphicon glyphicon-eye-open'></i> Посмотреть каталог</a></div></div><div class='clear'></div>";
		?>
        <div class="reg-box" style="margin-top: 0px;">
        	<div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'></div>
            <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 auth'>
            <h3>Войти в личный кабинет пользователя данного заказа</h3>
            <form method="post" action="javascript:void(0);" id="userLogInForm">
                <input type="hidden" name="back_url" value="<?=$_SERVER['REQUEST_URI']?>">
                <input type="checkbox" name="CAPTCHA_AUTH">
                <label>Эл. почта:</label>
                <input type="text" name="MAIL_AUTH"  id="MAIL_AUTH" placeholder="Введите ваш адрес эл. почты">
                <label>Пароль:</label>
                <input type="password" name="PASS_AUTH"  id="PASS_AUTH" placeholder="Введите ваш пароль">
                <button name="submit" type="submit" class="flat_button" id="btnLogIn" onclick="javascript:sendData('userLogIn.php','userLogInForm','resAction','btnLogIn','Вход'); return false;"><strong>Вход</strong></button>
                <div class="clear"></div>
            </form>
            </div>
            <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'></div>
            <div class='clear'></div>
        </div>
        <?
	}
}else{
	echo "<div class='col-xs-12'><div class='alert_warning'>К сожалению, заказ с таким номером не найден. <a href='".MAIN_URL_CATALOG."'><i class='glyphicon glyphicon-eye-open'></i> Посмотреть каталог</a></div></div><div class='clear'></div>";
}
?>