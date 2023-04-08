
<div class="col-md-12">
    <form action="#!" method="post">
        @csrf

        <div class="card">
                <div class="card-body">

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Year </label>
                                <select class="form-control custom-select">
                                    <option>--Select your Year--</option>
                                    <option>2023</option>
                                    {{-- <option>20</option>
                                    <option>March</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Month</label>
                                <select class="form-control custom-select">
                                    <option>--Select your Month--</option>
                                    @foreach ($months as $month)
                                        <option>{{$month}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Insurance</label>
                                <select class="form-control custom-select">
                                    <option>-- Select your Insurance --</option>
                                    @foreach ($insurance as $insurance)
                                        <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Search</button>

                </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Zero Configuration</h4>
                <hr>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered nowrap"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Ins</th>
                                <th>T. Amnt</th>
                                <th>Ins Due Amnt</th>
                                <th>Pt Due Amnt</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key=> $sale)
                                @php
                                    $client=\App\Models\Customer::where(['id'=>$sale->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first();

                                    $product=\App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                        ->where('company_id',Auth::user()->company_id)
                                                                        ->select('product_id','insurance_id','insurance_payment','patient_payment')
                                                                        ->first();

                                    $amount_paid    =   \App\Models\Transactions::where('invoice_id',$sale->id)->select('amount')->sum('amount');

                                    $ins_due_amount =   \App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                        ->where('company_id',Auth::user()->company_id)
                                                                        ->select('insurance_payment')
                                                                        ->sum('insurance_payment');

                                    $pt_due_amount =   \App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                        ->where('company_id',Auth::user()->company_id)
                                                                        ->select('patient_payment')
                                                                        ->sum('patient_payment');
                                @endphp
                                @if ($product->insurance_id!=null)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{date('Y-m-d',strtotime($sale->created_at))}}</td>
                                        <td>
                                            @if ($client)
                                                <center><span>{{$client}}</span></center>
                                            @else
                                                <center>
                                                    <span>{{$sale->client_name}}</span>
                                                </center>
                                            @endif
                                        </td>
                                        <td>
                                            {{\App\Models\Insurance::where('id',$product->insurance_id)->pluck('insurance_name')->first()}}
                                        </td>

                                        <td>{{format_money($sale->total_amount)}}</td>
                                        <td>
                                            {{format_money($ins_due_amount)}}
                                        </td>
                                        <td>
                                            @if ($product && $product->insurance_id!=null)
                                                {{format_money($pt_due_amount-$amount_paid)}}
                                            @else
                                                {{format_money($pt_due_amount-$amount_paid)}}
                                            @endif
                                        </td>
                                        {{-- <td >{{format_money($sale->due)}}</td> --}}
                                        <td>

                                            @if ($sale->status=='completed' && $sale->emailState=='submited')
                                                <span class="label label-warning">submited</span>
                                            @else
                                                <span class="label label-{{(($sale->status)=='completed')?'success':'danger'}}">{{$sale->status}}</span>
                                            @endif

                                            @if ($sale->payment=='paid')
                                                <span class="label label-success">{{$sale->payment}}</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($sale->status=='completed')
                                                @if ($sale->due!=0 )
                                                    <a href="{{route('manager.pay.invoice.due',Crypt::encrypt($sale->id))}}" class="btn btn-warning btn-sm">
                                                        Pay Due
                                                    </a>
                                                @else
                                                    <span class="label label-info">Fully Paid</span>
                                                @endif
                                            @else
                                                <a href="{{route('manager.sales.edit',Crypt::encrypt($sale->id))}}" class="btn btn-primary btn-sm">edit</a>
                                                <a href="#" data-toggle="modal" data-target="#myModal-{{$key}}" class="btn btn-danger btn-sm">delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                <div id="myModal-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{route('manager.invoice.delete',Crypt::encrypt($sale->id))}}" method="post">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                                        Warning</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">

                                                    <h4>Do you want to delete Invoice #{{sprintf('%04d',$sale->reference_number)}}?</h4>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit"
                                                        class="btn btn-info waves-effect">Yes</button>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                        </form>
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
    </form>

</div>
