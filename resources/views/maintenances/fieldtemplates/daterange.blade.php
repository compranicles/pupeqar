{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} mb-3">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label>{{ $fieldInfo->label }} - From</label><?php if ($fieldInfo->required == 1) { echo "<span style='color: red'> *</span>"; } ?>
        
                <input type="date" name="{{ $fieldInfo->name }}[]" id="{{ $fieldInfo->name }}_from" value="{{ (isset($value[0]))? $value[0]: '' }}" 
                        class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
                        @switch($fieldInfo->visibility)
                            @case(2)
                                {{ 'readonly' }}
                                @break
                            @case(3)
                                {{ 'disabled' }}
                                @break
                            @case(2)
                                {{ 'hidden' }}
                                @break
                            @default
                                
                        @endswitch>

                        @error($fieldInfo->name)
                            <span class='invalid-feedback' role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
        
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label>To</label>
        
                <input type="date" name="{{ $fieldInfo->name }}[]" id="{{ $fieldInfo->name }}_to" value="{{  (isset($value[1]))? $value[1]: ''}}" min="" 
                    class="form-control" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
                    @switch($fieldInfo->visibility)
                        @case(2)
                            {{ 'readonly' }}
                            @break
                        @case(3)
                            {{ 'disabled' }}
                            @break
                        @case(2)
                            {{ 'hidden' }}
                            @break
                        @default
                            
                    @endswitch>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
         $(document).on('change', '#{{ $fieldInfo->name }}_from', function(){
            $('#{{ $fieldInfo->name }}_to').attr('min', this.value);
        });
    </script>
@endpush
