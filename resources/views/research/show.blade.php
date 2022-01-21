<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Research Information') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Research Registration </h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        {{-- @if ($research->nature_of_involvement == 11)
                                            <a class="btn btn-secondary btn-sm mr-1" href="{{ route('research.manage-researchers', $research->research_code) }}">Manage Researchers</a>
                                        @else --}}
                                            {{-- <a class="btn btn-secondary btn-sm mr-1" href="{{ route('research.manage-researchers', $research->research_code) }}"></a>
                                        @endif --}}
                                        
                                        <div>
                                            <button class="btn btn-sm btn-dark mr-3" data-bs-toggle="modal" data-bs-target="#addResearcher" data-bs-whatever="{{ $research->title }}"><i class="bi bi-person-plus mr-2"></i>Add Co-researchers</button>
                                        </div>
                                        @include('research.options', ['research_id' => $research->id, 'research_status' => $research->status, 'involvement' => $research->nature_of_involvement, 'research_code' => $research->research_code])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <fieldset id="research">
                            @include('show', ['formFields' => $researchFields, 'value' => $value])
                        </fieldset>

                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
                            </div>
                         </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-file-alt mr-2"></i>Documents</h6>
                                <div class="row">
                                    @if (count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-12 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-image mr-2"></i>Images</h6>
                                <div class="row">
                                    @if(count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Images Attached</h6>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!--Modal for sharing-->
    <div class="modal fade" id="addResearcher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Share with co-researchers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('dropdowns.store') }}" class="needs-validation" method="POST" novalidate>
                <div class="form-group">
                    <table class="table table-borderless m-n2" id="dynamic_form">
                        <tr>
                            <td>
                                <div id="badges">

                                </div>
                                <input type="text" class="form-control input-parent" id="coresearcher-1" placeholder="Add co-researchers">
                                <div id="researcher-list">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Done</button>
            </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
        document.getElementById('researcher-list').style.display = "none";

        var getCoresearcher = (this).value;
            // console.log(getCoresearcher);
            <?php
                for ($i = 0; $i < count($people); $i++){ ?>
                    if (getCoresearcher === "{{ $people[$i]->last_name.", ".$people[$i]->first_name }}") {
                        document.getElementById('researcher-list').style.display = "block";
                        const para = document.createElement("p");
                        // para.innerText = "This is a paragraph";
                        para.innerHTML = "{{ $people[$i]->last_name.', '.$people[$i]->first_name.' '.$people[$i]->middle_name.' '.$people[$i]->suffix }}";
                        document.getElementById('researcher-list').appendChild(para);
                        console.log(researcher);
                    }
            <?php } ?>

        $('input#coresearcher-1').keyup(function () {
            var getCoresearcher = (this).value;
            // console.log(getCoresearcher);
            <?php
                for ($i = 0; $i < count($people); $i++){ ?>
                   if (getCoresearcher === "{{ $people[$i]->last_name.", ".$people[$i]->first_name }}") {
                        document.getElementById('researcher-list').style.display = "block";

                        const para = document.createElement("p");
                        // para.innerText = "This is a paragraph";
                        para.innerHTML = "{{ $people[$i]->last_name.', '.$people[$i]->first_name.' '.$people[$i]->middle_name.' '.$people[$i]->suffix }}";
                        document.getElementById('researcher-list').appendChild(para);
                        var x = document.querySelectorAll("p");
                        for (let a = 0; a < x.length; a++) {
                            x[a].setAttribute("role", "button");
                        }
                        // console.log(researcher);
                    }
                    if (getCoresearcher === "") {
                        document.getElementById('researcher-list').style.display = "none";
                        $('p').remove();
                    }
            <?php } ?>
        });

        $('input#coresearcher-1').on('input', function () {
            document.getElementById('researcher-list').style.display = "none";
            $('p').remove();
        });

    </script>
    <script>
        var r = 1;
        var count = 0;
        var researchers = [];

        document.querySelector('#researcher-list').addEventListener('click', function(event){
        if(event.target.nodeName === 'P'){
            document.getElementById('coresearcher-1').value = "";
            const badge = document.createElement("span");
            badge.setAttribute("class", "badge rounded-pill bg-dark mb-2 mr-2");
            badge.setAttribute("id", "researcher-" + r);
            badge.innerHTML = event.target.innerHTML + '<i class="bi bi-x ml-2" id="remove-researcher-'+r+'" role="button"></i>';
            document.getElementById('badges').appendChild(badge);
            document.getElementById('researcher-list').style.display = "none";
            $('p').remove();

            researchers[count] = r;
            count++;
            r++;
            remove_researchers(researchers);
        }
    });
    </script>
    <script>
        function remove_researchers(researchers) {
            for (let r = 0; r < researchers.length; r++) {
                if (document.getElementById('remove-researcher-'+researchers[r])) {
                    document.getElementById('remove-researcher-'+researchers[r]).addEventListener('click', function(){
                        $('#researcher-'+researchers[r]+'').remove();
                        // alert('#researcher-'+researchers[rnum]+'');
                        console.log(researchers[r]);
                    });
                }
            }
        }
    </script>
@endpush

</x-app-layout>