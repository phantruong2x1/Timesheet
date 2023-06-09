<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - DGT TimeSheet</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/js/select.dataTables.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('assets/images/icon-digitran-logo.png')}}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/b71be33871.js" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/customer.js')}}"></script>

    <style>
        .badge {
          font-size: 11px;
        }
        .table td {
          padding: 6px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        @include('frontend.layouts.partials.navbar')
        <!-- partial -->

        <div class="container-fluid page-body-wrapper">

            <!-- partial:partials/_settings-panel.html -->
            {{-- @include('frontend.layouts.partials.settings-panel') --}}
            <!-- partial -->

            <!-- partial:partials/_sidebar.html -->
            @include('frontend.layouts.partials.sidebar')
            <!-- partial -->

            
            <!-- content -->
            @yield('content')
            

        </div>   
        <!-- page-body-wrapper ends -->

        <!-- partial:partials/_footer.html -->
        @include('frontend.layouts.partials.footer')
        <!-- partial -->
        
    </div>
    <!-- container-scroller -->
     
    <!-- Toast Message UI -->
    <div id="toast-customer"></div>
    <!-- End Toast Message UI -->

    <!-- plugins:js -->
    <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.select.min.js')}}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="{{asset('assets/js/settings.js')}}"></script>
    <script src="{{asset('assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset('assets/js/Chart.roundedBarCharts.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- End custom js for this page-->
    <script>
        // showToast('Success', 'success', '1234dsbdbf')
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>

    @foreach (['error', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('toast-' . $msg))
    <script>
        showToast('{{$msg}}', '{{ Session('toast-' . $msg)}}');
    </script>
    @endif
    @endforeach
        
    
</body>

</html>
