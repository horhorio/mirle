<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//echo "<pre>"; print_r($arResult); echo "</pre>";
?>
<div class="review-head">
	<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 review-head-text no-padding'>
        <div class="review-head-text-inf">
            <h5>Отзывы покупателей</h5>
            <p>Помогите нам сделать качество обслуживания еще лучше, а другим покупателям, определиться с выбором интернет-магазина!</p>
            <p>Мы будем благодарны, если Вы уделите немного своего времени и оставите отзыв о качестве сервиса.</p>
            <a href="javascript:void(0);" class="review-add"><strong>Оставить отзыв</strong></a>
        </div>
  	</div>
  	<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 review-head-rating'>
    
    </div>
    <div class="clear"></div>
</div>
<div class='elements'>
<?
if(empty($arResult["ITEMS"])){
	echo "<div class='alert_warning margin-30-15'>К сожалению, еще никто не оставил свой отзыв.</div>";
}else{
	foreach($arResult["ITEMS"] as $arItem){
	?>
	<div class='element'>
		<div class="review-item__user">
			<p><span><b><?=$arItem[NAME]?></b>,</span> <?=$arItem[DISPLAY_ACTIVE_FROM]?> г.</p>
		</div>
		<div class="review-messages">
			<p><?=$arItem[PREVIEW_TEXT]?></p>
            <?
            if(strlen($arItem[DETAIL_TEXT]) > 10){
            	echo "<div class='answer'>";
					echo "<h6>Ответ отдела по работе с клиентами:</h6>";
					echo "<p>".$arItem[DETAIL_TEXT]."</p>";
				echo "</div>";
			}
			?>
		</div>
	</div>
	<?	
	}
}
?>
<?=$arResult["NAV_STRING"]?>
</div>
<div class="comment-add" id="comment-add">
    <div class="col-xs-12 desc">
        <h5>Вы можете оставить отзыв</h5>
        <p>Используйте данную форму только для того, чтобы оставить отзыв о работе интернет-магазина. Для вопросов о дате поступления, наличии, использовании, характеристиках товара и прочих используйте функцию задать вопрос.</p>
        <p>Все комментарии не касающиеся отзывов о работе интернет-магазина будут удалены!</p>
  	</div>
    <div class="clear"></div>
    <form action="javascript:void(0);" method="post" id="shopReviewsForm">
    <div class='col-sm-6 col-xs-12'>
        <input name="FIO" type="text" id="FIO" placeholder="Ваше имя*" value='<?=$USER->GetParam("NAME")?>'>
	</div>
    <div class='col-sm-6 col-xs-12'>
        <input name="MAIL" id="MAIL" type="text" placeholder="Ваша эл. почта" value='<?=$USER->GetParam("EMAIL")?>'>
	</div>
    <div class='col-xs-12'>
        <textarea name="TEXT" id="TEXT" placeholder="Текст сообщения*"></textarea>
        <div class="sog">Нажимая на кнопку <strong>"Добавить отзыв"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/personal-data-policy/" target="_blank">в соответствии с условиями</a></div>
        <button name="submit" type="button" class="add-comm-element" id="btnRev" onclick="javascript:sendData('shopComment.php','shopReviewsForm','resAction','btnRev','Добавить отзыв'); return false;"><strong>Добавить отзыв</strong></button>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    </form>
</div>