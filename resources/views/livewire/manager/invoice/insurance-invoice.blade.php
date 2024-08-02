<div class="col-md-12">

    <div class="card">
        <form wire:submit='searchInformation'>
            @csrf
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date </label>
                            <input type="date" wire:model.blur='start_date'
                                class="@error('start_date') is-invalid @enderror form-control">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Date </label>
                            <input type="date" wire:model.blur='end_date'
                                class="@error('end_date') is-invalid @enderror form-control">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!--/span-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Insurance</label>
                            <select class="form-control @error('insurance') is-invalid @enderror custom-select"
                                wire:model.blur='insurance'>
                                <option>-- Select your Insurance --</option>
                                @foreach ($insurances as $insurance)
                                    <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}</option>
                                @endforeach
                            </select>
                            @error('insurance')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove wire:target="searchInformation">Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>

            </div>
        </form>
    </div>


    @if ($showData)

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs customtab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#detailed-invoices" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                Detailed Invoice
                                <span class="badge badge-danger badge-pill">
                                    {{ count($invoices) }}
                                </span>
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#detailed-invoices" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                Invoiced Invoice
                                <span class="badge badge-danger badge-pill">
                                    {{ count($invoices) }}
                                </span>
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#summarized-invoices" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                Summarized Invoices
                                {{-- <span class="badge badge-danger badge-pill">
                                    {{ count($invoices) }}
                                </span> --}}
                            </span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>


        <div class="tab-content">

            <div class="tab-pane active" id="detailed-invoices">
                <div class="card">
                    <div class="card-body">

                        {{-- <h4 class="card-title">{{ numberToWord(90007895) }}</h4> --}}
                        {{-- <hr> --}}
                        <button onclick="exportAll('xlsx');" class="ml-2 btn btn-sm waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                            <i class="fa fa-download"></i> Download
                        </button>
                        <hr>
                        <div class="table-responsive">
                            <form wire:submit="addInvoiceCredit">
                                <table id="zero_config" class="table table-striped table-bordered nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" onclick="checkUncheckrequestId(this)"/>
                                            </th>
                                            <th>S/N</th>
                                            <th>Date</th>
                                            <th>Ins. Number</th>
                                            <th>DoB</th>
                                            <th>Sex</th>
                                            <th>Beneficiary Names</th>
                                            <th>Affiliate Names</th>
                                            <th>T. Amnt</th>
                                            <th>Ins Amnt </th>
                                            {{-- <th>Credit </th> --}}
                                            <th>Tt. Credit </th>
                                            <th>Final Tt. </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $key => $invoice)
                                            <tr>
                                                <th>
                                                    @if ($invoice->hasbeeninvoiced()==null)
                                                        <input class="invoicesID" wire:model.live="invoicesIds.{{$key}}.chekboxId" type="checkbox" value="{{$invoice->id}}"/>
                                                    @endif
                                                </th>
                                                <th>{{ $key + 1 }}</th>
                                                <th>{{ date('Y-m-d', strtotime($invoice->created_at)) }}</th>
                                                <th>{{ $invoice->insurance_card_number }} </th>
                                                <th>{{ $invoice->dateOfBirth }}</th>
                                                <th>{{ Oneinitials($invoice->gender) }}</th>
                                                <th>{{ $invoice->client_name }}</th>
                                                <th class="text-center">{{ $invoice->affiliate_names ?? '-' }}</th>
                                                <th>{{ format_money($invoice->soldproduct_sum_total_amount) }}</th>
                                                <th>{{ format_money($invoice->soldproduct_sum_insurance_payment) }}
                                                </th>
                                                {{-- <th>
                                                    <input type="text" wire:model.live="invoiceCredit.{{$key}}.amount" id="">
                                                </th> --}}
                                                <th>
                                                    {{ format_money($invoice->soldproduct_sum_insurance_payment) }}
                                                </th>
                                                <th>{{ format_money($invoice->soldproduct_sum_insurance_payment) }}
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                                <button class="btn btn-primary mr-3" type="submit">
                                    Submit Invoice Credit(s)
                                </button>
                                <a href="#!" wire:click="createInvoiceSummary" class="btn btn-success">
                                    <span wire:loading.remove wire:target="createInvoiceSummary">Create Invoice</span>
                                    <span wire:loading wire:target="createInvoiceSummary">Creating Invoice....</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="summarized-invoices">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="zero_" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Original invoice</th>
                                        <th>Final Invoice </th>
                                        <th>Payment </th>
                                        <th>Total Payment </th>
                                        <th>Balance </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <hr>
                            <button class="btn btn-success">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

</div>

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>

    <script>
        function exportAll(type) {

            $('#zero_config').tableExport({
                filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
                format: type
            });
        }

        function checkUncheckrequestId(checkBox) {
            get = document.getElementsByClassName('invoicesID');
            for (var i = 0; i < get.length; i++) {
                get[i].checked = checkBox.checked;
            }
        }
    </script>
@endpush
