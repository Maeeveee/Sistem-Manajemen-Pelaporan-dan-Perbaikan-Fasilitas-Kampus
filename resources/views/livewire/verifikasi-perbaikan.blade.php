<div>  
  @section('breadcrumbs')
      @php
          $breadcrumbs = [
              'Sarana Prasarana' => '',
              'Verifikasi Perbaikan' => route('verifikasi-perbaikan'),
          ];
      @endphp
      @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
  @endsection
  <title>Dashboard - Sarpras</title>
  <body>

  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <h2>Verifikasi Laporan Perbaikan</h2>
      <div class="d-block mb-4 mb-md-0">
      </div>
  </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


 
</div>