<div>
    <h6 class="text-gray-400 text-sm my-3 font-bold uppercase">
        {{ __('Pet data') }}
    </h6>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">

        {!! Form::hidden('pet_id', old('pet_id'), ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'placeholder' => trans('lang.tableUserID'), 'maxlength' => 13, 'required' => true]) !!}

        <div class="flex flex-col px-2">
            {!! Form::label('name', __('Name') . '*', ['class' => '']) !!}
            {!! Form::text('name', old('name'), ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'placeholder' => __('Name'), 'required' => true]) !!}
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- specie pet -->
        <div class="flex flex-col px-2 md:mb-0 mb-2">
            {!! Form::label('id_specie', __('Species') . '*', ['class' => '']) !!}
            <div class="flex items-center justify-between w-full gap-2">
                {!! Form::select('id_specie', $species, $pet->id_specie ?? null, ['class' => 'select2 form-control', 'placeholder' => __('Select a specie'), 'required' => true]) !!}

                @can('dashboard.species.create')
                <x-button-modal target="specie" />
                @endcan

            </div>

            @error('id_specie')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- race of pet -->
        <div class="flex flex-col px-2 md:mb-0 mb-2">
            {!! Form::label('id_race', __('Race') . '*', ['class' => '']) !!}
            <div class="flex items-center justify-between w-full gap-2">
                {!! Form::select('id_race', $races, $pet->id_race ?? null, ['class' => 'select2 form-control', 'placeholder' => $pet->id_specie ? __('Select a specie') : __('First select a specie'), 'required' => true]) !!}

                @can('dashboard.races.create')
                <x-button-modal target="race" />
                @endcan

            </div>

            @error('id_race')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


    </div>

    {{-- --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">

        {{-- Sex --}}
        <div class="flex flex-col px-2 mt-2">
            {!! Form::label('sex', __('Sex') . '*', ['class' => '']) !!}
            {!! Form::select('sex', ['M' => __('Male pet'), 'F' => __('Female pet')], $pet->sex, ['class' => 'select2 form-control', 'placeholder' => __('Select a sex'), 'required' => true]) !!}
            @error('sex')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col px-2 mt-2">
            <!-- Birth pet -->
            {!! Form::label('birth', __('Birth date') . '*', ['class' => '']) !!}
            {!! Form::date('birth', old('birth'), ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'max' => date('Y-m-d'), 'required' => true]) !!}
            @error('birth')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

    </div>

    <h6 class="text-gray-400 text-sm my-3 font-bold uppercase">
        {!! __('Pet information not required') !!}
    </h6>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3">

        <div class="flex flex-col px-2 md:mb-0 mb-2 col-span-2">
            {!! Form::label('id_fur', __('Fur'), ['class' => '']) !!}
            <div class="flex items-center justify-between w-full gap-2">
                {!! Form::select('id_fur', $furs, $pet->id_fur, ['class' => 'select2 form-control', 'placeholder' => $pet->id_specie ? __('Select a fur') : __('First select a specie')]) !!}

                @can('dashboard.furs.create')
                <x-button-modal target="fur" />
                @endcan

            </div>

            @error('id_fur')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3">
        <div class="flex flex-col px-2 md:mb-0 mb-2 col-span-2">
            {!! Form::label('characteristic', __('Characteristic'), ['class' => '']) !!}
            {!! Form::textarea('characteristic', old('characteristic'), ['class' => 'form-control border-1 border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-transparent rounded-sm', 'placeholder' => __('Characteristic'), 'rows' => 2]) !!}
            @error('characteristic')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">

        {{-- Castrado --}}
        <div class="flex flex-col px-2">
            <div class="form-group">
                <p class="font-weight-bold">{{ __('Castrated') }}</p>
                <label>
                    {!! Form::radio('castrated', 0, $pet->castrated ?? false) !!}
                    {{ __('No') }}
                </label>
                <label>
                    {!! Form::radio('castrated', 1, $pet->castrated ?? false) !!}
                    {{ __('Yes') }}
                </label>
                @error('castrated')
                <br>
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>
        {{-- Perdido --}}
        <div class="flex flex-col px-2">
            <div class="form-group">
                <p class="font-weight-bold">{{ __('Lost') }}</p>
                <label>
                    {!! Form::radio('lost', 0, $pet->lost ?? false) !!}
                    {{ __('No') }}
                </label>
                <label>
                    {!! Form::radio('lost', 1, $pet->lost ?? false) !!}
                    {{ __('Yes') }}
                </label>
                @error('lost')
                <br>
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>

        {{-- Publicado --}}
        <div class="flex flex-col px-2">

            <div class="form-group">
                <p class="font-weight-bold">{{ __('Published') }}</p>
                <label>
                    {!! Form::radio('published', 0, $pet->published ?? false) !!}
                    {{ __('No') }}
                </label>
                <label>
                    {!! Form::radio('published', 1, $pet->published ?? false) !!}
                    {{ __('Yes') }}
                </label>
                @error('published')
                <br>
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>



        </div>


    </div>

    <div class="grid grid-cols-1  mb-4">
        <div class="flex flex-col col-span-2 px-2">
            {{-- photos pet --}}
            <div x-data="{ open: true }">
                <div class="flex items-start">
                    <div>
                        {!! Form::label('photo_pet', __('Pet photos'), ['class' => '']) !!}
                    </div>
                    <div class="ml-2 cursor-pointer">
                        <button @click="open=!open" type="button">
                            <div x-show="!open"><i class="fa fa-angle-down text-xs"></i></div>
                            <div x-show="open"><i class="fa fa-angle-left text-xs"></i></div>
                        </button>
                    </div>
                </div>
                <div x-show="open">
                    @livewire('images-edit', ['currentFiles' => $images_])
                    <livewire:scripts />
                </div>
            </div>
        </div>
    </div>

    <!-- 8 row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 sm:space-y-0 space-y-2 mb-3">
        <!-- owner -->
        <div class="flex flex-col px-2 md:mb-0 mb-2">
            {!! Form::label('user_id', __('Owner'), ['class' => '']) !!}
            <div class="flex items-center justify-between w-full gap-2">
                {!! Form::select('user_id', $users, $pet->user_id, ['placeholder' => '']) !!}

                @can('dashboard.users.create')
                <x-button-new-tab-blank target="user" />
                @endcan

            </div>

            @error('user_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <x-submit-button-default />

</div>

@push('js')
<script src="{{ asset('js/alpine.min.js') }}"></script>
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{ asset('js/flowbite.js') }}"></script>
@include('partials.js_select2.owner')

@include('partials.js_select.changeSpecie')

@include('partials.js_modals.fur')
@include('partials.js_modals.race')
@include('partials.js_modals.specie')
@endpush