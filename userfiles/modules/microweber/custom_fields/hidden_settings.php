<?php include('settings_header.php'); ?>

<div class="custom-field-settings-name">
    <div class="mw-custom-field-group ">
        <label class="control-label" for="input_field_label<?php print $rand; ?>">
            <?php _e('Title'); ?>
        </label>
        <small class="text-muted d-block mb-2"><?php _e('The name of your field');?></small>
        <input type="text" class="form-control" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
    </div>
</div>

<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>

        <input type="text" class="form-control" name="value" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
    </div>
    <?php print $savebtn; ?>
</div>

<?php include('settings_footer.php'); ?>