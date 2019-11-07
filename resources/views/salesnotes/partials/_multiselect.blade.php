$options = explode(",", $field['values']);
        $selected = explode(",", $field['value']);
        $fieldname = $field['id']."[]";
        echo Form::select($fieldname, $options, $selected, array('multiple'));