<form id="addFieldForm" action="../wp-content/plugins/form-contact-angular/traitement/insert_field.php" method="post">

    <h2><?php echo __('Add a form field', 'contactform'); ?></h2>

    <table class="form-table">

        <thead>
        <tr>
            <th><?php echo __('Input', 'contactform'); ?><span class="req-field">*</span></th>
            <th><?php echo __('Field name', 'contactform'); ?><span class="req-field">*</span></th>
            <th><?php echo __('Placeholder', 'contactform'); ?></th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>
                <select id="addInputType" name="type">
                    <option><?php echo __('Select', 'contactform'); ?></option>
                    <option value="text">Input (text)</option>
                    <option value="email">Input (mail)</option>
                    <option value="number">Input (number)</option>
                    <option value="textarea">Textarea</option>
                </select>
                <input id="inputTagname" type="hidden" name="tagname">
            </td>
            <td><input id="addInputName" name="name"></td>
            <td><input name="placeholder"></td>
        </tr>
        <tr class="last-tr">
            <td colspan="3"><input type="submit" value="<?php echo __('Save field', 'contactform'); ?>" class="button button-primary"><p class="req-field">* <?php echo __('Required fields', 'contactform'); ?></p></td>
        </tr>
        </tbody>

    </table>

</form>