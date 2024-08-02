@section('title', getuserType() . ' - ' . ' User Perfomance Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'User Perfomance Report')
@section('page_name', 'User Perfomance Report')
{{-- === End of breadcumb == --}}

<div class="col-md-12">


    <div class="card">
        <form wire:submit='searchInformation'>
            @csrf
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.blur='start_date'>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.blur='end_date'>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove wire:target='searchInformation'>Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>
            </div>
        </form>
    </div>

    @if (!is_null($searchFoundSomething) && count($user_perfomance_report)>0)

        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4>Number Of Orders: 
                        <span class="text-warning">
                            {{number_format(count($user_perfomance_report))}}
                        </span>
                    </h4>
                    <a onclick="ExportToExcel('xlsx')" href="#" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                        <i class="fa fa-download"></i> Export To Excel
                    </a>
                </div>
                <div class="table-responsive mt-3">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Ordered</th>
                                <th>Priced</th>
                                <th>Confirmed</th>
                                <th>Sent To Supplier</th>
                                <th>Sent To Lab</th>
                                <th>In Production</th>
                                <th>Completed</th>
                                <th>Delivered</th>
                                <th>Received</th>
                                <th>Dispensed</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum=0;
                                $users_sum=0;
                            @endphp
                            @foreach ($company_users as $key => $user)

                                <tr>
                                    @php
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','requested')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','priced')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','Confirmed')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','sent to supplier')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','sent to lab')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->whereIn('status',['in production','in Process'])->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','completed')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','delivered')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','received')->count();
                                        $sum += $user_perfomance_report->where('user_id',$user->id)->where('status','dispensed')->count();
                                        $users_sum  +=  $sum;
                                    @endphp

                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','requested')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','priced')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','Confirmed')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','sent to supplier')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','sent to lab')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->whereIn('status',['in production','in Process'])->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','completed')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','delivered')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','received')->count() }}</td>
                                    <td>{{$user_perfomance_report->where('user_id',$user->id)->where('status','dispensed')->count() }}</td>
                                    <td>
                                        <span class="h5 text-bold text-info">{{ number_format($sum) }}</span>
                                    </td>
                                </tr>
                                @php
                                    $sum = 0
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th># </th>
                                <th>Name </th>
                                <th>Ordered </th>
                                <th>Priced </th>
                                <th>Confirmed</th>
                                <th>Sent To Supplier</th>
                                <th>Sent To Lab</th>
                                <th>In Production</th>
                                <th>Completed</th>
                                <th>Delivered</th>
                                <th>Received</th>
                                <th>Dispensed</th>
                                <th><span class="h5 text-bold text-info">{{ number_format($users_sum) }}</span> Total</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    @endif

    @if ($searchFoundSomething == 'no')
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning alert-rounded ">
                    Nothing Found from: <strong>{{ $start_date }}</strong> up to
                    <strong>{{ date('Y-m-d',strtotime(now())) }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                            aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
