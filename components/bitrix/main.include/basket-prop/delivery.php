<?
$outCity[0][id] = false;
$out = array();
if( $curl = curl_init() ) {
	curl_setopt($curl, CURLOPT_URL, 'https://lk.pro-cour.ru/calculator.php');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "type=find&query=".$CITY."&charset=utf8");
	$outCity = json_decode(curl_exec($curl), true);
	//echo $out;
	//echo "<pre>"; print_r($out); echo "</pre>";
	curl_close($curl);
}
if(($curl = curl_init())AND($outCity[0][id])) {
	curl_setopt($curl, CURLOPT_URL, 'https://lk.pro-cour.ru/calculator.php');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "type=calc&city_id=".$outCity[0][id]."&weight=".$ORDER_WEIGHT);
	$out = json_decode(curl_exec($curl), true);
	//echo $out;
	//echo "<pre>"; print_r($out); echo "</pre>";
	curl_close($curl);
}

/*if( $curl = curl_init() ) {
	$data['version'] = "1.0";
	//дата планируемой доставки, если не установлено, берётся сегодняшний день
	$data['dateExecute'] = '2017-12-22';
	//авторизация: логин
	$data['authLogin'] = '955fd2fbfb35051f7601a320bfd21f98';
	//авторизация: пароль
	$data['secure'] = '59326eb42df11b4a5e3361325b81735c';
	//город-отправитель
	$data['senderCityId'] = 270;
	//город-получатель
	$data['receiverCityId'] = 137;
	//выбранный тариф
	$data['tariffId'] = 137;
	//список тарифов с приоритетами
	$data['tariffList'] = 11;
	//режим доставки
	$data['modeId'] = 3;
	
	$data_string = json_encode($data);
	
	curl_setopt($curl, CURLOPT_URL, 'https://integration.cdek.ru/pvzlist.php');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, 'сityid=44');
	$gg = json_decode(curl_exec($curl), true);
	//echo $gg;
	echo "<pre>"; print_r($gg); echo "</pre>";
	curl_close($curl);
}*/

/*include($_SERVER["DOCUMENT_ROOT"]."/sdek/CalculatePriceDeliveryCdek.php");
//создаём экземпляр объекта CalculatePriceDeliveryCdek
$calc = new CalculatePriceDeliveryCdek();

//Авторизация. Для получения логина/пароля (в т.ч. тестового) обратитесь к разработчикам СДЭК -->
//$calc->setAuth('authLoginString', 'passwordString');

//устанавливаем город-отправитель
$calc->setSenderCityId(137);
//устанавливаем город-получатель
$calc->setReceiverCityId(137);
//устанавливаем дату планируемой отправки
$calc->setDateExecute('2017-12-22');

//задаём список тарифов с приоритетами
$calc->addTariffPriority(137);
$calc->addTariffPriority(11);

//устанавливаем тариф по-умолчанию
//$calc->setTariffId('137');
	
//устанавливаем режим доставки
$calc->setModeDeliveryId(3);
//добавляем места в отправление
$calc->addGoodsItemBySize($ORDER_WEIGHT);
$calc->addGoodsItemByVolume($ORDER_WEIGHT);
echo "<pre>"; print_r($calc->getResult()); echo "</pre>";*/



$isdel = false;
$resDeliverySystem = CSaleDelivery::GetList(
	array("SORT" => "ASC"), 
	array(
		"LID" => SITE_ID, 
		"ACTIVE" => "Y",
		"LOCATION" => $arC,
		"+<=WEIGHT_FROM" => $ORDER_WEIGHT, 
		"+>=WEIGHT_TO" => $ORDER_WEIGHT,
		"+<=ORDER_PRICE_FROM" => $allPrice,
		"+>=ORDER_PRICE_TO" => $allPrice,
	),
	false,
	false,
	array()
);
while ($arDeliverySystem = $resDeliverySystem->Fetch()){
	if(($arDeliverySystem[NAME] == "Курьер")AND(!empty($out))AND(!$arDeliverySystem[ORDER_PRICE_FROM])){
		//$arDeliverySystem[PERIOD_FROM] = $out['min'];
		//$arDeliverySystem[PERIOD_TO] = $out['max'];
		if($arDeliverySystem[PRICE] < $out['price'])
			$arDeliverySystem[PRICE] = $out['price'];
	}
	$arResult["DELIVERY_SYSTEM"][] = $arDeliverySystem;
	
	if($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'] == $arDeliverySystem[ID])
		$isdel = $_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'];
		
}
$dbRes = CSaleDeliveryHandler::GetList(
	array('SORT' => 'ASC'), 
	array(
		'ACTIVE' => 'Y',
		"COMPABILITY" => array(
			"LOCATION_FROM" => COption::GetOptionString('sale', 'location', false, SITE_ID),
			"LOCATION_TO" => $arC,
		)
	)
);
while ($arRes = $dbRes->GetNext()){
	$arResult["DELIVERY_SYSTEM"][] = $arRes;
	//echo '<li>('.$arResult['SID'].') <b>'.$arResult['NAME'].'</b><br />';
}
if(!$isdel)
	unset($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID']);
	
if($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID']){
	$arDeliv = $_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'];
}else{
	$arDeliv = $arResult["DELIVERY_SYSTEM"][0][ID];
}

//echo '<pre>';print_r($arResult["DELIVERY_SYSTEM"]);echo '</pre>';
if(!empty($arResult["DELIVERY_SYSTEM"])){
	/*if(count($arResult["DELIVERY_SYSTEM"]) == 1){
		unset($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID']);
		$arDelivSys = CSaleDelivery::GetByID($arResult["DELIVERY_SYSTEM"][0][ID]);
		$arDelivSysAct = $arResult["DELIVERY_SYSTEM"][0][ID];
		$_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'] = $arDelivSysAct;
		echo "<input name='DELIVERY_SYSTEM_ID' id='DELIVERY_SYSTEM_ID' type='hidden' value='".$arDelivSysAct."'>";
		$deliveryPrice = $arDelivSys[PRICE];
	}else{*/
		echo "<h3 class='title-form'>Выберите способ доставки:</h3>";
		echo '<div id="delivery" class="dp">';
		if($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID']){
			//$arDelivSys = CSaleDelivery::GetByID($_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID']);
			$arDelivSysAct = $_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'];
		}else{
			//$arDelivSys = CSaleDelivery::GetByID($arResult["DELIVERY_SYSTEM"][0][ID]);
			$arDelivSysAct = $arResult["DELIVERY_SYSTEM"][0][ID];
		}
		$_SESSION['SALE_ORDER_USER_PROPS']['DELIVERY_SYSTEM_ID'] = $arDelivSysAct;
		
		foreach($arResult["DELIVERY_SYSTEM"] as $key => $valDelivery){
			
			if($arDelivSysAct==$valDelivery[ID])
					$deliveryPrice = $valDelivery[PRICE];
			
			?>
				<input name="DELIVERY_SYSTEM_ID" onClick="basketPropertyFormSession()" type="radio" id="d_<?=$valDelivery[ID]?>" value="<?=$valDelivery[ID]?>" <?=($arDelivSysAct==$valDelivery[ID])?"checked='checked'":""?>>
				<label class="label delivery-item" for="d_<?=$valDelivery[ID]?>">
					<div class='col-lg-2 col-md-2 col-sm-2 col-xs-12 logo'>
					<?
					if(($valDelivery[LOGOTIP])AND(is_file($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($valDelivery[LOGOTIP])))){
						echo"<img src='".DOMAIN.CFile::GetPath($valDelivery[LOGOTIP])."' class='responsive-img'/>";
					}else{
						echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg-all.jpg' class='responsive-img'/>";
					}
					?> 
					</div>
					<div class='col-lg-10 col-md-10 col-sm-10 col-xs-12 pr-deliv'>
						<div class="name-d"><?=$valDelivery[NAME]?></div>
						<div class="price">Цена: <strong><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($valDelivery["PRICE"], $valDelivery["CURRENCY"]))?></strong>
						<?
						if(($valDelivery[PERIOD_FROM])AND($valDelivery[PERIOD_TO])){
							if($valDelivery[PERIOD_TO] > $valDelivery[PERIOD_FROM])
								echo " доставка: <strong>".$valDelivery[PERIOD_FROM]." - ".$valDelivery[PERIOD_TO]."</strong>  д.";
							else
								echo " доставка: <strong>".$valDelivery[PERIOD_FROM]."</strong>  д.";
						}elseif($valDelivery[PERIOD_FROM]){
							echo " доставка: <strong>".$valDelivery[PERIOD_FROM]."</strong> д.";
						}elseif($valDelivery[PERIOD_TO]){
							echo " доставка: <strong>".$valDelivery[PERIOD_TO]."</strong> д.";
						}
						?>
						</div>
						<?
						if($valDelivery[DESCRIPTION]){
							echo '<div class="desc">'.$valDelivery[DESCRIPTION].'</div>';
						}
						?>
					</div>
					<div class="clear"></div>
				</label>
				<?
				if(($valDelivery[ID]==12)AND($arDelivSysAct==$valDelivery[ID]))
					echo '<a id="map_button"><strong>Выбрать пункт выдачи</strong></a>';
				
		}
		echo "</div>";
	//}
}
if(($arC)AND($outCity[0][id])){//Санкт-Петербург
?>
<script type="text/javascript">
var map_config = {
    "target" : "map_button",
    "city" : "<?=$outCity[0][id]?>",
    "onload" : null,
    "onselect" : function(info){
        console.log(info);
		var adres = info.point.address.replace(document.getElementById("ORDER_PROP_4").value,"");
		adres = adres.trim()+', '+info.point.name;
        document.getElementById("ORDER_PROP_5").value = adres.replace(/[^,.-A-Za-zА-Яа-яЁё]/g, " ");
		basketPropertyFormSession();
    },
    "oncancel" : function(){ // функиця отмены выбора - по умолчанию только console.log(message)
        console.log('map select cancel');
        //alert ('Выбор отменен!');
    },
    "show_price" : false, // показывать поле стоимость - по умолчанию true
    "show_button" : true, // показывать кнопку "Заберу отсюда"
    "show_time" : false, // показывать время доставки
    "show_weight" : false, // показывать максимальный вес
    "show_partial" : false, // показывать частичный выкуп
    "show_type" : false, // показывать тип точки
    "filter_type" : null, 

};
if (typeof window.promap_var == "undefined"){
    var promap_var = 21845, promap_timerId;
    window.promap_Setting = {
        "target" : "map_button",
        "city" : "137",
        "onload" : null,
        "onselect" : function(value){console.log(value);},
        "oncancel" : function(){console.log('map select cancel');},
        "show_price" : true,
        "show_button" : true,
        "show_time" : true,
        "show_weight" : true,
        "show_partial" : true,
        "show_type" : true,
        "filter_type" : null,
        "price" : function(value){
            var out = [];
            for(var index in value) {
                if(value[index].value == 0)
                    continue;
                out.push(value[index].name + " - " + value[index].value + "p.");
            } 
            return out.join("; ");
        },
    };
    function loadScript(path){
        var sc = document.getElementsByTagName("script");
        var iA = sc[0];
        var se = document.createElement("script");
        se.type = "text/javascript"; 
        se.async = true; 
        se.charset="UTF-8";
        iA.parentNode.insertBefore(se, iA).src = path;
    }
    function addStyle(){
        var css = '',head = document.head || document.getElementsByTagName('head')[0],style = document.createElement('style');
        style.type = 'text/css';
        if (style.styleSheet){style.styleSheet.cssText = css;} else {style.appendChild(document.createTextNode(css));}
        head.appendChild(style);
    }   
    var extend = function () {
        var extended = {};
        var deep = false;
        var i = 0;
        var length = arguments.length;
        if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
            deep = arguments[0];
            i++;
        }
        var merge = function (obj) {
            for ( var prop in obj ) {
                if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                    if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                        extended[prop] = extend( true, extended[prop], obj[prop] );
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };
        for ( ; i < length; i++ ) {
            var obj = arguments[i];
            merge(obj);
        }
        return extended;
    };
    (function(){
        if(typeof map_config !== "undefined") {promap_Setting = extend(promap_Setting,map_config);}
        document.getElementById(promap_Setting.target).disabled = true;        
        if(typeof window.ymaps == "undefined"){loadScript("https:\/\/api-maps.yandex.ru\/2.1\/?lang=ru_RU");}
        loadScript("\/\/lk.pro-cour.ru/map_widget.js?v1.0.2");
        addStyle();
        var div = document.createElement('div'); div.id = 'lk_map_modal';
        div.innerHTML = '<div id="lk_point_selector"><a class="lk_close" onclick="proMap.hide()"></a><div id="lk_loading"><img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/ajax-loader.gif"></div><div id="lk_point_list" class="col-lg-4 col-md-5 col-sm-6 col-xs-12 no-padding"></div><div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 no-padding"><div id="lk_point_map"></div></div><div class="clear"></div></div>';document.body.appendChild(div);
        promap_timerId = setTimeout(function () {
            if (typeof window.proMap === "undefined") {
                console.log("Map widget dont load! Time is out!");
            }
        }, 5000);
    })();
};
</script>
<?
}
?>