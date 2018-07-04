<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Oxolov~2017[]
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");
$arCity = array();
$_SESSION['cityDelivery'] = $arCity = $memcache_obj->get('arCity');
if(empty($arCity)){
	CModule::IncludeModule("sale");
	$db_vars = CSaleLocation::GetList(
		array("CITY_NAME" => "ASC"),
		array("LID" => LANGUAGE_ID,">CITY_NAME"=>0),
		false,
		false,
		array('*')
	);
	while ($vars = $db_vars->Fetch()){
	  	//echo "<pre>"; print_r($vars); echo "</pre>";
	 	$arCity[] = $vars;
		$arCityName[] = $vars[CITY_NAME];
	}
	$memcache_obj->set('arCity', $arCity, false, 2592000);//Месяц
	$_SESSION['cityDelivery'] = $arCity;

	$end = end($arCityName);
	$arC = '';
	foreach($arCityName as $c){
		if($end != $c)
			$arC .= "'".$c."',";
		else
			$arC .= "'".$c."'";
	}
	$ct = "(function(){
    $('#ORDER_PROP_4').autocomplete([".$arC."], {
        max: 10
    });
})(jQuery);";
	$f=fopen($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/js/city.js","w");
	fwrite($f,$ct);
	fclose($f);
}
$memcache_obj->close();
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
<title><?$APPLICATION->ShowTitle()?></title>
<base href="<?=DOMAIN?>">
<meta name="author" content="Охолов С.В. https://oxolov.ru/">
<meta charset="<?=LANG_CHARSET;?>">
<?
//$APPLICATION->ShowHead();
$APPLICATION->ShowMeta("description");
//$APPLICATION->ShowHeadStrings();
?>
<meta name="yandex-verification" content="89232a54c3e536d1">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?
$dir = $APPLICATION->GetCurDir();
$getProduct = false;
$exDir = array_values(array_diff(explode("/",$dir), array('')));
//echo "<pre>"; print_r($exDir); echo "</pre>";
if(count($exDir) > 1){
	$ress = $DB->Query("
	SELECT 
		b_iblock_section.CODE 
	FROM
		b_iblock_section,
		b_iblock_element 
	WHERE 
		b_iblock_element.IBLOCK_ID=1
			AND
		b_iblock_element.CODE='".$exDir[1]."'
			AND
		b_iblock_element.IBLOCK_SECTION_ID=b_iblock_section.ID
			AND
		b_iblock_element.ACTIVE='Y'
	");
	$row = $ress->Fetch();
	//echo "<pre>"; print_r($row); echo "</pre>";
	if($row[CODE]){
		$getProduct = true;
		$link = "/".$row[CODE]."/".$exDir[1]."/";
		if(($dir != $link)OR(!empty($_GET))) { 
			echo "<link rel='canonical' href='".DOMAIN.$link."'/>\n";
			echo "<meta name='ROBOTS' content='INDEX, FOLLOW'>\n";
		}
	}
}
if(!$getProduct){
	if(!$_GET['PAGEN_1']){
		echo "<meta name='ROBOTS' content='INDEX, FOLLOW'>\n";
		if(!empty($_GET)){
			echo "<link rel='canonical' href='".DOMAIN.$dir."'/>\n";
		}
	}else{
		echo "<meta name='ROBOTS' content='NOINDEX, FOLLOW'>\n";
	}
}
?>
<link rel="icon shortcut" type="image/png" href="<?=DOMAIN?>/favicon.png">
<link href="<?=DOMAIN?>/favicon.png" rel="icon">
<link rel="stylesheet" type="text/css" href="<?=DOMAIN.SITE_TEMPLATE_PATH?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=DOMAIN.SITE_TEMPLATE_PATH?>/css/styles.css">
<link rel="stylesheet" type="text/css" href="<?=DOMAIN.SITE_TEMPLATE_PATH?>/css/media.css">
</head>
<body<?=($USER->IsAdmin())?'':' oncopy="return false;"'?>>
<?
//$APPLICATION->ShowPanel();
$CURRENT_USER = CUser::GetID();
if(($CURRENT_USER==3)AND(!CSite::InDir('/cart/'))){
    $USER->Logout();
}
if((!$_SESSION['arUser'])AND($CURRENT_USER)){
	$rsUser = CUser::GetByID($CURRENT_USER);
	$arUser = $rsUser->Fetch();
	$_SESSION['arUser'] = $arUser;
}
if(!$CURRENT_USER){
	unset($_SESSION['arUser']);
}
if($CURRENT_USER){
	if($USER->GetFirstName())
		$name = $USER->GetFirstName();
	elseif($USER->GetLastName())
		$name = $USER->GetLastName();
	else
		$name = $USER->Login();	
}
$amountText = false;
if(($CURRENT_USER)AND($_SESSION['arUser'])){
	$UF_DISCONT = $_SESSION['arUser'][UF_DISCONT];
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
	

	/*if(($BIRTHDATE_MONTH >= $date_from)AND($BIRTHDATE_MONTH <= $date_to)AND($BIRTHDATE_MONTH)){
		
		$amountText = "В честь Вашего дня рождения скидка <strong class='bender-font'>".$BIRTHDATE_DISCONT."%</strong>";
		
	}else{
		if($UF_AMOUNT > 0){
			if($UF_AMOUNT < 10000)
				$amountText = "Вам не хватает <strong class='bender-font'>".number_format(10000-$UF_AMOUNT, 0, '.', ' ')." р.</strong> до скидки в <strong class='bender-font'>5%</strong>, сумма накоплений <strong class='bender-font'>".number_format($UF_AMOUNT, 0, '.', ' ')." р.</strong>";
				
			elseif(($UF_AMOUNT >= 10000)AND($UF_AMOUNT <= 24999))
				$amountText = "Вам не хватает <strong class='bender-font'>".number_format(25000-$UF_AMOUNT, 0, '.', ' ')." р.</strong> до скидки в <strong class='bender-font'>10%</strong>! Ваша скидка <strong class='bender-font'>".$UF_DISCONT."%</strong>, сумма накоплений <strong class='bender-font'>".number_format($UF_AMOUNT, 0, '.', ' ')." р.</strong>";
				
			elseif(($UF_AMOUNT >= 25000)AND($UF_AMOUNT <= 49999))
				$amountText = "Вам не хватает <strong class='bender-font'>".number_format(50000-$UF_AMOUNT, 0, '.', ' ')." р.</strong> до скидки в <strong class='bender-font'>15%</strong>! Ваша скидка <strong class='bender-font'>".$UF_DISCONT."%</strong>, сумма накоплений <strong class='bender-font'>".number_format($UF_AMOUNT, 0, '.', ' ')." р.</strong>";
		
			elseif($UF_AMOUNT >= 50000)
				$amountText = "Ваша скидка <strong class='bender-font'>".$UF_DISCONT."%</strong> максимальная, сумма накоплений <strong class='bender-font'>".number_format($UF_AMOUNT, 0, '.', ' ')." р.</strong>";
			//echo "<pre>"; print_r($_SESSION['arUser']); echo "</pre>";
		}
	}*/
}
if(!$_SESSION['dataCity']){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])):
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else:
		$ip = $_SERVER['REMOTE_ADDR'];
	endif;
	require_once $_SERVER["DOCUMENT_ROOT"]."/geo/ipgeobase.php";
	$gb = new IPGeoBase();
	$_SESSION['dataCity'] = $gb->getRecord($ip);
}
	  
$arC = false;
foreach($_SESSION['cityDelivery'] as $c){
	if(mb_strtolower($_SESSION['dataCity'][city]) == mb_strtolower($c[CITY_NAME])){
		$arC = $c[CITY_ID];
		break;
	}
}
if(!$_SESSION['dataCity'][city]){
	$arC = true;
}



$promo = array();
if($USER->IsAdmin()){
	$lkr = '';
	CModule::IncludeModule("catalog");
	$arSelect = array("NAME","CODE","PROPERTY_LINK");
	$arFilter = array("IBLOCK_ID"=>11, "ACTIVE"=>"Y",'ACTIVE_DATE'=>'Y');
	$res = CIBlockElement::GetList(array("SORT"=>"DESC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$promo[] = array(
			"NAME"=>str_replace(strtoupper($arFields["CODE"]),"<b>".strtoupper($arFields["CODE"])."</b>",$arFields["NAME"]),
			"CODE"=>$arFields["CODE"]
		);
		$lkr = $arFields['PROPERTY_LINK_VALUE'];
		//echo '<pre>'; print_r($arFields); echo '</pre>';
	}
	if((!empty($promo))AND($arC)){
		$k = array_rand($promo);
		if($lkr)
			$lkr = ', <a href="'.DOMAIN.'/promo-product/?CODE='.$promo[$k]["CODE"].'" style="text-decoration: underline !important;">посмотреть...</a>';
		echo "<noindex><div class='inform'>".$promo[$k]["NAME"].$lkr."</div></noindex>";
	}
}
if(!$arC){
	echo "<noindex><div class='inform'>К сожалению, Доставка в г. <strong>".$_SESSION['dataCity'][city]."</strong> не осуществляется! Приносим свои извинения за доставленные неудобства!</div></noindex>";
}
?>
<div id="footerCart" class="<?=(CSite::InDir('/cart/'))?"hidden-lg hidden-md hidden-sm ":""?>hidden-xs">
    <div class='col-xs-10 f-cart no-padding'>
        <a href="/cart/">
            <span class="fixed-cart__counter"><strong>0</strong> товаров, </span>
            <span class="fixed-cart__total">на сумму: <strong>0 <span class="rouble">P<i>-</i></span></strong></span>
            <span class="fixed-cart__delivery">доставка: <strong>0 <span class="rouble">P<i>-</i></span></strong></span>
        </a>
    </div>
    <div class="clear"></div>
    <a href="javascript:void(0)" rel="nofollow" id="upButton"></a>
</div>
<?
if($CURRENT_USER){
	global $USER;
	if($USER->IsAdmin()){
	?>
	<div id="panel-admin">
		<a href="<?=DOMAIN?>/bitrix/admin/" class="mem" target="_blank"><strong><i class="glyphicon glyphicon-cog"></i>Администрирование</strong></a>
		<a href="javascript:void(0)" class="mem" onClick="resetCeche();"><strong><i class="glyphicon glyphicon-refresh"></i>Сбросить кеш</strong></a>
		<?
        if($amountText){
			echo "<noindex><div class='info-discont hidden-sm hidden-xs'>".$amountText."</div></noindex>";
		}
		?>
		<a href="javascript:void(0)" class="mem right" onclick='javascript: document.log.submit()'><strong><i class="glyphicon glyphicon-off"></i>Выход</strong></a>
		<div class="clear"></div>
	</div>
	<?
	}else{
	?>
	<div id="panel-admin">
        <?
        if($amountText){
			echo "<noindex><div class='info-discont'>".$amountText."</div></noindex>";
		}
		?>
        <a href="javascript:void(0)" class="mem right" onclick='javascript: document.log.submit()'><strong><i class="glyphicon glyphicon-off"></i>Выход</strong></a>
		<div class="clear"></div>
	</div>
	<?
	}
}	
?>
<header>
    <div class="head wrapp">
        <div class='col-lg-4 col-md-5 hidden-sm hidden-xs'>
           	<ul class="menu-left">
            	<li><a href="javascript:void(0);" rel="nofollow" class="menu-ico ico border shadow act"><strong><i class="glyphicon glyphicon-align-justify"></i>Меню</strong></a></li>
                <li><a href="javascript:void(0);" rel="nofollow" class="ico search-ico border shadow act"><strong><i class="glyphicon glyphicon-search"></i>Поиск</strong></a></li>
            </ul>
        </div>
        <div class='col-lg-4 col-md-2 col-sm-12 col-xs-12 logo'>
            <a href="<?=DOMAIN?>"><img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/logo.png" alt="Интернет-магазин <?=SITE?>"/></a>
            <?
            if($APPLICATION->GetCurDir() == "/")
				echo "<h1 class='slogan'>Интернет-магазин домашнего текстиля</h1>";
			else
				echo "<p class='slogan'>Интернет-магазин домашнего текстиля</p>";
			?>
        </div>
        <div class='col-lg-4 col-md-5 hidden-sm hidden-xs text-right'>
            <ul class="menu-right text-right">
                
                <li><a href="<?=DOMAIN?>/personal/<? if((!preg_match("/personal/", $dir))AND(!$CURRENT_USER))echo "?back_url=".$_SERVER['REQUEST_URI'];?>" rel="nofollow" class="ico  border shadow act"><strong><i class="glyphicon glyphicon-user"></i><?=($name)?$name:"Войти"?></strong></a></li>
                <li><a href="<?=DOMAIN?>/cart/" class="ico my-cart border shadow act"><strong><i class="glyphicon glyphicon-shopping-cart"><span>0</span></i>Корзина</strong></a></li>
            </ul>
        </div>
        <div class="clear"></div>
        <div class='hidden-lg hidden-md col-sm-12 col-xs-12'>
            <ul class="menu-center text-center">
                <li><a href="javascript:void(0);" rel="nofollow" class="menu-ico border act"><i class="glyphicon glyphicon-align-justify"></i>Меню</a></li>
                <li><a href="javascript:void(0);" rel="nofollow" class="search-ico  border act"><i class="glyphicon glyphicon-search"></i>Поиск</a></li>
                <li><a href="<?=DOMAIN?>/personal/<? if(!preg_match("/personal/", $dir))echo "?back_url=".$_SERVER['REQUEST_URI'];?>" rel="nofollow" class=" border act"><i class="glyphicon glyphicon-user"></i><?=($name)?$name:"Войти"?></a></li>
                <li><a href="<?=DOMAIN?>/cart/" rel="nofollow" class="my-cart border act"><i class="glyphicon glyphicon-shopping-cart"><span>0</span></i>Корзина</a></li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
</header>
<section>
<?
if($APPLICATION->GetCurDir() != "/"){
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    "template",
    Array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => "s1"
    )
);?>
<?
} 
if($APPLICATION->GetCurDir() == "/"){
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "index",
        Array(
            "AREA_FILE_SHOW" => "sect", 
            "AREA_FILE_SUFFIX" => "inc", 
            "AREA_FILE_RECURSIVE" => "N", 
            "EDIT_MODE" => "html", 
        )
);?>
<?
}

?>
