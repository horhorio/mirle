<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="search-fixed-box">
<form action="<?=$arResult["FORM_ACTION"]?>" class="search-form-box">
    <div class='col-lg-11 col-md-11 col-sm-10 col-xs-9'>
    	<input name="q" type="text" autofocus placeholder="Поиск" value="<?=str_replace('quot', " ", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $_GET[q]))?>"/>
    </div>
    <div class="clear"></div>
    <button type="submit" id="bt_src"></button>
    <a href="javascript:void(0);" rel="nofollow" class="close-search"></a>
</form>
</div>
<div id="search-fixed-overlow"></div>