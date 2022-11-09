@extends('layouts.backend')
@section('content')
    <x-basic-layout :title="$page_title" icon="fas fas fa-eye">
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
            </tbody>
        </table>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hire Date</th>
                    <th>Company Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($worker->orders->reverse() as $key => $order)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $order->order->created_at->format('dS M,Y') }}</td>
                        <td>{{ $order->order->company->user->name }}</td>
                    </tr>
                @empty
                @endforelse

            </tbody>
        </table>
    </x-basic-layout>
@endsection
