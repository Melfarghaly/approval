<style>
    .select2-container--default{
        width: 100% !important;
    }
</style>
<div class="pos-tab-content">
     <div class="row">

     <div class="col-sm-4">
            <div class="form-group">
                @php
                    $qoutation_counter =null;
                    if(!empty($business->approval_counter['qoutation'])){
                        $qoutation_counter = $business->approval_counter['qoutation'];
                    }
                @endphp
                {!! Form::label('approval_counter[qoutation]', __('lang_v1.qoutation') . ':') !!}
                {!! Form::select('approval_counter[qoutation][]',$users,  $qoutation_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $sale_order_counter =null;
                    if(!empty($business->approval_counter['sale_order'])){
                        $sale_order_counter = $business->approval_counter['sale_order'];
                    }
                @endphp
                {!! Form::label('approval_counter[sale_order]', __('lang_v1.sale_order') . ':') !!}
                {!! Form::select('approval_counter[sale_order][]',$users,  $sale_order_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $purchase_request_counter =null;
                    if(!empty($business->approval_counter['purchase_request'])){
                        $purchase_request_counter = $business->approval_counter['purchase_request'];
                    }
                @endphp
                {!! Form::label('approval_counter[purchase_request]', __('lang_v1.purchase_request') . ':') !!}
                {!! Form::select('approval_counter[purchase_request][]',$users,  $purchase_request_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $purchase_order_counter =null;
                    if(!empty($business->approval_counter['purchase_order'])){
                        $purchase_order_counter = $business->approval_counter['purchase_order'];
                    }
                @endphp
                {!! Form::label('approval_counter[purchase_order]', __('lang_v1.purchase_order') . ':') !!}
                {!! Form::select('approval_counter[purchase_order][]',$users, $purchase_order_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>


        
        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $purchase_permission_counter =null;
                    if(!empty($business->approval_counter['purchase_permission'])){
                        $purchase_permission_counter = $business->approval_counter['purchase_permission'];
                    }
                @endphp
                {!! Form::label('approval_counter[purchase_permission]', __('lang_v1.purchase_permission') . ':') !!}
                {!! Form::select('approval_counter[purchase_permission][]',$users, $purchase_permission_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $sell_permission_counter =null;
                    if(!empty($business->approval_counter['sell_permission'])){
                        $sell_permission_counter = $business->approval_counter['sell_permission'];
                    }
                @endphp
                {!! Form::label('approval_counter[sell_permission]', __('lang_v1.sell_permission') . ':') !!}
                {!! Form::select('approval_counter[sell_permission][]',$users,  $sell_permission_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>


        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $purchase_counter =null;
                    if(!empty($business->approval_counter['purchase'])){
                        $purchase_counter = $business->approval_counter['purchase'];
                    }
                @endphp
                {!! Form::label('approval_counter[purchase]', __('lang_v1.purchase') . ':') !!}
                {!! Form::select('approval_counter[purchase][]',$users, $purchase_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                @php
                    $sell_counter =null;
                    if(!empty($business->approval_counter['sell'])){
                        $sell_counter = $business->approval_counter['sell'];
                    }
                @endphp
                {!! Form::label('approval_counter[sell]', __('lang_v1.sell') . ':') !!}
                {!! Form::select('approval_counter[sell][]',$users, $sell_counter, ['class' => 'form-control select2' ,'multiple' , ]); !!}
            </div>
        </div>
    </div>
</div>