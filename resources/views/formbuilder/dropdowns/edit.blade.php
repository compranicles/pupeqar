{{-- Edit Dropdown Modal --}}
<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Dropdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.dropdowns.update', ':id') }}" id="form_edit" class="needs-validation" method="POST" novalidate>
                    @csrf
                    @method('put')

                    {{-- Name Input --}}
                    <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
    
                        <input class="form-control" type="text" id="editname" name="name" value="" required>
                                
                        <div class="invalid-feedback">
                            This is required. 
                        </div>
                    </div>

                    {{-- Options Input --}}
                    <div class="form-group">
                        <x-jet-label value="{{ __('Options') }}" class="mb-n2"/>
                        <table class="table table-borderless" id="dynamic_edit_form">
                            <tr>
                                <th>Label</th>
                                <th>Value</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success mb-2 mr-2">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>