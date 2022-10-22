@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-plus" btnText="Company List" btnIcon="far fa-building" :btnRoute="route('companies.index')" :permissions="['companies']">
        <x-form-post :action="route('companies.store')" :enctype="true">
            <div class="form-row">

                <x-form-group-input col="col-md-6" label="Company Name" name="name" :value="old('name')"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Company Owner Name" name="owner_name" :value="old('owner_name')"></x-form-group-input>

                <x-form-group-input-group type="email" col="col-md-6" label="Company Email" name="email" groupText="fas fa-at" :value="old('email')" :required="false"></x-form-group-input-group>
                <x-form-group-input-group type="number" col="col-md-6" label="Company Phone" name="phone" groupText="fas fa-phone" :value="old('phone')" :required="false"></x-form-group-input-group>

                <x-form-group-input col="col-md-6" label="Company Register Number" name="register_number" :value="old('register_number')"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Company Leave Number" name="leave_number" :value="old('leave_number')"></x-form-group-input>

                <x-form-group-input col="col-md-6" label="Company Address" name="address" :value="old('address')"></x-form-group-input>
                <x-form-group-toggle col="col-md-6" label="Company Status" name="status" :value="old('status')"></x-form-group-toggle>

                <x-form-group-input col="col-md-6" label="Company Login Password" name="password" :value="old('password')"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Password Confirmation" name="password_confirmation" :value="old('password_confirmation')"></x-form-group-input>

                <x-form-group-button col="col-md-12" icon="fas fa-plus" btnText="Create Company"></x-form-group-button>
            </div>
        </x-form-post>
    </x-button-layout>
@endsection
