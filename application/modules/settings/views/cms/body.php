<div>
    <form method='post'>
        <h1>Objednávky Excel Farba Kategórií</h1>
        <?php
        if (isset($categories)){
            foreach ($categories as $c){
                ?>
                <div class='form-line'>
                    <label for="<?php echo "category_color_".$c['id'];?>"><?php echo $c['category_name'];?></label>
                    <input id="<?php echo "category_color_".$c['id'];?>" class="color-picker" name="<?php echo "category_color_".$c['id'];?>" type='text' value="<?php echo set_value("category_color_".$c['id'], setting_value($settings, "category_color_".$c['id']));?>">
                </div>
                <?php
            }
        }
        ?>
        <div class="form-line">
            <input class="save" name="save" type="submit" value="Uložiť">
        </div>
    </form>
</div>
<div id="color-picker" class="pop-up"></div>