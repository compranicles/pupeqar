<div class="card">
    <div class="card-body card_show">
        <div class="table-responsive">
          <table class="table table-borderless show_table">
              @foreach ($formFields as $field)
                  @switch($field->field_type_name)
                      @case("text")
                          @include('maintenances.showtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("number")
                          @include('maintenances.showtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("date")
                          @include('maintenances.showtemplates.date', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      {{-- @case("date-range")
                          @include('maintenances.showtemplates.daterange', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break --}}
                      @case("currency-decimal")
                          @include('maintenances.showtemplates.decimal', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A', 'currency' => $value['currency_'.$field->name] ?? 'N/A'])    
                          @break
                      @case("dropdown")
                          @include('maintenances.showtemplates.dropdown', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("textarea")
                          @include('maintenances.showtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      {{-- @case("multiple-file-upload")
                          @include('maintenances.showtemplates.multiplefileupload', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break --}}
                      @case("decimal")
                          @include('maintenances.showtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("college")
                          @include('maintenances.showtemplates.college', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("department")
                          @include('maintenances.showtemplates.department', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("yes-no")
                          @include('maintenances.showtemplates.text', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @case("percentage")
                          @include('maintenances.showtemplates.percentage', ['fieldInfo' => $field, 'value' => $value[$field->name] ?? 'N/A'])
                          @break
                      @default
                          
                      @endswitch
                      
                      @endforeach
                    
                    


          </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    if ()
</script>
@endpush