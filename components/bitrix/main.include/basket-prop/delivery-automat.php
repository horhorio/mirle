	
        <input name="DELIVERY_SYSTEM_ID" onClick="basketPropertyFormSession()" type="radio" id="d_<?=$valDelivery[ID]?>_<?=$profile_id?>" value="<?=$valDelivery[ID]."_".$profile_id?>" <?=($arDelivSysAct==$valDelivery[ID]."_".$profile_id)?"checked='checked'":""?>>
		<label class="label delivery-item" for="d_<?=$valDelivery[ID]?>_<?=$profile_id?>">
            <div class='col-lg-2 col-md-2 col-sm-2 col-xs-12 logo'>
            <?
			if(($valDelivery[LOGOTIP][SRC])AND(is_file($_SERVER["DOCUMENT_ROOT"].$valDelivery[LOGOTIP][SRC]))){
				echo"<img src='".DOMAIN.$valDelivery[LOGOTIP][SRC]."' class='responsive-img'/>";
			}else{
				echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg-all.jpg' class='responsive-img'/>";
			}
			?> 
            </div>
			<div class='col-lg-10 col-md-10 col-sm-10 col-xs-12 pr-deliv'>
            	<div class="name-d"><?=$valDelivery[NAME]." ".$arProfile[TITLE]?></div>
            	<div class="price">Цена: <strong><?=str_replace("р.",'<span class="rouble">P<i>-</i></span>', CurrencyFormat($valDelivery["PRICE"], $valDelivery["CURRENCY"]))?></strong>
                <?
				if($arDelivery["ISNEEDEXTRAINFO"] == "Y")
					$extraParams = "showExtraParamsDialog('".$key.":".$profile_id."');";
				else
					$extraParams = "";
               $APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
					"NO_AJAX" => '',
					"DELIVERY" => $key,
					"PROFILE" => $profile_id,
					"ORDER_WEIGHT" => $ORDER_WEIGHT,
					"ORDER_PRICE" => $allPrice,
					"LOCATION_TO" => $arC,
					"LOCATION_ZIP" => '',
					"CURRENCY" => $CURRENCY,
					"ITEMS" => $count_pr,
					"EXTRA_PARAMS_CALLBACK" => $extraParams
				), null, array('HIDE_ICONS' => 'Y'));
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
		if($profile_id == 'pickup')
			echo '<a id="map_button_sdek"><strong>Выбрать пункт выдачи</strong></a>';
		