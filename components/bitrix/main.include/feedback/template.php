<?
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");
$arSite = array();
$arSite = $memcache_obj->get('CSiteGetByID');
if(empty($arSite)){
	$rsSites = CSite::GetByID(SITE_ID);
	$arSite = $rsSites->Fetch();
	$memcache_obj->set('CSiteGetByID', $arSite, false, 604800);//Неделя
	$arSite = $memcache_obj->get('CSiteGetByID');
}
$memcache_obj->close();

$res = $DB->Query("
SELECT 
	b_iblock_element.PREVIEW_TEXT,
	b_iblock_element_prop_s3.PROPERTY_7
FROM 
	b_iblock_element,
	b_iblock_element_prop_s3
WHERE 
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = b_iblock_element.ID
		AND
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = 954
		AND
	b_iblock_element.ACTIVE='Y'
		AND
	b_iblock_element.IBLOCK_ID = 3
");
$row = $res->Fetch();
?>
<div class='wrapp contact'>
    <div class='col-xs-12'>
        <h1 class="h1-head"><span class="before"></span><?=$row['PROPERTY_7']?><span class="after"></span></h1>
        <?
        if(strlen($row['PREVIEW_TEXT']) > 30)
			echo "<p class='prev-text'>".$row['PREVIEW_TEXT']."</p>";
		?>
    </div>
    <div class="clear"></div>
    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
        <div class='contact-info'>
            <div class="contact-text">
            <h4>Офис <?=SITE?></h4>
            <p><?=COMPANY_ADDRESS?></p>
            <p><?=COMPANY_WORKTIME?></p>
            </div>
            <div class="contact-text">
                <h4>Контактные телефоны:</h4>
                <p><a href='tel:<?=str_replace(" ", "", preg_replace('/[^0-9\s]/ui', ' ', COMPANY_TEL_OPER))?>'><strong><?=COMPANY_TEL_OPER?></strong> - Яна (Менеджер по работе с клиентами)</a></p>
                <p><a href='tel:<?=str_replace(" ", "", preg_replace('/[^0-9\s]/ui', ' ', COMPANY_TEL_OPER2))?>'><strong><?=COMPANY_TEL_OPER2?></strong> - Ирина (Менеджер по работе с клиентами)</a></p>
            </div>
            <div class="contact-text">
                <h4>Электронная почта:</h4>
                <p><a href="mailto:<?=$arSite['EMAIL']?>"><strong><?=$arSite['EMAIL']?></strong></a></p>
            </div>
            <div class="contact-text">
                <h4>Реквизиты:</h4>
                <p>Название компании: <strong><?=COMPANY?></strong></p>
                <p>ИНН: <strong>471404701496</strong></p>
                <p>ОГРН: <strong>317784700182767</strong></p>
            </div>
        </div>
    </div>
    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 form'>
	<form action="javascript:void(0);" method="POST" class="contact-form" id="contactForm" autocomplete="off">
        <h5>Напишите нам письмо</h5>
        <input placeholder="Ваше имя*" name="fio" id="fio" type="text">
        <input placeholder="Ваш e-mail*" name="mail" id="mail" type="text">
        <input placeholder="Ваш телефон" class="mask" name="phone" id="phone" type="tel">
        <textarea placeholder="Ваш текст*" name="text" id="text"></textarea>
        <div class="sog">Нажимая на кнопку <strong>"Отправить"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/personal-data-policy/" target="_blank">в соответствии с условиями</a></div>
        <button class="button-fiolet" type="submit" id="btnF" onclick="javascript:sendData('contact.php','contactForm','resAction','btnF','Отправить'); return false;"><strong>Отправить</strong></button>
	</form>
    </div>
    <div class="clear"></div>
</div>