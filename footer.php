<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
</section>
<footer>
    <div class="foot-subscribe-bg">
    	<div class="foot-subscribe wrapp">
            <div class='foot-soc col-xs-12 text-center'>
                <a href="https://vk.com/mirle" class="soc v" target="_blank"></a>
                <a href="https://www.instagram.com/mirle.ru/" class="soc i" target="_blank"></a>
            </div>
            <div class='foot-sub col-xs-12 text-center'>
                <h3>Узнавайте первыми об акциях и новинках!</h3>
                <form method="post" action="javascript:void(0);" id="subscribeForm">
                    <input type="email" id="SUBMAIL" name="SUBMAIL" placeholder="Email"/>
                    <button name="submit" type="button" class="flat_button" id="btnSUB" onClick="javascript:sendData('userSubscribeForm.php','subscribeForm','resAction','btnSUB','Ok'); return false;"><strong>Ok</strong></button>
                    <div class="clear"></div>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="footer-menus">
        <div class="wrapp">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 flex">
               <h4 class="footer-head"><?=SITE?></h4> 
               <?$APPLICATION->IncludeComponent("bitrix:menu", "footer", Array(
                    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "1",	// Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "ROOT_MENU_TYPE" => "about",	// Тип меню для первого уровня
                        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "COMPONENT_TEMPLATE" => "about"
                    ),
                    false
                );?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 flex">
                <h4 class="footer-head">Помощь покупателю</h4> 
               <?$APPLICATION->IncludeComponent("bitrix:menu", "footer", Array(
                    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "1",	// Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "ROOT_MENU_TYPE" => "help",	// Тип меню для первого уровня
                        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "COMPONENT_TEMPLATE" => "help"
                    ),
                    false
                );?>
            </div>
            <div class='clear hidden-lg hidden-md'></div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 flex">
                <h4 class="footer-head">Информация</h4> 
               <?$APPLICATION->IncludeComponent("bitrix:menu", "footer", Array(
                    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "1",	// Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "ROOT_MENU_TYPE" => "info",	// Тип меню для первого уровня
                        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "COMPONENT_TEMPLATE" => "info"
                    ),
                    false
                );?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 flex">
                <div class="footer-work">
                    <div class="footer-phone"><span><b>
                    <a href='tel:<?=str_replace(" ", "", preg_replace('/[^0-9\s]/ui', ' ', COMPANY_TEL_OPER))?>'><?=COMPANY_TEL_OPER?></a>
					<br>
					<a href='tel:<?=str_replace(" ", "", preg_replace('/[^0-9\s]/ui', ' ', COMPANY_TEL_OPER2))?>'><?=COMPANY_TEL_OPER2?></a>
					</b></span></div>
                    <div class="footer-jobtime"><span>пн. - пт. - с 10:00 до 19:00</span><span>сб. - вc. выходной</span></div>
                    <div class="footer-mail">
                    <span>Эл. почта:</span> <a href="mailto:shop@mirle.ru">shop@mirle.ru</a>
                    </div>
                    
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
	</div>
	<p class="copy"><?=date('Y')?> &copy <?=SITE?></p>
</footer>
<?$APPLICATION->IncludeComponent(
    "bitrix:search.form",
    "template",
    Array(
        "PAGE" => DOMAIN."/search/",
        "USE_SUGGEST" => "N"
    )
);?>

<?
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");
$all_menu = array();
$all_menu = $memcache_obj->get('all_menu');
//echo "<pre>"; print_r($var_key); echo "</pre>";
if(!empty($all_menu)){
	$arResult['SECTIONS'] = $all_menu;
	include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/components/bitrix/catalog.section.list/menu/template.php");
	//echo "memcache";
}else{
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"menu",
		Array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "N",
			"COUNT_ELEMENTS" => "Y",
			"IBLOCK_ID" => "1",
			"IBLOCK_TYPE" => "catalog",
			"SECTION_CODE" => "",
			"SECTION_FIELDS" => array("", ""),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array("", ""),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "4",
			"VIEW_MODE" => "LINE"
		)
	);
	//echo "query";
}
$memcache_obj->close();
?>


<div class='modalBox'>
    <div class='top'>
    	<div class='box-name-win'></div>
        <a href='javascript:void(0)' class='close'></a>
    </div>
    <div class='box-title'><strong></strong></div>
    <div class='cont-mod'>
    	
    </div>
</div>
<div class='modalOver'></div>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/slick.min.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/slick-lightbox.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.slimscroll.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
<? 
if($dir == '/cart/cart-prop/'){
?>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.autocomplete.pack.js"></script>
<? 
}
if($dir == '/varianty-dostavk/'){
?>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.autocomplete.pack.srchdeliv.js"></script>
<? 
}
 
if(
	($dir == '/cart/cart-prop/')OR
	($dir == '/varianty-dostavk/')
){
?>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/city.js"></script>
<? 
}
?>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/jquery.lazyload.min.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/cookie.js"></script>
<script src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/js/common.js"></script>

<form action="<?=DOMAIN.$dir?>" name="log">
<input type="hidden" name="logout" value="yes" />
</form>
<div id="resActionHidden"></div>
<div id="resAction"></div>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "LocalBusiness",
	"description": "Интернет-магазин домашнего текстиля <?=SITE?>. Тел: <?=COMPANY_TEL?>",
	"name": "<?=COMPANY?>",
	"url": "<?=DOMAIN?>",
	"logo": "<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/logo.png",
	"image": "<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/logo.png",
	"telephone": "<?=COMPANY_TEL?>",
	"address": {
		"@type": "PostalAddress",
		"addressLocality": "<?=COMPANY_ADDRESS?>"
	},
	"priceRange": "Постельное белье от 1800 рублей.",
	"openingHours": "<?=COMPANY_WORKTIME?>",
	"sameAs" : [ "https://vk.com/mirle","https://www.instagram.com/mirle.ru/"]
}
</script> 
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter43070549 = new Ya.Metrika2({
                    id:43070549,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/43070549" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-107210447-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', 'UA-107210447-1');
</script>
<script>
document.addEventListener("DOMNodeInserted", function (event) {
    var yandex = /Маркета/.test(event.target.innerHTML);
    if(yandex){
		$('html').removeAttr('g_init')
    	var id = $('body > div[id]:last').attr('id');
    	$('body > div[id]:last [title]').removeAttr('title');
    	//addImportantStyle('#'+id, 'opacity: 0.1 !important;');
    	addImportantStyle('#'+id, 'background: #FFF !important;border-bottom: 1px solid #FFF;');
    	addImportantStyle('#'+id+' *', 'cursor:default !important;color: transparent !important;background-image: none !important;background-color: transparent !important;border-style: none !important;box-shadow:  none !important;')
    	$('#'+id+' img').removeAttr('src');
    	$('#'+id+' a').removeAttr('href');
    	$('#'+id+' *').removeAttr('data-url');
		$('body').append("<noindex><div class='lock-yansov'></div></noindex>");
    } 
}, false);
function addImportantStyle(element, style) {
    var el = $(element).attr('style');
    if ($(element).attr('style') !== '' ) {
        $(element).attr('style', $(element).attr('style') + ';' + style);
    } else {
        $(element).attr('style', style);
    }
}
</script>
<?

/*if($USER->IsAdmin()){
	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, 'https://geocode-maps.yandex.ru/1.x/');
		curl_setopt($culrl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, false);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, "format=json&results=1&geocode=г. Сосновый Бор, Ленская 3 корп. 1 кв 22&charset=utf8");
		curl_setopt($curl, CURLOPT_POSTFIELDS, "format=json&kind=street&results=1&geocode=г. Сосновый Бор, ул. Молодежная 61&charset=utf8");
		//curl_setopt($curl, CURLOPT_POSTFIELDS, "format=json&results=1&geocode=г. ".$_SESSION['dataCity'][city].", Ленская 3 корп. 1 кв 22&charset=utf8");
		$geo = json_decode(curl_exec($curl), true);
		//echo $out;
		echo "<pre>"; print_r($geo); echo "</pre>";
		curl_close($curl);
		echo $geo[response][GeoObjectCollection][featureMember][0][GeoObject][name];
	}
	
	//echo "<pre>"; print_r($_SESSION['cityDelivery']); echo "</pre>";
	$outCity[0][id] = false;
	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, 'https://lk.pro-cour.ru/calculator.php');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "type=find&query=Абадзехская&charset=utf8");//".$_SESSION['dataCity'][city]."
		$outCity = json_decode(curl_exec($curl), true);
		//echo $out;
		echo "<pre>"; print_r($outCity); echo "</pre>";
		curl_close($curl);
	}
	if(($curl = curl_init())AND($outCity[0][id])) {
		curl_setopt($curl, CURLOPT_URL, 'https://lk.pro-cour.ru/calculator.php');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "type=calc&city_id=".$outCity[0][id]."&weight=3");
		$out = json_decode(curl_exec($curl), true);;
		//echo $out;
		echo "<pre>"; print_r($out); echo "</pre>";
		curl_close($curl);
	}
}*/
?>
</body>
</html>