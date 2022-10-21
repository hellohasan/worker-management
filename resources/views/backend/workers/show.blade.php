@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-eye" btnText="Worker Details" btnIcon="fas fa-user" :btnRoute="route('workers.index')" :permissions="['workers']">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="30%" class="text-right">Title</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right">Worker Name</td>
                    <td>{{ $worker->user->name }}</td>
                </tr>
                <tr>
                    <td class="text-right">Worker Email</td>
                    <td>{{ $worker->user->email }}</td>
                </tr>
                <tr>
                    <td class="text-right">Worker Phone</td>
                    <td>{{ $worker->user->phone }}</td>
                </tr>
                <tr>
                    <td class="text-right">Worker Image</td>
                    <td>
                        <img src="{{ $worker->user->avatar }}" alt="">
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Worker Date of Birth</td>
                    <td>
                        {{ $worker->dob }} <br>
                        {{ $age }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Worker Passport</td>
                    <td>
                        {{ $worker->passport }} <br>
                        {{ $passport_expire }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Worker Visa</td>
                    <td>
                        {{ $worker->visa }} <br>
                        {{ $visa_expire }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Worker Charge</td>
                    <td>
                        {{ $worker->charge }} {{ $basic->currency }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Worker Status</td>
                    <td>{{ $worker->status->name }}</td>
                </tr>
                <tr>
                    <td class="text-right">Action</td>
                    <td>
                        @can('workers-edit')
                            <a class="btn btn-success bold uppercase btn-xs" href="{{ route('workers.edit', $worker->id) }}"><i class="far fa-edit"></i> Edit</a>
                        @endcan
                        @can('workers-destroy')
                            <button class="btn btn-danger btn-xs bold uppercase delete_button" data-toggle="modal" data-target="#DeleteModal" data-id="{{ $worker->id }}" type="button"><i class="fa fa-trash"></i> Delete</button>
                        @endcan
                    </td>
                </tr>
            </tbody>
        </table>
    </x-button-layout>
    <x-delete-modal route="workers.destroy"></x-delete-modal>
@endsection
