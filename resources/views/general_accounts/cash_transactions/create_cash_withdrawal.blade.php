@extends('layouts.app')
@section('title', 'إنشاء سحب نقدي')

@section('content')
    <style>
        .invalid-feedback {
            color: red;
        }

        .is-invalid {
            border-color: red;
            padding-right: calc(1.5em + .75rem);
            background-position: right calc(.375em + .1875rem) center;
        }
    </style>

    <!-- Main content -->
    <section class="content no-print">
        <div id="response-message"></div> <!-- مكان لعرض رسالة النجاح -->

        <form id="cash_withdrawal_form" method="POST" action="{{ route('cash_withdrawals.store') }}">
            @csrf

            @component('components.widget', ['class' => 'box-solid'])
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="container mt-5">
                            <h1 class="mb-4 text-center">إنشاء سحب نقدي من البنك</h1>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">عدد سندات سحب نقدي</label>
                                    <input type="text" class="form-control" value="{{ max(1, $cashWithdrawalCount) }}"
                                        readonly>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="" class="form-label">تاريخ السند</label>
                                    <input type="text" id="" name="document_date"
                                        class="form-control @error('document_date') is-invalid @enderror datepicker"
                                       >
                                    @error('document_date')
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

                            <div class="row mb-3">
                                 @php 
                            $business=\App\Business::find(session('business.id'));
                            $banks =\DB::table('accounting_accounts')->where('business_id',$business->id)->where('detail_type_id',30)->pluck('name','id');

                            @endphp
                            <div class="row mb-3">
                                <div class="col-md-6 ">
                                        <label for="cash_drawer" class="form-label">البنك  </label>
                                        <select id="bank_name" name="bank_name"
                                            class="form-control  @error('bank_name') is-invalid @enderror">
                                            @foreach($banks as $id => $name )
                                                <option value="{{ $id }}" >{{$name}}</option>
                                            @endforeach                                        </select>
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
                                <button type="submit" class="btn btn-primary btn-custom">حفظ</button>
                            </div>
                        </>
                    </div>
                </div>
            @endcomponent
        </form>
    </section>
@stop

@section('javascript')
    <script src="{{ asset('js/cash_withdrawal.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datetimepicker({
                format:moment_date_format  ,
              
             });
             $('.datepicker').val(moment().format(moment_date_format));
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
                $('#account_name').val(selectedAccountName);
            });

            $('#cash_withdrawal_form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var actionUrl = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#response-message').html('<div class="alert alert-success">' +
                                response.message + '</div>').show();
                            setTimeout(function() {
                                $('#response-message').fadeOut();
                                reloadPage
                            (); // Reload the page after showing success message
                            }, 1000);
                        } else {
                            $('#response-message').html('<div class="alert alert-danger">' +
                                response.message + '</div>').show();
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<div class="alert alert-danger"><ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul></div>';
                        $('#response-message').html(errorHtml).show();
                    }
                });
            });
        });

        function reloadPage() {
            location.reload(); // Reloads the current page
        }
    </script>
@endsection
