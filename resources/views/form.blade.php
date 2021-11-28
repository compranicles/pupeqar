<div class="row">
    @foreach ($formFields as $field)
        @switch($field->field_type_name)
            @case("text")
                @include('maintenances.fieldtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("number")
                @include('maintenances.fieldtemplates.number', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("date")
                @include('maintenances.fieldtemplates.date', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("date-range")
                @include('maintenances.fieldtemplates.daterange', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("currency-decimal")
                @include('maintenances.fieldtemplates.decimal', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? '', 'currency' => $value['currency'] ?? ''])    
                @break
            @case("dropdown")
                @include('maintenances.fieldtemplates.dropdown', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("textarea")
                @include('maintenances.fieldtemplates.textarea', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("multiple-file-upload")
                @include('maintenances.fieldtemplates.multiplefileupload', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("decimal")
                @include('maintenances.fieldtemplates.numberdecimal', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
            @case("college")
                @include('maintenances.fieldtemplates.college', ['fieldInfo' => $field, 'colleges' => $colleges ?? '', 'college_id' => $collegeOfDepartment[0]->id ?? '', 'department_id' => $value['department_id'] ?? ''])
                @break
            @case("department")
                @include('maintenances.fieldtemplates.department', ['fieldInfo' => $field])
                @break
            @case("yes-no")
                @include('maintenances.fieldtemplates.yes-no', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? ''])
                @break
        @endswitch
    @endforeach
</div>