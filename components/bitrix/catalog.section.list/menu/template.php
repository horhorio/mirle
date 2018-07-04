<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? 
//echo "<pre>"; print_r($arResult); echo "</pre>";
$dir = $APPLICATION->GetCurDir();
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");
$all_menu = array();
$all_menu = $memcache_obj->get('all_menu');
if(empty($all_menu)){
	$memcache_obj->set('all_menu', $arResult['SECTIONS'], false, 1209600);//2 Неделя
}
$memcache_obj->close();
$region = $_SESSION['dataCity'][city];
?>
<div class="menu-fixed">
<a href="javascript:void(0);" rel="nofollow" class="close-menu"></a>
<ul class="menu">
	<li>
		<a <?=($dir!='/novinki/') ? "class='act'" : "";?> <?=($dir!='/novinki/') ? "href='".DOMAIN.'/novinki/'."'" : "";?> title="Все новинки в интернет-магазине <?=SITE?>">Все новинки</a>
	</li>
	<li>
		<a <?=($dir!='/akcii/') ? "class='act'" : "";?> <?=($dir!='/akcii/') ? "href='".DOMAIN.'/akcii/'."'" : "";?> title="Акции и скидки в интернет-магазине <?=SITE?>">Все акции и скидки</a>
	</li>
    <li>
		<a <?=($dir!='/gift/') ? "class='act'" : "";?> <?=($dir!='/gift/') ? "href='".DOMAIN.'/gift/'."'" : "";?> title="Товары с подарками в интернет-магазине <?=SITE?>">Товары с подарками</a>
	</li>
	<?
    foreach ($arResult['SECTIONS'] as $arSection){
    if($arSection['DEPTH_LEVEL'] == 1){
		$arr = array();
		foreach ($arResult['SECTIONS'] as $arSection2){
    	if(($arSection2['IBLOCK_SECTION_ID'] == $arSection['ID'])AND($arSection2[ELEMENT_CNT] > 0)){
			$arr[] = $arSection2;
		}
		}
	?>
    <li>
		<a <?=($dir!=$arSection['SECTION_PAGE_URL']) ? "class='act'" : "";?> <?=($dir!=$arSection['SECTION_PAGE_URL']) ? "href='".DOMAIN.$arSection[SECTION_PAGE_URL]."'" : "";?> title="<?=$arSection['NAME']?>  купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arSection['NAME']?></a>
        <noindex><span class="cnt-sect"><?=$arSection[ELEMENT_CNT]?></span></noindex>
        <?
		if(!empty($arr)){
		?>
        <ul class="menum-drop">
        	<?
            foreach ($arr as  $k => $arItem){
				$arr2 = array();
				foreach ($arResult['SECTIONS'] as $arSect2){
				if(($arSect2['IBLOCK_SECTION_ID'] == $arItem['ID'])AND($arSect2[ELEMENT_CNT] > 0)){
					$arr2[] = $arSect2;
				}
				}
			?>
            <li class="menum-drop__item">
        		<a class="menum-drop__link<? if($dir==$arItem['SECTION_PAGE_URL'])echo" act"?>" <?=($dir!=$arItem['SECTION_PAGE_URL']) ? "href='".DOMAIN.$arItem[SECTION_PAGE_URL]."'" : "";?> title="<?=$arSection['NAME']?> <?=$arItem['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arItem[NAME]?></a>
                <noindex><span class="cnt-sect"><?=$arItem[ELEMENT_CNT]?></span></noindex>
                <?
				if(!empty($arr2)){
				?>
				<ul class="menum-dropin">
					<?
					foreach($arr2 as $arItem2){
						$arr3 = array();
						foreach ($arResult['SECTIONS'] as $arSect3){
						if(($arSect3['IBLOCK_SECTION_ID'] == $arItem2['ID'])AND($arSect3[ELEMENT_CNT] > 0)){
							$arr3[] = $arSect3;
						}
						}
					?>
					<li class="menum-dropin__item">
						<a class="menum-dropin__link<? if($dir==$arItem2['SECTION_PAGE_URL'])echo" act"?>" <?=($dir!=$arItem2['SECTION_PAGE_URL']) ? "href='".DOMAIN.$arItem2[SECTION_PAGE_URL]."'" : "";?> title="<?=$arSection['NAME']?> <?=$arItem['NAME']?> <?=$arItem2['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arItem2[NAME]?></a>
                        <noindex><span class="cnt-sect"><?=$arItem2[ELEMENT_CNT]?></span></noindex>
                        <?
						if(!empty($arr3)){
						?>
						<ul class="menum-dropin2">
                        	<?
							foreach($arr3 as $arItem3){
							?>
							<li class="menum-dropin__item2">
								<a class="menum-dropin__link<? if($dir==$arItem3['SECTION_PAGE_URL'])echo" act"?>" <?=($dir!=$arItem3['SECTION_PAGE_URL']) ? "href='".DOMAIN.$arItem3[SECTION_PAGE_URL]."'" : "";?> title="<?=$arSection['NAME']?> <?=$arItem['NAME']?> <?=$arItem2['NAME']?> <?=$arItem3['NAME']?> купить в г. <?=$region?> - интернет-магазин <?=SITE?>"><?=$arItem3[NAME]?></a>
                                <noindex><span class="cnt-sect"><?=$arItem3[ELEMENT_CNT]?></span></noindex>
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
			}
			?>
        </ul>
        <?
		}
		?>
	</li>
    <?
	}
	}
	?>
	
</ul>
</div>
<div class="menu-fixed-overlow"></div>
