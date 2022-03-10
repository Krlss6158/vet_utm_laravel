<!-- Name Field -->
<div class="form-group col-6">
    {!! Form::label('name', trans('lang.name'), ['class' => 'uppercase text-xs font-bold mb-2']) !!}
    <div class="">
        {!! Form::text('name', $role ? $role->name : null, ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'placeholder' => trans('lang.name'), 'required' => true]) !!}
        <div class="text-gray-500 text-sm mt-2">
            {{ trans('lang.required') }}
        </div>
    </div>
</div>

<!-- Guard Name Field -->
<!-- <div class="form-group col-6">
    {!! Form::label('guard_name', trans('lang.guard_name'), ['class' => 'uppercase text-xs font-bold mb-2']) !!}
    <div class="">
        {!! Form::text('guard_name', $role ? $role->guard_name : 'web', ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'placeholder' => trans('lang.guard_name'), 'required' => true]) !!}
        <div class="text-gray-500 text-sm mb-2">
            {{ trans('lang.required') }}
        </div>
    </div>
</div> -->

<!-- Submit Field -->
<div class="form-group col-sm-12 text-right">
<button type="submit" class="float-right bg-green-500 hover:bg-green-600 p-2 px-4 mt-2 rounded-md text-whire font-medium text-white">{{trans('lang.save')}}</button>
</div>