    @extends('layouts.backend')

    @section('content')
    <div id="page-container">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="hero">
          <div class="hero-inner text-center">
            <div class="bg-body-extra-light">
              <div class="content content-full overflow-hidden">
                <div class="py-4">
                  <!-- Error Header -->
                  <h1 class="display-1 fw-bolder text-flat">
                    403
                  </h1>
                  <h2 class="h4 fw-normal text-muted mb-5">
                    We are sorry but you do not have permission to access this page..
                  </h2>
                  <!-- END Error Header -->

                </div>
              </div>
            </div>
            <div class="content content-full text-muted fs-sm fw-medium">
              <!-- Error Footer -->
              <p class="mb-1">
                Would you like to let us know about it?
              </p>
              <a class="link-fx" href="javascript:void(0)">Report it</a> or <a class="link-fx" href="{{  route('dashboard') }}">Go Back to Dashboard</a>
              <!-- END Error Footer -->
            </div>
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    @endsection