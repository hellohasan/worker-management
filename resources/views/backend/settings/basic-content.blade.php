@extends('layouts.backend')

@section('content')
    <x-basic-layout :title="$page_title" icon="fas fa-cog">
        <x-form-post :action="route('update-basic-content')">
            <div class="form-row">
                <x-form-group-input col="col-md-6" name="title" label="Website Title" :value="$basic->title" />
                <div class="form-group col-md-6">
                    <label for="timezone">Web Timezone</label>
                    {!! Timezones::create('timezone', env('APP_TIMEZONE'), ['attr' => ['id' => 'timezone', 'class' => 'form-control select2']]) !!}
                </div>
            </div>
            <div class="form-row">
                <x-form-group-input col="col-md-4" name="phone" label="Phone Number" :value="$basic->phone" />
                <x-form-group-input col="col-md-4" name="email" label="Email Address" type="email" :value="$basic->email" />
                <x-form-group-textarea col="col-md-4" name="address" label="Address" :value="$basic->address" rows="1" />
            </div>
            <div class="form-row">
                <x-form-group-toggle col="col-md-4" name="email_verification" label="Email Verification" :value="$basic->email_verification" />
                <x-form-group-input col="col-md-4" name="currency" label="Web Currency" :value="$basic->currency" />
                <x-form-group-input col="col-md-4" name="symbol" label="Currency Symbol" :value="$basic->symbol" />
            </div>
            <x-form-group-button btnText="Update Basic Setting"></x-form-group-button>
        </x-form-post>
    </x-basic-layout>
@endsection
@push('scripts')
    <script src="{{ asset('backend/js/form-group-select.js') }}"></script>
@endpush
