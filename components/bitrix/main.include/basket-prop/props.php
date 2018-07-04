<?
//unset($_SESSION['SALE_ORDER_USER_PROPS']);
if($CURRENT_USER){
	$rsUser = CUser::GetByID($CURRENT_USER);
	$user = $rsUser->Fetch();
	//echo "<pre>"; print_r($user); echo "</pre>";
	
	$db_sales = CSaleOrderUserProps::GetList(array("DATE_UPDATE" => "DESC"),array("USER_ID" => $CURRENT_USER, "PERSON_TYPE_ID" => 1),false,array("nTopCount" => 1));
	while ($ar_sales = $db_sales->Fetch()){
		$profileID = $ar_sales[ID];
	}
	$fieldsUser = array();    
	// получим свойства профиля   
	$db_propVals = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), Array("USER_PROPS_ID"=>$profileID));
	while ($arPropVals = $db_propVals->Fetch()){
		$arResult['SALE_ORDER_USER_PROPS'][] = $arPropVals;
	}
	//echo "<pre>"; print_r($profilOld); echo "</pre>";  
}

$arResult['SALE_ORDER_PROPS'] = array();
$db_props = CSaleOrderProps::GetList(
	array("SORT" => "ASC"),
	array(
			"PERSON_TYPE_ID" => 1,
			"ACTIVE" => "Y"
		),
	false,
	false,
	array()
);
while ($props = $db_props->Fetch()){
	//echo "<pre>"; print_r($props); echo "</pre>"; 
	$vPalProp = false;
	if(!empty($arResult['SALE_ORDER_USER_PROPS'])){
		foreach($arResult['SALE_ORDER_USER_PROPS'] as $vP){
			if($vP[ORDER_PROPS_ID] == $props[ID]){
				$vPalProp = $vP[VALUE];
				break;
			}
		}
	}
	$props[VALUE] = $vPalProp;
	if((!$vPalProp)AND($props[CODE] == 'EMAIL'))
		$props[VALUE] = $USER->GetParam("EMAIL");
	if((!$vPalProp)AND($props[CODE] == 'FIO'))
		$props[VALUE] = $USER->GetParam("NAME");
	if((!$vPalProp)AND($props[CODE] == 'CITY'))
		$props[VALUE] = $CITY;
	$props[FIELD_NAME] = $props[CODE];
	$arResult['SALE_ORDER_PROPS'][] = $props;
}

//echo '<pre>';print_r($arResult['SALE_ORDER_PROPS']);echo '</pre>';
if(!empty($arResult['SALE_ORDER_PROPS'])){
	echo "<h3 class='title-form'>Информация о покупателе:</h3>";
	echo '<div id="props">';
	foreach ($arResult['SALE_ORDER_PROPS'] as $arProperties){
		if ($arProperties["TYPE"] == "TEXT"){
			$basketProperty = '';
			if($arProperties[CODE] == 'CITY')
				$basketProperty = 'oninput="basketSearchCity(this.value);"';
			//if($arProperties[CODE] == 'CITY')
				//$basketProperty = 'onblur="basketPropertyFormSession()"';
			?>
            <div class="form-grp">
            <label><?=($arProperties[REQUIED] == 'Y')?"<strong>".$arProperties[NAME]."</strong>":$arProperties[NAME]?><?=($arProperties[REQUIED] == 'Y')?" <star>*</star>":""?></label>
            <input <?=$basketProperty?> <?=($arProperties[CODE] == 'PHONE')?'type="tel" class="mask"':'type="text"'?> placeholder="<?=$arProperties[NAME]?>" value="<?=($_SESSION['SALE_ORDER_USER_PROPS']["ORDER_PROP_".$arProperties["ID"]]) ? $_SESSION['SALE_ORDER_USER_PROPS']["ORDER_PROP_".$arProperties["ID"]] : $arProperties["VALUE"];?>" name="ORDER_PROP_<?=$arProperties["ID"]?>" id="ORDER_PROP_<?=$arProperties["ID"]?>"/>
            <?
            if($arProperties[DESCRIPTION])
				echo"<span class='desc'>".$arProperties[DESCRIPTION]."</span>";
			?>
            </div>
            <?
		}
		elseif ($arProperties["TYPE"] == "TEXTAREA"){
			?>
            <div class="form-grp">
            <label><?=($arProperties[REQUIED] == 'Y')?"<strong>".$arProperties[NAME]."</strong>":$arProperties[NAME]?><?=($arProperties[REQUIED] == 'Y')?" <star>*</star>":""?></label>
            <textarea placeholder="<?=$arProperties[NAME]?>" name="ORDER_PROP_<?=$arProperties["ID"]?>" id="ORDER_PROP_<?=$arProperties["ID"]?>"><?=($_SESSION['SALE_ORDER_USER_PROPS']["ORDER_PROP_".$arProperties["ID"]]) ? $_SESSION['SALE_ORDER_USER_PROPS']["ORDER_PROP_".$arProperties["ID"]] : $arProperties["VALUE"];?></textarea>
            <?
            if($arProperties[DESCRIPTION])
				echo"<span class='desc'>".$arProperties[DESCRIPTION]."</span>";
			?>
            </div>
            <?
		}
	}
	echo "</div>";
}
?>
