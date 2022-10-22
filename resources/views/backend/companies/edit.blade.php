@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fa-edit" btnText="Company List" btnIcon="fas fa-list" :btnRoute="route('companies.index')" :permissions="['companies']">
        <x-form-post :action="route('companies.update', $company->id)" :enctype="true" :isPut="true">
            <div class="form-row">

                <x-form-group-input col="col-md-6" label="Company Name" name="name" :value="$company->user->name"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Company Owner Name" name="owner_name" :value="$company->owner_name"></x-form-group-input>

                <x-form-group-input-group type="email" col="col-md-6" label="Company Email" name="email" groupText="fas fa-at" :value="$company->user->email" :required="false"></x-form-group-input-group>
                <x-form-group-input-group type="number" col="col-md-6" label="Company Phone" name="phone" groupText="fas fa-phone" :value="$company->user->phone" :required="false"></x-form-group-input-group>

                <x-form-group-input col="col-md-6" label="Company Register Number" name="register_number" :value="$company->register_number"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Company Leave Number" name="leave_number" :value="$company->leave_number"></x-form-group-input>

                <x-form-group-input col="col-md-6" label="Company Address" name="address" :value="$company->address"></x-form-group-input>
                <x-form-group-toggle col="col-md-6" label="Company Status" name="status" :value="$company->status"></x-form-group-toggle>

                <x-form-group-input col="col-md-6" label="Company Login Password" name="password" value="" :required="false"></x-form-group-input>
                <x-form-group-input col="col-md-6" label="Password Confirmation" name="password_confirmation" value="" :required="false"></x-form-group-input>

                <x-form-group-button col="col-md-12" icon="far fa-paper-plane" btnText="Update Company"></x-form-group-button>
            </div>
        </x-form-post>
    </x-button-layout>
@endsection
