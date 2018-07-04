<div class="sidebar">
	<?
    if(!empty($arResult[arResParSect])){
		//Оказался лишний запрос
		/*$nav = CIBlockSection::GetNavChain(false,$Section['ID']);
		while($arSectionPath = $nav->GetNext()){
			$parrentSect[] = $arSectionPath;
		}
		$parrentSectID = $parrentSect[0][ID];
		//echo '<pre>'; print_r($parrentSect); echo '</pre>';
		$ress = $DB->Query("SELECT NAME FROM b_iblock_section WHERE ID=".$parrentSectID);
		$row = $ress->Fetch();*/
		//echo '<pre>';print_r($row);echo '</pre>';
    ?>
    <div class="sidebar-menu-box">
    <h4 class="sidebar-head hidden-sm hidden-xs"><strong><?=$arResult[parrentSect][0][NAME]?></strong></h4>
    <ul class="sidebar-menu hidden-sm hidden-xs">
    	<?
        foreach($arResult[arResParSect] as $arSection){
		if($arSection[DEPTH_LEVEL] == 2){
			$arrSect = array();
			foreach($arResult[arResParSect] as $key => $arSectionPar){
				if($arSection['ID'] == $arSectionPar['IBLOCK_SECTION_ID']){
					$arrSect[] = $arSectionPar;
				}
				
			}
			
		?>
        <li id="par_<?=$arSection['ID']?>" class="level-<?=$arSection[DEPTH_LEVEL]?>">
        	<a <? if($dir=="/".$arSection['CODE']."/")echo " class='act'"?> <?=($dir!="/".$arSection['CODE']."/") ? "href='/".$arSection['CODE']."/'" : "";?> title="<?=$arSection['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arSection['NAME']?></a>
            <noindex><div class="cnt-sect"><span><?=number_format($arSection[ELEMENT_CNT], 0, '.', ' ')?></span></div></noindex>
            <?
            if(!empty($arrSect)){
				$open = false;
				if($dir=="/".$arSection['CODE']."/")
					$open = " open";
				foreach($arrSect as $arItem){
					//echo '<pre>'; print_r($arItem); echo '</pre>';
					if($dir=="/".$arItem[CODE]."/"){
						$open = " open";
						break;
					}
				}
			?>
            <ul class="sidebar-drop<?=$open?>">
				<?
                foreach($arrSect as $arItem){
					$arrSect2 = array();
					foreach($arResult[arResParSect] as $key => $arSectionPar2){
						if($arItem['ID'] == $arSectionPar2['IBLOCK_SECTION_ID']){
							$arrSect2[] = $arSectionPar2;
						}
						
					}
                ?>
                <li class="level-<?=$arItem[DEPTH_LEVEL]?>"><a <? if($dir=="/".$arItem['CODE']."/")echo " class='act'"?> <?=($dir!="/".$arItem['CODE']."/") ? "href='/".$arItem['CODE']."/'" : "";?> title="<?=$arSection['NAME']?> <?=$arItem['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arItem['NAME']?></a>
                <noindex><div class="cnt-sect"><span><?=$arItem[ELEMENT_CNT]?></span></div></noindex>
                	<?
					if(!empty($arrSect2)){
						$open2 = false;
						if($dir=="/".$arItem['CODE']."/")
							$open2 = " open";
						foreach($arrSect2 as $arItem2){
							//echo '<pre>'; print_r($arItem); echo '</pre>';
							if($dir=="/".$arItem2[CODE]."/"){
								$open2 = " open";
								break;
							}
						}
					?>
					<ul class="sidebar-drop2<?=$open2?>">
                    	<?
                        foreach($arrSect2 as $arItem2){
						?>
                        <li class="level-<?=$arItem[DEPTH_LEVEL]?>"><a <? if($dir=="/".$arItem2['CODE']."/")echo " class='act'"?> <?=($dir!="/".$arItem2['CODE']."/") ? "href='/".$arItem2['CODE']."/'" : "";?> title="<?=$arSection['NAME']?> <?=$arItem['NAME']?>  <?=$arItem2['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arItem2['NAME']?></a>
                        <noindex><div class="cnt-sect"><span><?=$arItem2[ELEMENT_CNT]?></span></div></noindex>
                        </li>
                        <?
						}
						?>
                    </ul>
					<?
                    }
                    ?>
                </li>
                <?
                }
                ?>
            </ul>
            <?
			}
			?>
        </li>
        <?
		}//foreach($arResult[arResParSect] as $arSection){
		}//if($arSection[DEPTH_LEVEL] == 2){
		?>
    </ul>
    </div>
    <?
    }
	if(!empty($arResult[sidebarFilter])){
		$BREND = array();
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
					$SIZE[] = $v;
				if ($ex[0] == "MATERIAL")
					$MATERIAL[] = $v;
				if ($ex[0] == "DRAWING")
					$DRAWING[] = $v;
			}
		}
    ?>
    <div class="sidebar-filter">	
        <?
        if(count($arResult[sidebarFilter][SIZE]) > 1){
		?>
    	<div class="sidebar-filter-item filt-size">
            <div class="filter-head" id="SIZE"><i class="t-icon-f turn"></i><strong>Размер<?=(count($SIZE) > 0)?" <b>(".count($SIZE).")</b>":""?></strong></div>
            <div class="filt-elems">
            <ul>
               <?
			   $checked = false;
               foreach($arResult[sidebarFilter][SIZE] as $filter){
					if(!empty($_GET)){
						$checked = false;
						foreach($_GET as $k => $v){
							$ex = explode('_',$k);
							if(($ex[0] == "SIZE")AND($v == $filter[NAME]))
								$checked = "checked";
						}
					}
				?>
                <li><input class="filt" <?=$checked?> id="<?=md5($filter[NAME])?>" name="SIZE_<?=substr(md5($filter[NAME]),0,5)?>" value="<?=$filter[NAME]?>" type="checkbox"><label for="<?=md5($filter[NAME])?>"><?=$filter[NAME]?></label></li>
                <?
				}
				?>
            </ul>
            </div>
		</div>
        <?
		}
		if(count($arResult[sidebarFilter][MATERIAL]) > 1){
		?>
		<div class="sidebar-filter-item">
            <div class="filter-head" id="MATERIAL"><i class="t-icon-f turn"></i><strong>Материал<?=(count($MATERIAL) > 0)?" <b>(".count($MATERIAL).")</b>":""?></strong></div>
            <div class="filt-elems">
            <ul>
                <?
				$checked = false;
                foreach($arResult[sidebarFilter][MATERIAL] as $filter){
					if(!empty($_GET)){
						$checked = false;
						foreach($_GET as $k => $v){
							$ex = explode('_',$k);
							if(($ex[0] == "MATERIAL")AND($v == $filter[ID]))
								$checked = "checked";
						}
					}
				?>
                <li><input <?=$checked?> class="filt" id="<?=md5($filter[NAME].$filter[ID])?>" name="MATERIAL_<?=substr(md5($filter[NAME].$filter[ID]),0,5)?>" value="<?=$filter[ID]?>" type="checkbox"><label for="<?=md5($filter[NAME].$filter[ID])?>"><?=$filter[NAME]?></label></li>
                <?
				}
				?>
            </ul>
            </div>
		</div>
        <?
		}
		if(count($arResult[sidebarFilter][BREND]) > 1){
		?>
		<div class="sidebar-filter-item">
            <div class="filter-head" id="BREND"><i class="t-icon-f turn"></i><strong>Производитель<?=(count($BREND) > 0)?" <b>(".count($BREND).")</b>":""?></strong></div>
            <div class="filt-elems">
            <ul>
                <?
				$checked = false;
                foreach($arResult[sidebarFilter][BREND] as $filter){
					if(!empty($_GET)){
						$checked = false;
						foreach($_GET as $k => $v){
							$ex = explode('_',$k);
							if(($ex[0] == "BREND")AND($v == $filter[ID]))
								$checked = "checked";
						}
					}
				?>
                <li><input class="filt" <?=$checked?> id="<?=md5($filter[NAME].$filter[ID])?>" name="BREND_<?=substr(md5($filter[NAME].$filter[ID]),0,5)?>" value="<?=$filter[ID]?>" type="checkbox"><label for="<?=md5($filter[NAME].$filter[ID])?>"><?=$filter[NAME]?></label></li>
                <?
				}
				?>
            </ul>
            </div>
		</div>
        <?
		}
		if(count($arResult[sidebarFilter][DRAWING]) > 1){
		?>
        <div class="sidebar-filter-item">
            <div class="filter-head" id="DRAWING"><i class="t-icon-f turn"></i><strong>Рисунок<?=(count($DRAWING) > 0)?" <b>(".count($DRAWING).")</b>":""?></strong></div>
            <div class="filt-elems">
            <ul>
                <?
				$checked = false;
                foreach($arResult[sidebarFilter][DRAWING] as $filter){
					if(!empty($_GET)){
						$checked = false;
						foreach($_GET as $k => $v){
							$ex = explode('_',$k);
							if(($ex[0] == "DRAWING")AND($v == $filter[ID]))
								$checked = "checked";
						}
					}
				?>
                <li><input class="filt" <?=$checked?> id="<?=md5($filter[NAME].$filter[ID])?>" name="DRAWING_<?=substr(md5($filter[NAME].$filter[ID]),0,5)?>" value="<?=$filter[ID]?>" type="checkbox"><label for="<?=md5($filter[NAME].$filter[ID])?>"><?=$filter[NAME]?></label></li>
                <?
				}
				?>
            </ul>
            </div>
		</div>
        <?
		}
		if(count($arResult[sidebarFilter][COLOR]) > 1){
		?>
		<div class="sidebar-filter-item">
            <div class="filter-head" id="COLOR"><i class="t-icon-f turn"></i><strong>Цвет<?=(count($COLOR) > 0)?" <b>(".count($COLOR).")</b>":""?></strong></div>
            <div class="filt-elems">
            <ul>
                <?
				$checked = false;
                foreach($arResult[sidebarFilter][COLOR] as $filter){
					if(!empty($_GET)){
						$checked = false;
						foreach($_GET as $k => $v){
							$ex = explode('_',$k);
							if(($ex[0] == "COLOR")AND($v == $filter[ID]))
								$checked = "checked";
						}
					}
				?>
                <li><input class="filt" <?=$checked?> id="<?=md5($filter[NAME].$filter[ID])?>" name="COLOR_<?=substr(md5($filter[NAME].$filter[ID]),0,5)?>" value="<?=$filter[ID]?>" type="checkbox"><label for="<?=md5($filter[NAME].$filter[ID])?>" style="border-left: 3px solid <?=$filter[COLOR]?>;"><?=$filter[NAME]?></label></li>
                <?
				}
				?>
            </ul>
            </div>
		</div>	
        <?
		}
		?>
    </div>
    <?
	}
	?>
</div>
<?
$add = array();
if(CModule::IncludeModule("catalog")){
	$arSelect = array("ID","NAME","DETAIL_PAGE_URL","DETAIL_PICTURE");
	$arFilter = array("IBLOCK_ID"=>1, "ACTIVE"=>"Y","PROPERTY_STATUS"=>array(114),"!CATALOG_QUANTITY"=>"", "PROPERTY_MAIN"=>50);
	$res = CIBlockElement::GetList(array("RAND"=>"ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$img = CFile::ResizeImageGet($arFields[DETAIL_PICTURE], array('width'=>620, 'height'=>'350'), BX_RESIZE_IMAGE_EXACT, true);
		if(is_file($_SERVER["DOCUMENT_ROOT"].$img[src])){
			//echo '<pre>';print_r($arFields);echo '</pre>';
			$STATUS = $STATUS_XML_ID = $STATUS_ENUM_ID = false;
			$db_props = CIBlockElement::GetProperty(1, $arFields[ID], array(), Array("CODE"=>"STATUS"));
			if($ar_props = $db_props->Fetch()){
				$STATUS = $ar_props[VALUE_ENUM];
				$STATUS_XML_ID = $ar_props[VALUE_XML_ID];
				$STATUS_ENUM_ID = $ar_props[VALUE];
			}
			$add[] = array(
					"PREVIEW_PICTURE"=>DOMAIN.$img[src],
					"NAME" => $arFields[NAME],
					"DETAIL_PAGE_URL" => DOMAIN.$arFields["DETAIL_PAGE_URL"],
					"STATUS" => array("VALUE"=>$STATUS,"XML_ID" => $STATUS_XML_ID,"ENUM_ID" => $STATUS_ENUM_ID),
			);
		}
	}
}
//echo '<pre>';print_r($add);echo '</pre>';
if(!empty($add)){
echo '<div class="advertising hidden-sm hidden-xs">';
foreach($add as $val) {
	echo "<div class='col-xs-12 no-padding'>";
		echo "<a href='".$val[DETAIL_PAGE_URL]."' class='advert'>";
			echo "<div class='status-goods ".$val[STATUS][XML_ID]."'><span>".$val[STATUS][VALUE]."</span><i></i></div>";
				
			echo "<div class='img'>";
				echo "<img src='".$val[PREVIEW_PICTURE]."' alt='".$val[NAME]."' class='responsive-img'>";
			echo "</div>";
			echo "<p>".$val[NAME]."</p>";
		echo "</a>";
	echo "</div>";
}
echo "<div class='clear'></div>";
echo "</div>";
}
?>