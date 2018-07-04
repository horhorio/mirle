<?
//echo "<pre>"; print_r($_SESSION['arUser']); echo "</pre>";
$UF_DISCONT = intval($_SESSION['arUser'][UF_DISCONT]);
$UF_AMOUNT = $_SESSION['arUser'][UF_AMOUNT];
$DISCONT_CARD = $_SESSION['arUser'][UF_DISCONT_CARD];
$res = $DB->Query("
SELECT 
	b_iblock_element.PREVIEW_TEXT,
	b_iblock_element_prop_s3.PROPERTY_7
FROM 
	b_iblock_element,
	b_iblock_element_prop_s3
WHERE 
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = b_iblock_element.ID
		AND
	b_iblock_element_prop_s3.IBLOCK_ELEMENT_ID = 8891
		AND
	b_iblock_element.ACTIVE='Y'
		AND
	b_iblock_element.IBLOCK_ID = 3
");
$row = $res->Fetch();
//echo '<pre>';print_r($row);echo '</pre>';
?>
<div class='personal-discont'>
<div class='col-md-3 col-sm-2 hidden-xs'></div>
<div class='col-md-6 col-sm-8 col-xs-12'>
	<div class="card discont-card-<?=($UF_DISCONT>0)?$UF_DISCONT:8?>">
    	<div class="card-slog">карта постоянного покупателя</div>
        <div class="card-percent"><?=$UF_DISCONT?><strong>%</strong></div>
        <img src="<?=DOMAIN.SITE_TEMPLATE_PATH?>/images/card/discont-card-<?=($UF_DISCONT>0)?$UF_DISCONT:8?>.png"  class="responsive-img card-img"/>
        <div class="card-num mask-card"><?=$DISCONT_CARD?></div>
    </div>
    
</div>
<div class='col-md-3 col-sm-2 hidden-xs'></div>
<div class="clear"></div>
<?
    if(strlen($row['PREVIEW_TEXT']) > 30){
    ?>
        <div class="information-box"><?=$row['PREVIEW_TEXT']?></div>
    <?
    }
    ?>
</div>