<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="reg-box">
    <div class="sog">Нажимая на кнопку <strong>"Вход"</strong>, <strong>"Изменить пароль"</strong>, <strong>"Регистрация"</strong>, вы даете согласие на обработку персональных данных <a href="<?=DOMAIN?>/legal/" target="_blank">в соответствии с условиями</a></div>
    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 auth'>
        <h3>Зарегистрированные пользователи</h3>
        <form method="post" action="javascript:void(0);" id="userLogInForm">
        	<input type="hidden" name="back_url" value="<?=$_GET['back_url']?>">
            <input type="checkbox" name="CAPTCHA_AUTH">
            <label>Эл. почта:</label>
			<input type="text" name="MAIL_AUTH"  id="MAIL_AUTH" placeholder="Введите ваш адрес эл. почты">
            <label>Пароль:</label>
            <input type="password" name="PASS_AUTH"  id="PASS_AUTH" placeholder="Введите ваш пароль">
            <button name="submit" type="submit" class="flat_button" id="btnLogIn" onclick="javascript:sendData('userLogIn.php','userLogInForm','resAction','btnLogIn','Вход'); return false;"><strong>Вход</strong></button>
            <div class="clear"></div>
        </form>
        <h3 class="forgot">Вы забыли свой пароль?</h3>
        <form method="post" action="javascript:void(0);" id="userforgotForm">
        	<input type="hidden" name="back_url" value="<?=$_GET['back_url']?>">
            <input type="checkbox" name="CAPTCHA_FORGOT">
            <label>Эл. почта:</label>
			<input type="text" name="MAIL_FORGOT"  id="MAIL_FORGOT" placeholder="Введите ваш адрес эл. почты">
            <button name="submit" type="submit" class="flat_button" id="btnforgot" onclick="javascript:sendData('userforgot.php','userforgotForm','resAction','btnforgot','Изменить пароль'); return false;"><strong>Изменить пароль</strong></button>
            <div class="clear"></div>
        </form>
    </div>
    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 reg'>
        <h3>Регистрация нового пользователя</h3>
        <form method="post" action="javascript:void(0);" id="userRegForm" autocomplete="off">
			<input type="hidden" name="back_url" value="<?=$_GET['back_url']?>">
            <input type="checkbox" name="CAPTCHA_REG">
            <label>Имя:</label>
            <input type="text" name="NAME_REG"  id="NAME_REG" placeholder="Введите свое имя">
            <label>Фамилия:</label>
            <input type="text" name="SURNAME_REG"  id="SURNAME_REG" placeholder="Введите свою фамилию">
            <label>Эл. почта:</label>
            <input type="text" name="MAIL_REG"  id="MAIL_REG" placeholder="Введите ваш адрес эл. почты">
            <label>Пароль:</label>
            <input type="password" name="PASS_REG"  id="PASS_REG" placeholder="Введите ваш пароль">
            <label>Подтвердите пароль:</label>
            <input type="password" name="REPASS_REG"  id="REPASS_REG" placeholder="Введите пароль еще раз">
            <button name="submit" type="submit" class="flat_button" id="btnReg" onclick="javascript:sendData('userReg.php','userRegForm','resAction','btnReg','Регистрация'); return false;"><strong>Регистрация</strong></button>
            <div class="clear"></div>
        </form>
    </div>
    <div class="clear"></div>
</div>