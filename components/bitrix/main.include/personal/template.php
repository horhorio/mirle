<?
//echo "<pre>"; print_r($_SESSION['arUser']); echo "</pre>"
?>
<div class='col-md-3 hidden-sm hidden-xs no-padding'></div>
<div class='col-md-9 col-sm-12 col-xs-12 no-padding'>
    <form action="javascript:void(0);" method="post" class="form-box" id="personalForm">
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>Ваше имя: <star>*</star></label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="NAME" type="text" id="NAME" placeholder="Ваше имя*" value='<?=$_SESSION['arUser'][NAME]?>'>
            </div>
            <div class="clear"></div>
        </div>
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>Ваша фамилия:</label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="LAST_NAME" type="text" id="LAST_NAME" placeholder="Ваша фамилия" value='<?=$_SESSION['arUser'][LAST_NAME]?>'>
            </div>
            <div class="clear"></div>
        </div>
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>Ваша эл. почта: <star>*</star></label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="EMAIL" id="EMAIL" type="text" placeholder="Ваша эл. почта*" value='<?=$_SESSION['arUser'][EMAIL]?>'>
            </div>
            <div class="clear"></div>
        </div>
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>Ваш номер телефона: <star>*</star></label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="PHONE" id="PHONE" class="mask" type="tel" placeholder="Ваш номер телефона 8(___)__-__-___" value='<?=$_SESSION['arUser'][PERSONAL_MOBILE]?>'>
            </div>
            <div class="clear"></div>
        </div>
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>День рождения:</label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="BIRTHDATE" id="BIRTHDATE" class="mask-date" type="tel" placeholder="День рождения __.__.____" value='<?=$_SESSION['arUser'][PERSONAL_BIRTHDAY]?>'<?=($_SESSION['arUser'][PERSONAL_BIRTHDAY])?" readonly='readonly'":""?>>
				<?
				if($_SESSION['arUser'][PERSONAL_BIRTHDAY])
					echo "<div class='desc'>Изменить дату рождения по тел.: <strong>".COMPANY_TEL_OPER."</strong></div>";
				?>
            </div>
            <div class="clear"></div>
        </div>
        <div class='field'>
            <div class='col-sm-4 col-xs-12 no-padding'>
            	<label>Получать новости:</label>
            </div>
            <div class='col-sm-8 col-xs-12 no-padding'>
            	<input name="SUB" type="checkbox" id="SUB" value='1' <?=($_SESSION['arUser'][UF_SUB]==1)?"checked":""?>>
                <label for="SUB" class="checkbox"></label>
          </div>
            <div class="clear"></div>
        </div>

        <div class="pass">
            <p class="pass-text">Хочу изменить пароль для входа в личный кабинет <strong>( не обязательно для заполнения )</strong></p>
            <div class='field'>
                <div class='col-sm-4 col-xs-12 no-padding'>
                    <label>Пароль:</label>
                </div>
                <div class='col-sm-8 col-xs-12 no-padding'>
                    <input name="PASS" id="PASS" type="text">
                </div>
                <div class="clear"></div>
            </div>
            <div class='field'>
                <div class='col-sm-4 col-xs-12 no-padding'>
                    <label>Пароль еще раз:</label>
                </div>
                <div class='col-sm-8 col-xs-12 no-padding'>
                    <input name="PASS_REP" id="PASS_REP" type="text">
                </div>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="sog">Нажимая на кнопку <strong>"Сохранить"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/legal/" target="_blank">в соответствии с условиями</a></div>
        <button name="submit" type="button" class="add-element" id="btnP" onclick="javascript:sendData('personal.php','personalForm','resAction','btnP','Сохранить'); return false;"><strong>Сохранить</strong></button>
    </form>
    </div>
<div class="clear"></div>