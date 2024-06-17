@extends('manager.includes.app')

@section('title','Manager\'Clinic Settings')

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Clinic Settings')
@section('page_name','Clinic Settings')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Column -->

        @include('manager.includes.layouts.message')

        <div class="col-lg-12 col-xlg-12 col-md-12">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item ">
                    <a class="nav-link active" data-toggle="tab" href="#lens-pricing" role="tab">
                        <span class="hidden-sm-up"><i class="ti-control-record"></i></span>
                        <span class="hidden-xs-down">Lens Pricing</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#exam" role="tab">
                        <span class="hidden-sm-up">
                            <i class="ti-home"></i>
                        </span>
                        <span class="hidden-xs-down">Exams</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#insurance" role="tab">
                        <span class="hidden-sm-up"><i class="ti-bookmark"></i></span>
                        <span class="hidden-xs-down">Insurance</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#insurance_" role="tab">
                        <span class="hidden-sm-up"><i class="ti-email"></i></span>
                        <span class="hidden-xs-down">Insurance %</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#complaint" role="tab">
                        <span class="hidden-sm-up"><i class="ti-comment-alt"></i></span>
                        <span class="hidden-xs-down">Complaint</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#history" role="tab">
                        <span class="hidden-sm-up"><i class="ti-time"></i></span>
                        <span class="hidden-xs-down">History</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#drugs" role="tab">
                        <span class="hidden-sm-up"><i class="ti-email"></i></span>
                        <span class="hidden-xs-down">Drugs</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#hospitals" role="tab">
                        <span class="hidden-sm-up"><i class="ti-support"></i></span>
                        <span class="hidden-xs-down">Hospitals</span>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content tabcontent-border">

                <div class="tab-pane p-20 active" id="lens-pricing" role="tabpanel">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">Pricing List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                <span class="hidden-sm-up"><i class="ti-plus"></i></span>
                                <span class="hidden-xs-down">Add Pricing</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="home" role="tabpanel">
                            <div class="p-20 row" style="width:100%">
                                {{-- <div class="card">
                                    <div class="card-body"> --}}
                                        <div class="table-responsive">
                                            <table id="zero_config" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Sphere From</th>
                                                    <th>Sphere To</th>
                                                    <th>Cylinder From</th>
                                                    <th>Cylinder To</th>
                                                    <th>Addition From</th>
                                                    <th>Addition To</th>
                                                    <th>cost</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pricings as $key=> $pricing)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{lensDescription(initials($pricing->lenstype->name)).' '.$pricing->index->name.' '.$pricing->chromatics->name.' '.$pricing->coating->name}}</td>
                                                        <td>{{ $pricing->sphere_from }}</td>
                                                        <td>{{ $pricing->sphere_to }}</td>
                                                        <td>{{ $pricing->cylinder_from }}</td>
                                                        <td>{{ $pricing->cylinder_to }}</td>
                                                        <td>{{ $pricing->addition_from }}</td>
                                                        <td>{{ $pricing->addition_to }}</td>
                                                        <td>{{ format_money($pricing->cost)}}</td>
                                                        <td>{{ format_money($pricing->price)}}</td>
                                                        <td>
                                                            <form action="{{route('manager.clinic.pricing.remove',Crypt::encrypt($pricing->id))}}" method="post" onsubmit="return confirm('Are You sure ?')">
                                                                @csrf
                                                                {{ method_field('DELETE') }}
                                                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                    {{-- </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="profile" role="tabpanel">

                            <div class="col-md-12" id="lens">
                                <form action={{route('manager.clinic.settings.lens.pricing.save')}} method="post">
                                    @csrf
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Lens Description</h4>
                                            <div class="form-body">
                                                <div class="row">
                                                    <!--/span-->
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Lens Type</label>
                                                            <select class="form-control custom-select" name="lens_type" id="type" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach (\App\Models\LensType::get() as $lens_type)
                                                                    <option value="{{ $lens_type->id }}"
                                                                        {{ old('lens_type') == $lens_type->id ? 'selected' : '' }}>
                                                                        {{ $lens_type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Index</label>
                                                            <select class="form-control custom-select" name="index" id="index" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach (\App\Models\PhotoIndex::get() as $index)
                                                                    <option value="{{ $index->id }}"
                                                                        {{ old('index') == $index->id ? 'selected' : '' }}>
                                                                        {{ $index->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Chromatics Aspects</label>
                                                            <select class="form-control custom-select" name="chromatics" id="chrm" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach (\App\Models\PhotoChromatics::get() as $chromatics)
                                                                    <option value="{{ $chromatics->id }}"
                                                                        {{ old('chromatics') == $chromatics->id ? 'selected' : '' }}>
                                                                        {{ $chromatics->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Coating</label>
                                                            <select class="form-control custom-select" name="coating" id="coating" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach (\App\Models\PhotoCoating::get() as $coatings)
                                                                    <option value="{{ $coatings->id }}"
                                                                        {{ old('coating') == $coatings->id ? 'selected' : '' }}>
                                                                        {{ $coatings->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="power">

                                        <div class="card">
                                            <div class="card-body">

                                                <h4 class="card-title">Lens Power</h4>


                                                <div id="product-pricing" class=" m-t-20"></div>

                                                <div class="row">
                                                    <div class="col-md-12" id="eyes">
                                                        <div class="form-group row">
                                                            <div class="col-12 mt-3 d-flex justify-content-end">
                                                                <div class="form-group">
                                                                    <button class="btn btn-success" type="button"
                                                                        onclick="productPricingSection();"><i class="fa fa-plus"></i>
                                                                        Add Lens
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- =========== Powe division for addition =============== --}}
                                        {{-- <div class="col-md-12" id="action_buttons"> --}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group m-b-0 text-center">
                                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                                        <a href="{{ url()->previous() }}"
                                                            class="btn btn-dark waves-effect waves-light">Cancel</a>
                                                    </div>
                                                </div>
                                            {{-- </div> --}}
                                        </div>
                                        {{-- =========== Powe division for addition =============== --}}

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="exam" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">Exam List</h4>
                                    </div>
                                    <hr>
                                    {{-- ================================= --}}
                                    @include('manager.includes.layouts.message')
                                    {{-- ========================== --}}

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Exam Name</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($exams as $key=> $exam)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$exam->exam_name}}</td>
                                                    <td>{{format_money($exam->amount)}}</td>
                                                    <td class="text-right">
                                                        <a href="" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="#" data-toggle="modal" data-target="#delete-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong style="text-warning">{{$exam->exam_name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.exam.remove',Crypt::encrypt($exam->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Exam</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.clinic.exam.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="exam_name"
                                                    class="col-sm-3 text-right control-label col-form-label">Exam
                                                    name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="exam_name" class="form-control"
                                                        placeholder="Exam name" name="exam_name" required
                                                        value="{{old('exam_name')}}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="exam_price"
                                                    class="col-sm-3 text-right control-label col-form-label invalid">Exam
                                                    Price</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="exam_price"
                                                        placeholder="RWF" name="exam_price" required
                                                        value="{{old('exam_price')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save Exam</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane  p-20" id="insurance" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">Insurance List</h4>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Insurance Name</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($insurances as $key=> $insurance)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$insurance->insurance_name}}</td>
                                                    <td class="text-right">
                                                        <a href="" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="#" data-toggle="modal" data-target="#delete-insurance-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-insurance-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong class="text-warning">{{$insurance->insurance_name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.insurance.remove',Crypt::encrypt($insurance->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Insurance</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.clinic.insurance.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="insurance_name"
                                                    class="col-sm-4 text-right control-label col-form-label">Insurance
                                                    name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="insurance_name" class="form-control"
                                                        placeholder="insurance name" name="insurance_name" required
                                                        value="{{old('insurance_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save Insurance</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-20" id="insurance_" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">Insurance Percentages on exam</h4>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Insurance Name</th>
                                                    <th>Exam Name</th>
                                                    <th>Percentage</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($insurance_exams as $key=> $insurance)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{\App\Models\Insurance::where('id',$insurance->insurance_id)->pluck('insurance_name')->first()}}</td>
                                                    <td>{{\App\Models\Exam::where('id',$insurance->exam_id)->pluck('exam_name')->first()}}</td>
                                                    <td>{{$insurance->percentage}} %</td>
                                                    <td class="text-right">
                                                        <a href="" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="#" data-toggle="modal" data-target="#delete-exam-insurance-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-insurance-exam-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong class="text-warning">{{$insurance->insurance_name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.insurance.percentage.remove',Crypt::encrypt($insurance->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Insurance %</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.insurance.percentage.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="exam_name"
                                                    class="col-sm-4 text-right control-label col-form-label">Exam Name</label>
                                                <div class="col-sm-8">
                                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                                        name="exam" id="exam" required>
                                                        <option value="">Select</option>
                                                        @foreach ($exams as $exam)
                                                        <option value="{{$exam->id}}" {{(old('exam')==$exam->id)?'selected':''}}>
                                                            {{$exam->exam_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="exam_name"
                                                    class="col-sm-4 text-right control-label col-form-label">Insurance
                                                    name</label>
                                                <div class="col-sm-8">
                                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                                        name="insurance" id="insurance" required>
                                                        <option value="">Select</option>
                                                        @foreach ($insurances as $insurances)
                                                        <option value="{{$insurances->id}}" {{(old('insurance')==$insurance->id)?'selected':''}}>
                                                            {{$insurances->insurance_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="percentage"
                                                    class="col-sm-4 text-right control-label col-form-label">Insurance
                                                    %</label>
                                                <div class="col-sm-8">
                                                    <input type="number" id="percentage" class="form-control"
                                                        placeholder="insurance Percentage" name="percentage" required
                                                        value="{{old('percentage')}}" max="100">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save Insurance</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-20" id="complaint" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">Complaint List</h4>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Complaint Name</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($complaint as $key=> $complaint)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$complaint->name}}</td>
                                                    <td class="text-right">
                                                        {{-- <a href="" class="btn btn-primary btn-sm">Edit</a> --}}
                                                        <a href="#" data-toggle="modal" data-target="#delete-complaint-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-complaint-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong class="text-warning">{{$complaint->name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.complaint.remove',Crypt::encrypt($complaint->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Complaint</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.clinic.complaint.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="complaint_name"
                                                    class="col-sm-4 text-right control-label col-form-label">Complaint
                                                    name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="complaint_name" class="form-control"
                                                        placeholder="complaint name" name="complaint_name" required
                                                        value="{{old('complaint_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save Complaint</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-20" id="hospitals" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">Hospital List</h4>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Hospital Name</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hospitals as $key=> $hospital)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{ $hospital->hospital_name }}</td>
                                                    <td class="text-right">
                                                        {{-- <a href="" class="btn btn-primary btn-sm">Edit</a> --}}
                                                        <a href="#" data-toggle="modal" data-target="#delete-complaint-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-complaint-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong class="text-warning">{{$hospital->name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.complaint.remove',Crypt::encrypt($hospital->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Hospital</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.clinic.hospital.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="hospital_name"
                                                    class="col-sm-4 text-right control-label col-form-label">Hospital
                                                    name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="hospital_name" class="form-control"
                                                        placeholder="hospital name" name="hospital_name" required
                                                        value="{{old('hospital_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save Hospital</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-20" id="history" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h4 class="card-title">History List</h4>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="zero_config"
                                            class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>History Name</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($histories as $key=> $history)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$history->name}}</td>
                                                    <td class="text-right">
                                                        {{-- <a href="" class="btn btn-primary btn-sm">Edit</a> --}}
                                                        <a href="#" data-toggle="modal" data-target="#delete-history-{{$key}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                                <div id="delete-history-{{$key}}" class="modal fade" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel"><i
                                                                        class="fa fa-exclamation-triangle"></i> Warning
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Are you sure You want to delete
                                                                    <strong class="text-warning">{{$history->name}}</strong>???
                                                                </h4>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{route('manager.history.remove',Crypt::encrypt($history->id))}}"
                                                                    class="btn btn-info waves-effect">Yes</a>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect"
                                                                    data-dismiss="modal">No</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add History</h4>
                                    <hr>

                                    <form class="form-horizontal" action="{{route('manager.clinic.history.save')}}"
                                        method="POST" id="exam-form">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group row">
                                                <label for="history_name"
                                                    class="col-sm-4 text-right control-label col-form-label">History
                                                    name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="history_name" class="form-control"
                                                        placeholder="history name" name="history_name" required
                                                        value="{{old('history_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-b-0 text-right">
                                            <button id="submitButton" type="submit"
                                                class="btn btn-info waves-effect waves-light">Save History</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-20" id="drugs" role="tabpanel">
                    <div class="alert alert-info alert-rounded col-lg-7 col-md-9 col-sm-12">
                        <b><i class="fa fa-info-circle"></i> Info! </b>Feature Coming soon!!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/extra-libs/jquery.repeater/product-settings.js') }}"></script>
    <script>
        $('#exam-form').on('submit', function () {
            $('#submitButton').html('Saving Exam...');
            $('#submitButton').prop('diabled', true);
        });
    </script>
@endpush
