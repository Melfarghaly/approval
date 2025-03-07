@extends('layouts.app')
@section('title', 'إنشاء سند صرف نقدي')

@section('content')
    <style>
        .invalid-feedback {
            color: red;
            display: block;
            /* Ensure it appears below the input */
            margin-top: 0.25rem;
            /* Add some space between the input and the error message */
        }

        .is-invalid {
            border-color: red;
            padding-right: calc(1.5em + .75rem);
            background-position: right calc(.375em + .1875rem) center;
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
            /* Optional: add a shadow for visibility */
        }


        .alert-success {
            color: green;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #d4edda;
            border-radius: 5px;
            background-color: #d4edda;
        }
    </style>

    <section class="content no-print">
        <div id="response-message"></div>
        <form id="cash_disbursement_form" method="POST" action="{{ route('vouchers.store.cash_disbursement') }}">
            @csrf
            @component('components.widget', ['class' => 'box-solid'])
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="container mt-5">
                            <h1 class="mb-4 text-center">إنشاء سند صرف نقدي</h1>
                            <input type="hidden" name="voucher_type" value="cash_disbursement">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">عدد سندات الصرف</label>
                                    <input type="text" class="form-control" value="{{ max(1, $disbursedCashCount) }}"
                                        readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="voucher_date" class="form-label">تاريخ السند</label>
                                    <div class='input-group ' id=''>
                                    <input type="text" id="voucher_date" name="voucher_date"
                                        class="form-control datepicker @error('voucher_date') is-invalid @enderror"
                                        value="{{ old('voucher_date')  }}">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    @error('voucher_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 hide">
                                    <label for="currency" class="form-label">العملة</label>
                                    <input type="text" id="currency" name="currency"
                                        class="form-control @error('currency') is-invalid @enderror" placeholder="العملة"
                                        value="{{ old('currency') ?? 'الجنيه' }}">
                                    @error('currency')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="amount" class="form-label">المبلغ</label>
                                    <input type="number" step="0.01" id="amount" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror" placeholder="المبلغ"
                                        value="{{ old('amount') }}">
                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @php 
                            $business=\App\Business::find(session('business.id'));
                            $banks =\DB::table('accounting_accounts')->where('business_id',$business->id)->where('parent_account_id',$business->parent_bank_account_id)->pluck('name','id');

                            @endphp
                            <div class="row mb-3">
                            <div class="col-md-6 ">
                                    <label for="cash_drawer" class="form-label">الخزنة  </label>
                                    <select id="cash_drawer" name="cash_drawer"
                                        class="form-control accounts- @error('cash_drawer') is-invalid @enderror">
                                        @foreach($banks as $id => $name )
                                                <option value="{{ $id }}" >{{$name}}</option>
                                            @endforeach                                     
                                          </select>
                                    @error('cash_drawer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                       
                            </div>


                                <!-- Account Name -->
                                <div class="col-md-6 mb-2">
                                    <label for="account_id" class="form-label">اسم الحساب</label>
                                    <select id="account_id" name="account_id"
                                        class="form-control accounts-dropdown @error('account_id') is-invalid @enderror">
                                        <!-- Options will be dynamically loaded by Select2 -->
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 mb-2">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات"
                                        rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="container text-center m-5">
                                <button type="submit" class="btn btn-primary btn-custom m-5 " id="save-btn">حفظ</button>
                            </div>

                        </div>
                    </div>
                </div>
            @endcomponent
        </form>
    </section>
@stop

@section('javascript')
    <script src="{{ asset('js/cash_disbursement.js') }}"></script>
   
<script type="text/javascript">
       
$(document).ready(function() {
    $('.datepicker').datetimepicker({
                format: moment_date_format ,
                ignoreReadonly: true,
    });
    function removeHtmlTags(str) {
        return str.replace(/<\/?[^>]+>/gi, '');
    }

    // Initialize Select2
    $("select.accounts-dropdown").select2({
        ajax: {
            url: '/accounting/accounts-dropdown',
            dataType: 'json',
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.text
                        };
                    })
                };
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
        templateSelection: function(data) {
            return data.text;
        }
    }).on('select2:select', function(e) {
        var selectedAccountName = removeHtmlTags(e.params.data.text);
        //$('#account_name').val(selectedAccountName);
    });

    // Handle form submission
    $('#cash_disbursement_form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        function setVoucherNumber() {
            var actionUrl = form.attr('action').replace('store', 'generateUniqueVoucherNumber');
            $.ajax({
                url: actionUrl,
                method: 'GET',
                success: function(response) {
                    form.find('input[name="voucher_number"]').val(response.voucher_number);
                }
            });
        }

        function reloadPage() {
           // window.location.reload(); // Reload the current page
        }

        setVoucherNumber();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#response-message').html('<div class="alert-success">' + response.message + '</div>').show();
                    form[0].reset();
                    setTimeout(function() {
                        $('#response-message').fadeOut();
                        reloadPage(); // Reload the page after a successful submission
                    }, 1000);
                } else {
                    $('#response-message').html('<div class="alert-danger">' + response.message + '</div>').show();
                }
                $('#save-btn').removeAttr('disabled');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorHtml = '<div class="alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                $('#save-btn').removeAttr('disabled');
                errorHtml += '</ul></div>';
                $('#response-message').html(errorHtml).show();
            }
        });
    });
    

    // Display success message if available
    @if (session('success'))
        $("#response-message").html('<div class="alert-success">{{ session('success') }}</div>').show();
        setTimeout(function() {
            $("#response-message").fadeOut();
        }, 4000);
    @endif
});

    </script>
@endsection
