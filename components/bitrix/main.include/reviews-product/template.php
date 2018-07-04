<?
if(CModule::IncludeModule("catalog")){
	$arResultRev = array();
	$arSelect = array("DATE_CREATE","NAME","PREVIEW_TEXT","PROPERTY_PRODUCT_ID","PROPERTY_CITY");
	$arFilter = array("IBLOCK_ID"=>6, "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(array("ID"=>"DESC"), $arFilter, false, array("nPageSize" => '12'), $arSelect);
	$NAV_STRING = $res->GetPageNavStringEx($navComponentObject, '', 'peges', '');
	while($ob = $res->GetNextElement()){
		$arResultRev[] = $ob->GetFields();
	}
	//echo "<pre>"; print_r($arResultRev); echo "</pre>";
	if(empty($arResultRev)){
		echo "<div class='alert_warning margin-30-15'>К сожалению, отзывов о товарах еще никто не оставлял</div>";
	}else{
		foreach($arResultRev as $k => $arElement){
			$arProduct = CIblockElement::GetById($arElement[PROPERTY_PRODUCT_ID_VALUE])->GetNext();
			$PREVIEW_PICTURE = CFile::GetPath($arProduct[PREVIEW_PICTURE]);	
			$resQ = $DB->Query("
			SELECT 
				count(b_iblock_element_prop_s6.IBLOCK_ELEMENT_ID) AS 'count' 
			FROM 
				b_iblock_element,
				b_iblock_element_prop_s6 
			WHERE 
				b_iblock_element.ACTIVE='Y'
					AND
				b_iblock_element_prop_s6.IBLOCK_ELEMENT_ID=b_iblock_element.ID
					AND
				b_iblock_element_prop_s6.PROPERTY_16=".intval($arProduct[ID])
			);
			$rowQ = $resQ->Fetch();
			$SIZE = false;
			$db_props = CIBlockElement::GetProperty($arProduct[IBLOCK_ID], $arProduct[ID], array(), Array("CODE"=>"SIZE"));
			if($ar_props = $db_props->Fetch())
				$SIZE = $ar_props["VALUE"];
			//echo "<pre>"; print_r($arProduct); echo "</pre>"; 
		?>
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        	<div class="element shadow">
                <div class='top'>
                    <div class='box-name'>Отзыв: <span class="date"><?=substr($arElement[DATE_CREATE],0,10)?> г.</span></div>
                </div>
                <div class='box-title'><strong><?=$arElement[NAME]?><?=($arElement[PROPERTY_CITY_VALUE])?", г. ".$arElement[PROPERTY_CITY_VALUE]:""?></strong></div>
                <div class='cont-mod'>
                    <div class='picture'>
                        <?
                        if(intval($rowQ['count']) > 0){
							echo '<div class="view">Отзывов о товаре: <strong>'.number_format(intval($rowQ['count']), 0, '.', ' ').'</strong></div>';
						}
						?>
                        <a href="<?=$arProduct[DETAIL_PAGE_URL]?>#commentProduct" rel="nofollow" target="_blank">
                        <?
                        if(($arProduct[PREVIEW_PICTURE])AND(is_file($_SERVER["DOCUMENT_ROOT"].$PREVIEW_PICTURE))){
                            echo"<img src='".DOMAIN.$PREVIEW_PICTURE."' title='".str_replace('quot', "", preg_replace('/[^а-яa-z0-9-\s]/ui', '', $arProduct[NAME]))."' class='responsive-img'/>";
                        }else{
                            echo"<img src='".DOMAIN.SITE_TEMPLATE_PATH."/images/noimg.jpg' class='responsive-img'/>";
                        }
                        ?>
                        </a>
                    </div>
                    <div class='property'>
                        <h2><?=$arProduct[NAME]?></h2>
                        <div class="size"><?=$SIZE?></div>
                        <p><?=mb_strimwidth($arElement[PREVIEW_TEXT], 0, 180, "... ", "utf-8")?></p>
                        <a href="<?=$arProduct[DETAIL_PAGE_URL]?>#commentProduct" class="more" rel="nofollow" target="_blank"><strong>Подробнее...</strong></a>
                    </div>
                </div>
            </div>
        </div>
        <?	
		}
		echo "<div class='clear'></div>";
		echo $NAV_STRING;
	}
}else{
	echo "<div class='alert_error margin-30-15'>Внутренняя системная ошибка! Перезагрузите страницу.</div>";
}
?>