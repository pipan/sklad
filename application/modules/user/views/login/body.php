<div>
    <?php
    if (($error = validation_errors()) != "") {
        echo "<div class='form-error'>" . $error . "</div>";
    }
    echo form_open("cms/user/login/login");
        ?>
        <div class="form-line">
            <label for="name">Meno</label>
            <input id="name" name="name" type="text" value="<?php echo set_value('name'); ?>" autofocus>
        </div>
        <div class="form-line">
            <label for="password">Heslo</label>
            <input id="password" name="password" type="password">
        </div>
        <div class="form-line">
            <label for="remember">zapamätať prihlásenie?</label>
            <input id="remember" name="remember" type="checkbox" value='1'>
        </div>
        <div>
            <input class="save" name="login" type="submit" value=" Prihlásiť sa ">
        </div>
    </form>
</div>