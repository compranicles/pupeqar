<x-app-layout>
    <div class="container db-container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="db-card p-4 bg-body rounded shadow-sm" style="background-color: white;">
                            <div class="p-3">
                                <i class="far fa-star home-icons text-right"></i>
                                <div class="float-right">
                                    <h2 class="text-right">{{ $sum }}</h2>
                                    <p class="text-right" id="quarter"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">

    </div>
    <script>
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        const d = new Date();
        let month1 = month[d.getMonth()-2];
        let month2 = month[d.getMonth()];
        document.getElementById("quarter").innerHTML = "Accomplishment reports from " + month1 + ' - ' + month2 + ' ' + new Date().getFullYear();
    </script>
</x-app-layout>
