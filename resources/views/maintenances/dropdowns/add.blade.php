{{-- Add Modal --}}
<div class="modal fade" id="addModal" data-backdrop="static" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Dropdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dropdowns.store') }}" class="needs-validation" method="POST" novalidate>
                    @csrf

                    {{-- Name Input --}}
                    <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
    
                        <input class="form-control" type="text" id="addname" name="name" required>
                                
                        <div class="invalid-feedback">
                            This is required. 
                        </div>
                    </div>

                    {{-- Options Input --}}
                    <div class="form-group">
                        <x-jet-label value="{{ __('Options') }}" class="mb-n2"/>
                        <table class="table table-borderless m-n2" id="dynamic_form">
                            <tr>
                                <td>
                                    <input class="form-control" type="text" id="addlabel" value="" name="label[]" required>
                                    <div class="invalid-feedback">
                                        This is required. 
                                    </div>
                                </td>
                                <td>
                                    <button type="button" name="add" id="addOption" class="form-control btn btn-success"><i class="fas fa-plus"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success mb-2 mr-2">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>