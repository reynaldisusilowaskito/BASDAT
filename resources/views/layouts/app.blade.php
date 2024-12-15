<!DOCTYPE html>
<html>

<head>
    <title>Aplikasi Pengadaan Barang</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
 

</head>

<div id="app">
    @include('layouts.nav')

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.sidebar')
            </div>
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>
</div>
@yield('scripts')
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.all.min.js"></script>
</html>
