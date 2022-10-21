@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-edit" btnText="Worker List" btnIcon="fas fa-users" :btnRoute="route('workers.index')" :permissions="['workers']">
        <x-form-post :action="route('workers.update', $worker->id)" :enctype="true" :isPut="true">
            <div class="form-row">
                <x-form-group-select col="col-md-6" label="Worker Country" :options="$countries" name="country" :value="$worker->country"></x-form-group-select>
                <x-form-group-select col="col-md-6" label="Worker Category" :options="$categories" name="category_id" :value="$worker->category_id"></x-form-group-select>

                <x-form-group-input col="col-md-6" label="Worker Name" name="name" :value="$worker->user->name"></x-form-group-input>
                <x-form-group-input type="date" col="col-md-6" label="Worker DOB" name="dob" :value="$worker->dob"></x-form-group-input>

                <div class="from-group col-md-12">
                    <div class="form-group">
                        <label for="photo">Previous Photo:</label> <br>
                        <img src="{{ $worker->user->avatar }}" alt="">
                    </div>
                </div>
                <x-form-group-photo col="col-md-6" label="Worker Photo" name="photo" message="Image will resize: (100X100)px"></x-form-group-photo>
                <x-form-group-input col="col-md-6" label="Worker Address" name="address" :value="$worker->address"></x-form-group-input>

                <x-form-group-input-group type="email" col="col-md-6" label="Worker Email" name="email" groupText="fas fa-at" :value="$worker->user->email" :required="false"></x-form-group-input-group>
                <x-form-group-input-group type="number" col="col-md-6" label="Worker Phone" name="phone" groupText="fas fa-phone" :value="$worker->user->phone" :required="false"></x-form-group-input-group>

                <x-form-group-input-group type="text" col="col-md-6" label="Passport Number" name="passport" groupText="fas fa-passport" :value="$worker->passport"></x-form-group-input-group>
                <x-form-group-input-group type="date" col="col-md-6" label="Passport Expire Date" name="passport_ex" groupText="fas far fa-calendar-alt" :value="$worker->passport_ex"></x-form-group-input-group>

                <x-form-group-input-group type="text" col="col-md-6" label="Visa Number" name="visa" groupText="fab fa-cc-visa" :value="$worker->visa"></x-form-group-input-group>
                <x-form-group-input-group type="date" col="col-md-6" label="Visa Expire Date" name="visa_ex" groupText="fas far fa-calendar-alt" :value="$worker->visa_ex"></x-form-group-input-group>
                <x-form-group-input-group type="number" col="col-md-6" label="Hire Charge" name="charge" :groupText="$basic->currency" :groupIcon="false" :value="$worker->charge"></x-form-group-input-group>
                <x-form-group-select col="col-md-6" label="Status" :options="$status" name="status_id" :value="$worker->status_id"></x-form-group-select>
                <x-form-group-button col="col-md-12" icon="fas fas fa-paper-plane" btnText="Update Worker"></x-form-group-button>
            </div>
        </x-form-post>
    </x-button-layout>
@endsection
