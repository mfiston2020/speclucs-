<!-- Nav tabs -->
{{-- <ul class="nav nav-tabs" role="tablist"> --}}


    <div class="row">
            @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'store')
                <a href="{{ route('manager.product.create') }}"
                    class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary"
                    style="align-items: right;">
                    <i class="fa fa-plus"></i> New Product
                </a>
                <a onclick="exportAll('xlsx');" href="#"
                    class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success"
                    style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel
                </a>
                {{-- <a href="{{ route('manager.product.import') }}"
                    class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary"
                    style="align-items: right;">
                    <i class="fa fa-upload"></i> Import Excel
                </a> --}}

        @endif
                <a href="{{ route('manager.lens.stock', 0) }}"
                    class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-warning"
                    style="align-items: right;">
                    <i class="fa fa-inbox"></i> Lens Stock
                </a>

    </div>

<div class="row">
    <a href="#requested" role="tab">
        <span class="hidden-sm-up"><i class="ti-home"></i></span>
        <span class="hidden-xs-down">
            Requested Products
            <span class="badge badge-danger badge-pill">
                {{ count($requests) }}
            </span>
        </span>
    </a>

    <a class="nav-link" data-toggle="tab" href="#priced" role="tab">
        <span class="hidden-sm-up"><i class="ti-home"></i></span>
        <span class="hidden-xs-down">
            Priced
            <span class="badge badge-danger badge-pill">
                {{ count($requests_priced) }}
            </span>
        </span>
    </a>
</div>

<li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#po-sent" role="tab">
        <span class="hidden-sm-up"><i class="ti-home"></i></span>
        <span class="hidden-xs-down">
            PO Sent
            <span class="badge badge-danger badge-pill">
                {{ count($requests_supplier) }}
            </span>
        </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link " data-toggle="tab" href="#provided-to-lab" role="tab">
        <span class="hidden-sm-up">
            <i class="ti-home"></i>
        </span>
        <span class="hidden-xs-down">
            Provided to Lab
            <span class="badge badge-danger badge-pill">
                {{ count($requests_lab) }}
            </span>
        </span>
    </a>
</li>

{{-- </ul> --}}
