@extends('layouts.app')
@section('title', __('lang_v1.purchase_order'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.purchase_order')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('po_list_filter_location_id', __('purchase.business_location') . ':') !!}
                    {!! Form::select('po_list_filter_location_id', $business_locations, null, [
                        'class' => 'form-control select2',
                        'style' => 'width:100%',
                        'placeholder' => __('lang_v1.all'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('po_list_filter_supplier_id', __('purchase.supplier') . ':') !!}
                    {!! Form::select('po_list_filter_supplier_id', $suppliers, null, [
                        'class' => 'form-control select2',
                        'style' => 'width:100%',
                        'placeholder' => __('lang_v1.all'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('po_list_filter_status', __('sale.status') . ':') !!}
                    {!! Form::select('po_list_filter_status', $purchaseOrderStatuses, null, [
                        'class' => 'form-control select2',
                        'style' => 'width:100%',
                        'placeholder' => __('lang_v1.all'),
                    ]) !!}
                </div>
            </div>
            @if (!empty($shipping_statuses))
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('shipping_status', __('lang_v1.shipping_status') . ':') !!}
                        {!! Form::select('shipping_status', $shipping_statuses, null, [
                            'class' => 'form-control select2',
                            'style' => 'width:100%',
                            'placeholder' => __('lang_v1.all'),
                        ]) !!}
                    </div>
                </div>
            @endif
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('po_list_filter_date_range', __('report.date_range') . ':') !!}
                    {!! Form::text('po_list_filter_date_range', null, [
                        'placeholder' => __('lang_v1.select_a_date_range'),
                        'class' => 'form-control',
                        'readonly',
                    ]) !!}
                </div>
            </div>
        @endcomponent
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_purchase_orders')])
            @can('purchase_order.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right"
                            href="{{ action([\App\Http\Controllers\PurchaseOrderController::class, 'create']) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg> @lang('messages.add')
                        </a>
                    </div>
                @endslot
            @endcan

            <table class="table table-bordered table-striped ajax_view" id="purchase_order_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('messages.date')</th>
                        <th>@lang('purchase.ref_no')</th>
                        <th>@lang('purchase.location')</th>
                        <th>@lang('purchase.supplier')</th>
                        <th>@lang('sale.status')</th>
                        <th>@lang('lang_v1.quantity_remaining')</th>
                        <th>@lang('lang_v1.shipping_status')</th>
                        <th>@lang('lang_v1.UserConfirmed')</th>
                        <th>@lang('lang_v1.userWhoConfirmed')</th>
                        <th>@lang('lang_v1.added_by')</th>
                    </tr>
                </thead>
            </table>
        @endcomponent
        <div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
    </section>
    <!-- /.content -->
@stop
@section('javascript')
    @includeIf('purchase_order.common_js')
    <script type="text/javascript">
        $(document).ready(function() {
            //Purchase table
            purchase_order_table = $('#purchase_order_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [1, 'desc']
                ],
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                fixedHeader:false,
                ajax: {
                    url: '{{ action([\App\Http\Controllers\PurchaseOrderController::class, 'index']) }}',
                    data: function(d) {
                        if ($('#po_list_filter_location_id').length) {
                            d.location_id = $('#po_list_filter_location_id').val();
                        }
                        if ($('#po_list_filter_supplier_id').length) {
                            d.supplier_id = $('#po_list_filter_supplier_id').val();
                        }
                        if ($('#po_list_filter_status').length) {
                            d.status = $('#po_list_filter_status').val();
                        }
                        if ($('#shipping_status').length) {
                            d.shipping_status = $('#shipping_status').val();
                        }

                        var start = '';
                        var end = '';
                        if ($('#po_list_filter_date_range').val()) {
                            start = $('input#po_list_filter_date_range')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            end = $('input#po_list_filter_date_range')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                        }
                        d.start_date = start;
                        d.end_date = end;

                        d = __datatable_ajax_callback(d);
                    },
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'ref_no',
                        name: 'ref_no'
                    },
                    {
                        data: 'location_name',
                        name: 'BS.name'
                    },
                    {
                        data: 'name',
                        name: 'contacts.name'
                    },
                    {
                        data: 'status',
                        name: 'transactions.status'
                    },
                    {
                        data: 'po_qty_remaining',
                        name: 'po_qty_remaining',
                        "searchable": false
                    },
                    {
                        data: 'shipping_status',
                        name: 'transactions.shipping_status'
                    },
                    { data: 'confirmedUsers', name: 'confirmedUsers', 'searchable': false, 'sortable':false},
                    { data: 'userWhoConfirmed', name: 'userWhoConfirmed', 'searchable': false, 'sortable':false},
                    {
                        data: 'added_by',
                        name: 'u.first_name'
                    }
                ]
            });

            $(document).on(
                'change',
                '#po_list_filter_location_id, #po_list_filter_supplier_id, #po_list_filter_status, #shipping_status',
                function() {
                    purchase_order_table.ajax.reload();
                }
            );

            $('#po_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#po_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                        moment_date_format));
                    purchase_order_table.ajax.reload();
                }
            );
            $('#po_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#po_list_filter_date_range').val('');
                purchase_order_table.ajax.reload();
            });

            $(document).on('click', 'a.delete-purchase-order', function(e) {
                e.preventDefault();
                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).attr('href');
                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    purchase_order_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
