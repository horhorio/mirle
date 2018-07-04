<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$TIMESTAMP_X = $arResult[TIMESTAMP_X];
if($TIMESTAMP_X){
	//[TIMESTAMP_X] => 26.04.2017 09:41:38
	$date = new DateTime($TIMESTAMP_X);
	
	$LastModified_unix = $date->getTimestamp(); // время последнего изменения страницы
	$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
	$IfModifiedSince = false;
	if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
		$IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));  
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		$IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
	if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
		exit;
	}
	header('Last-Modified: '. $LastModified);
}

$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");
$arResult[sidebarFilter] = array();
$arResult[sidebarFilter] = $memcache_obj->get('sidebarFilter_'.$arResult[ID]);

if(empty($sidebarFilter)){
	$arFilter = Array("IBLOCK_ID"=>$arResult[IBLOCK_ID], "ACTIVE"=>"Y","SECTION_ID" => $arResult[ID]);
	
	$res = CIBlockElement::GetList(Array("PROPERTY_MANUFACTURER"=>"ASC"), $arFilter, array("PROPERTY_MANUFACTURER"), false, Array("PROPERTY_MANUFACTURER"));
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$resss = $DB->Query("SELECT NAME FROM b_iblock_element WHERE ID = ".$arFields[PROPERTY_MANUFACTURER_VALUE]);
		$row = $resss->Fetch();
		if($row[NAME])
			$sidebarFilter[BREND][] = array("NAME"=>$row[NAME],"ID"=>$arFields[PROPERTY_MANUFACTURER_VALUE]);
	}
	
	$res = CIBlockElement::GetList(Array("PROPERTY_DRAWING"=>"ASC"), $arFilter, array("PROPERTY_DRAWING"), false, Array("PROPERTY_DRAWING"));
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		if($arFields[PROPERTY_DRAWING_VALUE])
			$sidebarFilter[DRAWING][] = array("NAME"=>$arFields[PROPERTY_DRAWING_VALUE],"ID"=>$arFields[PROPERTY_DRAWING_ENUM_ID]);
	}
	
	$res = CIBlockElement::GetList(Array("PROPERTY_COLOR"=>"ASC"), $arFilter, array("PROPERTY_COLOR"), false, Array("PROPERTY_COLOR"));
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$db_enum_list = CIBlockProperty::GetPropertyEnum("COLOR", Array(), Array("IBLOCK_ID"=>$arResult[IBLOCK_ID],"ID"=>$arFields[PROPERTY_COLOR_ENUM_ID]));
		if($ar_enum_list = $db_enum_list->GetNext()){
			$sidebarFilter[COLOR][] = array("NAME"=>$ar_enum_list[VALUE],"ID"=>$ar_enum_list[ID],"COLOR"=>$ar_enum_list[XML_ID]);
		}
	}
	
	$res = CIBlockElement::GetList(Array("PROPERTY_SIZE"=>"ASC"), $arFilter, array("PROPERTY_SIZE"), false, Array("PROPERTY_SIZE"));
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		if($arFields[PROPERTY_SIZE_VALUE])
			$sidebarFilter[SIZE][] = array("NAME"=>trim($arFields[PROPERTY_SIZE_VALUE]));
	}
	
	$res = CIBlockElement::GetList(Array("PROPERTY_MATERIAL"=>"ASC"), $arFilter, array("PROPERTY_MATERIAL"), false, Array("PROPERTY_MATERIAL"));
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		if($arFields[PROPERTY_MATERIAL_VALUE])
			$sidebarFilter[MATERIAL][] = array("NAME"=>$arFields[PROPERTY_MATERIAL_VALUE],"ID"=>$arFields[PROPERTY_MATERIAL_ENUM_ID]);
	}

	$memcache_obj->set('sidebarFilter_'.$arResult[ID], $sidebarFilter, false, 1209600);//2 Неделя
	$arResult[sidebarFilter] = $memcache_obj->get('sidebarFilter_'.$arResult[ID]);
	//echo '<pre>'; print_r($sidebarFilter); echo '</pre>';
}

//echo '<pre>'; print_r($_GET); echo '</pre>';
//echo '<pre>'; print_r($sidebarFilter); echo '</pre>';
$parrentSectID = false;
$arResult[parrentSect] = array();
$nav = CIBlockSection::GetNavChain(false, $arResult['ID'],array("ID","NAME","CODE")); 
while($res = $nav->ExtractFields("nav_")){ 
	$arResult[parrentSect][] = $res;
}

//echo '<pre>'; print_r($parrentSect); echo '</pre>';
$parrentSectID = $arResult[parrentSect][0]['ID'];
//echo 'arResParSect_'.$parrentSectID;
if($parrentSectID){
	$arResult[arResParSect] = array();
	$arResult[arResParSect] = $memcache_obj->get('arResParSect_'.$parrentSectID);
	
	if(empty($arResult[arResParSect])){
		$rsParentSection = CIBlockSection::GetByID($parrentSectID);
		if ($arParentSection = $rsParentSection->GetNext())
		{
		   $arFilter = array('ACTIVE'=>'Y','IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); 
		   $bIncCnt = Array('CNT_ACTIVE' => "Y");
		   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc','sort' => 'asc'),$arFilter,$bIncCnt,array("ID","NAME","CODE","IBLOCK_SECTION_ID","DEPTH_LEVEL"));
		   while ($arSect = $rsSect->GetNext())
		   {
			   if($arSect[ELEMENT_CNT] > 0)
					$arResParSect[] = $arSect;
		   }
		}
		$memcache_obj->set('arResParSect_'.$parrentSectID, $arResParSect, false, 1209600);//2 Неделя
		$arResult[arResParSect] = $arResParSect;
		//echo '<pre>'; print_r($arResParSect); echo '</pre>';
	}
	//echo '<pre>'; print_r($arResParSect); echo '</pre>';
}
$memcache_obj->close();


$BRAND = array();
$COLOR = array();
$SIZE = array();
$MATERIAL = array();
$DRAWING = array();
if(!empty($_GET)){
	foreach($_GET as $k => $v){
		$ex = explode('_',$k);
		if ($ex[0] == "BREND")
			$BREND[] = $v;
		if ($ex[0] == "COLOR")
			$COLOR[] = $v;
		if ($ex[0] == "SIZE")
			$SIZE[] = $v."%";
		if ($ex[0] == "MATERIAL")
			$MATERIAL[] = $v;
		if ($ex[0] == "DRAWING")
			$DRAWING[] = $v;
	}
}
$minmax_price=array();
$arFilter = Array(
	"IBLOCK_ID"=>$arResult[IBLOCK_ID], 
	"ACTIVE"=>"Y",
	"SECTION_ID" => $arResult[ID],
	"PROPERTY_DRAWING"=>$DRAWING, 
	"PROPERTY_COLOR"=>$COLOR, 
	"PROPERTY_SIZE"=>$SIZE, 
	"PROPERTY_MATERIAL"=>$MATERIAL, 
	"PROPERTY_MANUFACTURER"=>$BREND,
);	
$res = CIBlockElement::GetList(Array("catalog_PRICE_1"=>"ASC"), $arFilter, false, array("nPageSize"=>1), Array("ID"));
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	//$ar_res = CPrice::GetBasePrice($arFields[ID]);
	//echo '<pre>'; print_r($arFields); echo '</pre>';
	$arResult[minmax_price]["MIN"] = intval($arFields[CATALOG_PRICE_1]);
}
$res = CIBlockElement::GetList(Array("catalog_PRICE_1"=>"DESC"), $arFilter, false, array("nPageSize"=>1), Array("ID"));
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	//$ar_res = CPrice::GetBasePrice($arFields[ID]);
	//echo '<pre>'; print_r($arFields); echo '</pre>';
	$arResult[minmax_price]["MAX"] = intval($arFields[CATALOG_PRICE_1]);
}
//$minmax_price = array_values(array_diff(array_unique($minmax_price), array('')));
//$arResult[minmax_price] = array("MIN"=>ceil(min($minmax_price)),"MAX"=>ceil(max($minmax_price)));
//echo '<pre>'; print_r($arResult[minmax_price]); echo '</pre>';
?>