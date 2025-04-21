<div>
    @if (count($orders)>0)
        <div class="ml-1 row alert alert-warning alert-rounded col-lg-12 col-md-12 col-sm-12 d-flex align-content-center">
            <h4 class="card-title">You Have {{count(($orders))}} Unprocessed orders from uploaded Products</h4>
            <hr>
            <div class="d-flex align-items-center justify-content-between">

                <span wire:loading wire:target="processOrders">
                    <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="50" alt=""> Loading Please Wait.....
                </span>
                <a href="#!" wire:click="processOrders" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                    <i class="fa fa-plus"></i> click To Process Orders
                </a>
            </div>
        </div>
    @endif
</div>