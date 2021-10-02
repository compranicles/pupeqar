@foreach ($formFields as $field)
    @switch($field->field_type_name)
        @case("text")
            @include('formbuilder.fieldtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name]])
            @break
        @case("number")
            @include('formbuilder.fieldtemplates.number', ['fieldInfo' => $field, 'value' => $value[$field->name]])
            @break
        @case("date-range")
            @include('formbuilder.fieldtemplates.daterange', ['fieldInfo' => $field, 'value' => $value[$field->name]])
            @break
        @case("decimal")
            @include('formbuilder.fieldtemplates.decimal', ['fieldInfo' => $field, 'value' => $value[$field->name]])    
            @break
        @case("dropdown")
            @include('formbuilder.fieldtemplates.dropdown', ['fieldInfo' => $field, 'value' => $value[$field->name]])
            @break
        @case("submit")
            @include('formbuilder.fieldtemplates.submit', ['fieldInfo' => $field])
        @default
    @endswitch
@endforeach