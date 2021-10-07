<div class="row">
    <div class="col-md-12">
        <div class="m-3">
            <h4>Fields</h4>
        </div>
    </div>
    <hr>

    <div class="col-md-12">
        <div class="mx-3">
            <div id="field_message"></div>
        </div>
    </div>

    {{-- ADD Button --}}
    <div class="col-md-12">
        <div class="mx-3 mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#addFieldModal">
                Add
            </button>
        </div>
    </div>
    <hr>
    {{-- save arrangements --}}

    <div class="col-md-12">
        <div class="mx-3 mb-3">
            <div class="mb-0">
                <div class="d-flex justify-content-end align-items-baseline">
                    <button class="btn btn-light mx-2" id="previewButton" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button>
                    <x-jet-button id="field_save_arrange" data-save="{{ route('admin.fields.arrange', $form_id) }}" >
                        {{ __('Save') }}
                    </x-jet-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Fields --}}
    <div class="col-md-6">
        <div class="mx-3 mb-3  overflow-auto table_container">
            <div class="table-responsive">
                <table id="hidden_fields_table" class="table table-sm table-bordered">
                    <tbody class="fieldsortable">
                        <tr>
                            <th colspan="2">
                                <h4 class="text-center">All Fields</h4>
                            </th>
                        </tr>
                        @forelse ($fields as $field)
                        @if ($field->status === 'hidden')
                        <tr data-id="{{ $field->id }}">
                            <td>{{ $field->label }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-field" data-toggle="modal" data-target="#editFieldModal" data-form="{{ $field->form_id }}" data-id="{{ $field->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-field" data-target="#deleteFieldModal" data-toggle="modal" data-id="{{ $field->id }}"  data-name="{{ $field->label }}">Delete</button>
                            </td>
                        </tr>
                        @endif
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- shown Fields --}}
    <div class="col-md-6">
        <div class="mx-3 mb-3  overflow-auto table_container">
            <div class="table-responsive">
                <table id="shown_fields_table" class="table table-sm table-bordered">
                    <tbody class="fieldsortable">
                        <tr>
                            <th colspan="2">
                                <h4 class="text-center">Shown Fields</h4>
                            </th>
                        </tr>
                        @forelse ($fields as $field)
                        @if ($field->status === 'shown')
                        <tr data-id="{{ $field->id }}">
                            <td>{{ $field->label }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-field" data-toggle="modal" data-target="#editFieldModal" data-form="{{ $field->form_id }}" data-id="{{ $field->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-field" data-target="#deleteFieldModal" data-toggle="modal" data-id="{{ $field->id }}" data-name="{{ $field->label }}">Delete</button>
                            </td>
                        </tr>
                        @endif
                        @empty
                        {{--  --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
</div>

{{-- add modal --}}
@include('formbuilder.fields.add', [
    'form_id' => $form_id,
    'fieldtypes' => $fieldtypes,
    'dropdowns' => $dropdowns,
])

{{-- edit modal --}}
@include('formbuilder.fields.edit', [
    'form_id' => $form_id,
    'fieldtypes' => $fieldtypes,
    'dropdowns' => $dropdowns
])

{{-- delete modal --}}
@include('formbuilder.fields.delete', [
    'form_id' => $form_id,
])

{{-- preview modal --}}
@include('formbuilder.forms.preview', ['form_id' => $form_id])