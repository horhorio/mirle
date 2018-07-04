	
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
		