<?
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$arSalesUser = array();
$arFilter = Array("USER_ID" => CUser::GetID());
$arNavParams = array(
	"nPageSize" => '10',
);
$rsSales = CSaleOrder::GetList(array("DATE_INSERT" => "DESC"), $arFilter, false, $arNavParams);
$NAV_STRING = $rsSales->GetPageNavStringEx($navComponentObject, '', 'peges', '');
while ($arSales = $rsSales->Fetch())
{
	$arSalesUser[] = $arSales;
}
//echo "<pre>"; print_r($arSalesUser); echo "</pre>"
?>
<div class='orders'>
    <?
    if(empty($arSalesUser)){
		echo "<div class='alert_warning'>К сожалению, у Вас еще нет заказов <a href='".MAIN_URL_CATALOG."'>перейти в каталог</a>.</div>";
	}else{
		foreach($arSalesUser as $k => $arSales){
	?>
    	<div class="detail" id="elOrderD_<?=$arSales[ID]?>"></div>
        <div class="list" id="elOrder_<?=$arSales[ID]?>">
        	<div class="ord-head">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ord-number">
					<p>Заказ № <b><?=$arSales[ACCOUNT_NUMBER]." или ID ".number_format($arSales[ID], 0, '.', ' ')?></b>, от <?=substr($arSales[DATE_INSERT],0,10)?> г.</p>	                
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ord-links">
                	<a href="javascript:void(0);" onclick="javascript:userOrderDetail(<?=$arSales[ID]?>)">Подробнее</a>
                    <?
                    if(($arSales[PAYED] != "Y")AND($arSales[STATUS_ID] != "P")AND($arSales[PAY_SYSTEM_ID] == 2)AND($arSales[CANCELED] != "Y")){
						echo "<a href='".DOMAIN."/cart/pay/?ORDER_ID=".$arSales[ID]."' target='_blank'>Оплатить</a>";
					}
					if(($arSales[STATUS_ID] == "N")AND($arSales[CANCELED] != "Y")){
						echo "<a href='javascript:void(0);' onclick='javascript:userOrderParam(".$arSales[ID].",\"userOrderCancel.php\")'>Отменить</a>";
					}
					if(($arSales[CANCELED] == "Y")OR($arSales[STATUS_ID] == "F")){
						echo "<a href='javascript:void(0);' onclick='javascript:userOrderParam(".$arSales[ID].",\"userOrderDel.php\")'>Удалить</a>";
					}
					if($arSales[STATUS_ID] != "N"){
						echo "<a href='javascript:void(0);' onclick='javascript:userOrderParam(".$arSales[ID].",\"userOrderRepeat.php\")'>Повторить</a>";
					}
					?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ord-elements">
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
				$dbBasket = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arSales[ID]));
				while ($arBasket = $dbBasket->Fetch()){
					//echo "<pre>"; print_r($arBasket); echo "</pre>";
					$ress = $DB->Query("SELECT ID,ACTIVE FROM b_iblock_element WHERE IBLOCK_ID=1 AND ID=".$arBasket[PRODUCT_ID]);
					$row = $ress->Fetch();
					//echo "<pre>"; print_r($row); echo "</pre>";
					$primech = false;
					if(!$row[ID]){
						$primech = " <span class='err'>( удален из ассортимента )</span>";
					}else{
						if($row[ACTIVE] != "Y"){
							$primech = " <span class='err'>( не активен )</span>";
						}
					}
				?>
                <div class="el">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    	<p><a href="<?=DOMAIN.$arBasket[DETAIL_PAGE_URL]?>" target="_blank"><?=$arBasket[NAME]?></a><?=$primech?></p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    	<p class="centered"><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arBasket[PRICE], $arBasket[CURRENCY]));?></p>
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
            <div class="ord-total">
            	<p><?=($arSales[COMMENTS])?$arSales[COMMENTS]." / ":""?>Итоговая сумма заказа: <b><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($arSales[PRICE], $arSales[CURRENCY]));?></b></p>
            </div>
        </div>
    <?
		}
	}
	echo $NAV_STRING;
	?>
</div>