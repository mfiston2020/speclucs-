@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-rounded  col-lg-7 col-md-9 col-sm-12">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span>
            </button>
        </div>
    @endforeach
@endif

{{-- ========== inserting error message ========== --}}
@if (session('errorMsg'))
    <div class="alert alert-danger alert-rounded col-lg-7 col-md-9 col-sm-12">
        <b>Error! </b>{{ session('errorMsg') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
    </div>
@enderror
{{-- Success message --}}
@if (session('successMsg'))
    <div class="alert alert-success col-6">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true">×</span>
        </button>
        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
        {{ session('successMsg') }}
    </div>
@endif
{{-- ========== Warning error message ========== --}}
@if (session('warningMsg'))
    <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
        <b>Warning! </b>{{ session('warningMsg') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true">×</span>
        </button>
    </div>
@enderror
