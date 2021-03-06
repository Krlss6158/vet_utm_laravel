@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@can('dashboard.furs.create')
<link rel="stylesheet" href="{{asset('css/flowbite.min.css')}}">
@endcan

@endpush

@section('content_header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:space-y-0 space-y-2">
    <div class="text-lg font-bold">{{ __('Editing data specie') }}</div>
    <div class="flex flex-col sm:space-x-2 sm:flex-row sm:items-center items-start sm:space-y-0 space-y-1">

        @can('dashboard.species.index')
        <a href="{{ route('dashboard.species.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md font-semibold px-4 ">
            {{ __('Species list') }}
        </a>
        @endcan

        @can('dashboard.species.create')
        <a href="{{ route('dashboard.species.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-md font-semibold px-4 ">
            {{ __('Add species') }}
        </a>
        @endcan


    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <x-flash-messages />

        {!! Form::model($specie, ['route' => ['dashboard.species.update', $specie], 'autocomplete' => 'off', 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
        @include('dashboard.species.fields')
        {!! Form::close() !!}

        @include('dashboard.furs.modal')
    </div>
</div>

@endsection