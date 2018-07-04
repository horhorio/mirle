<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//echo "<pre>"; print_r($arResult[SECTIONS]); echo "</pre>";
if(!empty($arResult[SECTIONS])){
?>
<div class="index-departments">
	<div class="wrapp">
    <h3 class="h3-head margin-top-none"><span class="before"></span>Все разделы интрнет-магазина<span class="after"></span></h3>
    </div>
    <div class="department wrapp">
    <?
    foreach($arResult[SECTIONS] as $key => $arSection){
    ?>
        <div class='col-lg-3 col-md-3 col-sm-6 col-xs-6'>
            <a href="<?=DOMAIN.$arSection[SECTION_PAGE_URL]?>" class="element shadow">
                <div class="element-hover"></div>
                <div class="element-bg">
                    <div class="img">
                    <?
                    if($arSection["PICTURE"][SRC])
						$img = DOMAIN.$arSection["PICTURE"][SRC];
					else
						$img = DOMAIN.SITE_TEMPLATE_PATH."/images/product-test/1.jpg";
					?>
                    <img src="<?=$img?>" alt="<?=$arSection[NAME]?>" class="responsive-img">
                    </div>
                    <div class="name">
                        <h3 title="<?=$arSection[NAME]?>"><?=$arSection[NAME]?></h3>
                        <?
                        $num = abs($arSection[ELEMENT_CNT]) % 100;
                        $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
                        if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
                            $prd = " товаров";
                        if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
                            $prd = " товара";
                        if ($num_x == 1) // иначе если оканчивается на 1
                            $prd = " товар";
                        if ($num == 0)
                            $prd = " товаров";
                        ?>
                        <span><?=$arSection[ELEMENT_CNT].$prd?></span>
                    </div>
                </div>
            </a>
        </div>
    <?
    }
    ?>
    <div class="clear"></div>
    </div>
</div>
<?
}
?>