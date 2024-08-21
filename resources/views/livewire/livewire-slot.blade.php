@extends('manager.includes.app')

@section('title', getuserType() . ' - ' . ' Reports')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/extra-libs/prism/prism.css') }}">
    <style>
        .custom-border {
            border: 1px solid #F0F0F0;
            border-left: 2px solid #3BAFDA;
            border-radius: 4px;
        }
    </style>
@endpush

{{-- ==== Breadcumb ======== --}}
{{-- {{-- @section('current', 'Order') --}}
@section('page_name', 'New Order')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid row">

        {{ $slot }}

    </div>
@endsection

@push('scripts')
{{-- @stack('scripts') --}}
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js')}}"></script>

    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('zero_config');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || ('speclus_%DD%-%MM%-%YY%-month(%MM%).' + (type || 'xlsx')));
        }
    </script>
@endpush
