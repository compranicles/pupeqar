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

    <script>
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        const d = new Date();
        if (d.getMonth() == 0 || d.getMonth() == 1 ||d.getMonth() == 2) {
            var month1 = month[0];
            var month2 = month[2];
        }
        else if (d.getMonth() == 3 || d.getMonth() == 4 ||d.getMonth() == 5) {
            var month1 = month[3];
            var month2 = month[5];
        }
        else if (d.getMonth() == 6 || d.getMonth() == 7 ||d.getMonth() == 8) {
            var month1 = month[6];
            var month2 = month[8];
        }
        else if(d.getMonth() == 9 || d.getMonth() == 10 ||d.getMonth() == 11){
            var month1 = month[9];
            var month2 = month[11];
        }
        document.getElementById("quarter").innerHTML = "My accomplishment reports from " + month1 + ' - ' + month2 + ' ' + new Date().getFullYear();
        // document.getElementById("department").innerHTML = "Accomplishments of employees from" + month1 + ' - ' + month2 + ' ' + new Date().getFullYear();

    </script>
</x-app-layout>
