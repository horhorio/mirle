<?

$arPaySystem  = Array();
$elPay  = Array();
$resP = $DB->Query("SELECT PAYSYSTEM_ID FROM b_sale_delivery2paysystem WHERE DELIVERY_ID='".$arDeliv."'");
while($rowP = $resP->Fetch()){
	$elPay[] = $rowP[PAYSYSTEM_ID];
}
//echo "<pre>"; print_r($elPay); echo "</pre>";  
if(!empty($elPay))
	$resPaySystem  = CSalePaySystem::GetList(array("SORT"=>"ASC","ID"=>"ASC"), array("ACTIVE"=>"Y", "PERSON_TYPE_ID"=>1,"ID"=>$elPay));
else
	$resPaySystem  = CSalePaySystem::GetList(array("SORT"=>"ASC","ID"=>"ASC"), array("ACTIVE"=>"Y", "PERSON_TYPE_ID"=>1));
while ($arPaySystemItem = $resPaySystem->Fetch()) {
	$arResult["PAY_SYSTEM"][] = $arPaySystemItem;
}

//echo '<pre>';print_r($arResult["PAY_SYSTEM"]);echo '</pre>';
if(!empty($arResult["PAY_SYSTEM"])){
	if(count($arResult["PAY_SYSTEM"]) == 1){
		unset($_SESSION['SALE_ORDER_USER_PROPS']['PAY_SYSTEM_ID']);
		$arPaySys = CSalePaySystem::GetByID($arResult["PAY_SYSTEM"][0][ID]);
		$arPaySysAct = $arResult["PAY_SYSTEM"][0][ID];
		echo "<input name='PAY_SYSTEM_ID' id='PAY_SYSTEM_ID' type='hidden' value='".$arPaySysAct."'>";
	}else{
		//echo (count($arResult["DELIVERY_SYSTEM"]) > 1)?'<div class="margin-top-30"></div>':'';
		echo (!empty($arResult["DELIVERY_SYSTEM"]))?'<div class="margin-top-30"></div>':'';
		echo "<h3 class='title-form'>Выберите способ оплаты:</h3>";
		echo '<div id="pay" class="dp">';
		if($_SESSION['SALE_ORDER_USER_PROPS']['PAY_SYSTEM_ID']){
			$arPaySysAct = $_SESSION['SALE_ORDER_USER_PROPS']['PAY_SYSTEM_ID'];
		}else{
			$arPaySysAct = $arResult["PAY_SYSTEM"][0][ID];
		}
		$_SESSION['SALE_ORDER_USER_PROPS']['PAY_SYSTEM_ID'] = $arPaySysAct;
		foreach($arResult["PAY_SYSTEM"] as $key => $pay){
		?>
        <input name="PAY_SYSTEM_ID" onClick="basketPropertyFormSession()" type="radio" id="p_<?=$pay[ID]?>" value="<?=$pay[ID]?>" <?=($arPaySysAct==$pay[ID])?"checked='checked'":""?>>
		<label class="label delivery-item<?=($pay==0)?' no-marg':''?>" for="p_<?=$pay[ID]?>">
            <div class='col-lg-2 col-md-2 col-sm-2 col-xs-12 logo'>
            <?
			if(($pay[PSA_LOGOTIP])AND(is_file($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($pay[PSA_LOGOTIP])))){
				echo"<img src='".DOMAIN.CFile::GetPath($pay[PSA_LOGOTIP])."' class='responsive-img'/>";
			}else{
				echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg-all.jpg' class='responsive-img'/>";
			}
			?> 
            </div>
			<div class='col-lg-10 col-md-10 col-sm-10 col-xs-12 pr-deliv'>
            	<div class="name-d"><?=$pay[NAME]?></div>
                <?
				if($pay[DESCRIPTION])
					echo '<div class="desc">'.$pay[DESCRIPTION].'</div>';
				?>
            </div>
            <div class="clear"></div>
        </label>
		<?
		}
		echo "</div>";
	}
}
?>
