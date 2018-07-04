<noindex>
<div class='col-lg-5 col-md-6 col-sm-12 no-padding'>
    <?
    /*$srtPrMin = ($_GET[PRICE_MIN])?($_GET[PRICE_MIN]):$arResult[minmax_price][MIN];
	$srtPrMax = ($_GET[PRICE_MAX])?($_GET[PRICE_MAX]):$arResult[minmax_price][MAX];
	if($srtPrMin == $srtPrMax){*/
	?>
    <div class="sortes-price">
        <div class='col-lg-3 col-md-4 hidden-sm hidden-xs sortname'>Цена:</div>
        <div class='col-lg-9 col-md-8 col-sm-12 col-xs-12'>
            <div class="wt-range">
                <div class="res">
                    <div class="left">от <strong><?=number_format(($_GET[PRICE_MIN])?$_GET[PRICE_MIN]:$arResult[minmax_price][MIN], 0, '.', ' ')?></strong> <span class="rouble">P<i>-</i></span></div>
                    <div class="right">до <strong><?=number_format(($_GET[PRICE_MAX])?$_GET[PRICE_MAX]:$arResult[minmax_price][MAX], 0, '.', ' ')?></strong> <span class="rouble">P<i>-</i></span></div>
                    <div class="clear"></div>
                </div>
                <input type="hidden" id="amountMin" value="<?=($_GET[PRICE_MIN])?$_GET[PRICE_MIN]:$arResult[minmax_price][MIN]?>">
                <input type="hidden" id="amountMax" value="<?=($_GET[PRICE_MAX])?$_GET[PRICE_MAX]:$arResult[minmax_price][MAX]?>">
                <div id="slider-range" class="slider-horizontal"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <?
	//}
	?>
</div>
<div class='col-lg-7 col-md-6 col-sm-12'>
    <div class="sortes-box">
        <div class="sortes">
        <?
        if(($_GET['sort'] == "no")OR((!$_GET['sort'])AND(!$_GET['price'])AND(!$_GET['stock'])))
            echo "не указывать";
        elseif($_GET['price'] == "desc")
            echo "убыванию цены";
        elseif($_GET['price'] == "asc")
            echo "возрастанию цены";
        elseif($_GET['stock'] == "in")
            echo "наличию";
        else
            echo "не указывать";
        ?>
        </div>
        <strong class="hidden-md">Сортировать по: </strong>
        <div class="clear"></div>
        <div id="section_menu">
            <a href='javascript:void(0);' rel="nofollow" data-name="не указывать" onClick="javascript:sortSect('sort','sort','no','не указывать')" class="mrow<? if(($_GET['sort'] == "no")OR((!$_GET['sort'])AND(!$_GET['price'])AND(!$_GET['stock'])))echo " act"?>">не указывать</a>
            <div class="sep" style=""></div>
            <a href='javascript:void(0);' rel="nofollow" data-name="уменьшению цены" onClick="javascript:sortSect('sort','price','desc','убыванию цены')" class="mrow<? if($_GET['price'] == "desc")echo " act"?>">убыванию цены</a>
            <div class="sep" style=""></div>
            <a href='javascript:void(0);' rel="nofollow" data-name="увеличению цены" onClick="javascript:sortSect('sort','price','asc','возрастанию цены')" class="mrow<? if($_GET['price'] == "asc")echo " act"?>">возрастанию цены</a>
            <div class="sep" style=""></div>
            <a href='javascript:void(0);' rel="nofollow" data-name="наличию" onClick="javascript:sortSect('sort','stock','in','наличию')" class="mrow<? if($_GET['stock'] == "in")echo " act"?>">наличию</a>
        </div>
    </div>
</div>
<div class="clear"></div>
</noindex>