<div class="row">
    @foreach ($formFields as $field)
        @switch($field->field_type_name)
            @case("textarea")
                @include('maintenances.fieldtemplates.textarea', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("multiple-file-upload")
                @include('maintenances.fieldtemplates.multiplefileupload', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
        @endswitch
    @endforeach
</div>