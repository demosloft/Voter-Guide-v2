<h1>Questions</h1>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="dab_dashboard">
    <h2>Questionnaire: </h2>
    <form action="" id="questions_form" name="questions_form" method="POST">
        <?php
        $quednary = get_option('dab_field_option_name');
        $count = '';
        if (is_array($quednary)) {
            $count = count($quednary);
        } ?>
        <table id="repeatable-fieldset-one" data-questions="<?= $count ?: 1; ?>" width="100%">
            <tbody>
            <?php

            if ($count > 0) {
                $i = 1;
                foreach ($quednary as $fields) {
                    $field_option = array_key_exists("field_options", $fields) ? $fields['field_options'] : '';
                    $field_type = $fields['field_type'];
                    $field_name = $fields['field_name'];
                    $field_position = $fields['field_position'];
                    echo questionnaire_fields($field_type, $field_name, $field_option, $i, $field_position);
                    $i ++;
                }
            } else {
                // show a blank question
                add_meta_row_dab(0);
            } ?>
            </tbody>
        </table>

        <p><a id="add-row" class="button" href="#">Add another</a></p>
        <div class="submit-btn">
            <button class="button" type="submit" name="dab_submit" class="btn btn-primary">Submit <i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
        </div>
    </form>
</div>
