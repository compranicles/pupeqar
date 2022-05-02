<!-- Generate Report Modal -->
<div class="modal fade" id="GenerateSectorLevel" tabindex="-1" aria-labelledby="GenerateSectorLevelLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="GenerateSectorLevelLabel">Generate Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('report.generate.index', auth()->id()) }}" method="post" id="generate_ipo_form">
                    @csrf
                    <input type="hidden" name="source_generate" value="sector">
                    <div class="form-group">
                        <label for="type_generate">Format</label>
                        <select name="type_generate" id="type_generate" class="form-control" required>
                            <option value="" selected disabled>Choose...</option>
                            <option value="academic">Academic</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <input type="hidden" name="sector" value="{{ $sector->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_quarter_generate">Quarter Start</label>
                                <select name="from_quarter_generate" id="from_quarter_generate" class="form-control">
                                    <option value="1" {{$quarter== 1 ? 'selected' : ''}} class="quarter">1</option>
                                    <option value="2" {{$quarter== 2 ? 'selected' : ''}} class="quarter">2</option>
                                    <option value="3" {{$quarter== 3 ? 'selected' : ''}} class="quarter">3</option>
                                    <option value="4" {{$quarter== 4 ? 'selected' : ''}} class="quarter">4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_quarter_generate">Quarter End</label>
                                <select name="to_quarter_generate" id="to_quarter_generate" class="form-control">
                                    <option value="1" {{$quarter== 1 ? 'selected' : ''}} class="quarter">1</option>
                                    <option value="2" {{$quarter== 2 ? 'selected' : ''}} class="quarter">2</option>
                                    <option value="3" {{$quarter== 3 ? 'selected' : ''}} class="quarter">3</option>
                                    <option value="4" {{$quarter== 4 ? 'selected' : ''}} class="quarter">4</option>
                                </select>
                            </div>
                       </div>
                    </div>
                    <div class="form-group">
                        <label for="year_generate2">Year</label>
                        <select name="year_generate2" id="year_generate2" class="form-control" >
                        </select>
                    </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // var max = new Date().getFullYear();
        // var min = 0;
        // var diff = max-2022;
        // min = max-diff;
        // select = document.getElementById('from_year_generate');

        // var year = {!! json_encode($year) !!};
        // for (var i = max; i >= min; i--) {
        //     select.append(new Option(i, i));
        //     if (year == i) {
        //         document.getElementById("from_year_generate").value = i;
        //     }
        // }

        var max = new Date().getFullYear();
        var min = 0;
        var diff = max-2022;
        min = max-diff;
        select = document.getElementById('year_generate2');

        var year = {!! json_encode($year) !!};
        for (var i = max; i >= min; i--) {
            select.append(new Option(i, i));
            if (year == i) {
                document.getElementById("year_generate2").value = i;
            }
        }
        
        $('#sector').removeAttr('required');
        $('#sector').attr('disabled', true);

        $('#source_generate').on('change', function (){
            if ($(this).val() == 'ipo') {
                //Univ. Funded
                $('#sector').removeAttr('required');
                $('#sector').attr('disabled', true);
            }
            else if($(this).val() == 'sector'){
                $('#sector').removeAttr('disabled');
                $('#sector').attr('required', true);
            }
        });
    </script>
@endpush