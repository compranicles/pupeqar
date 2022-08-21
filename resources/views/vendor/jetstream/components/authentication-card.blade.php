<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="card login-card rounded-5">
                    <div class="d-flex justify-content-center mb-3 login-logo" style="border-bottom: 1px solid gainsboro;">
                        <div class="mb-3">
                            {{ $logo }}
                        </div>
                        <div class="system-name">
                            <img src="{{ URL('storage/slogan.png') }}" width="80" alt="">
                        </div>
                    </div>
                    <div>
                        <div class="ml-3 mr-3 text-center">
                            <h4 class="font-weight-bold text-dark">PUP electronic Quarterly Accomplishment Reporting</h4>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
