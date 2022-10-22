@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fa-shopping-bag" btnText="Worker Details" btnIcon="fas fa-user" :btnRoute="route('workers.index')" :permissions="['workers']">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    @unlessrole('Company|Worker')
                        <th>Company</th>
                    @endunlessrole
                    <th>Order Number</th>
                    <th>Total Worker</th>
                    <th>Total Amount</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $key => $order)
                    <tr>
                        <td>{{ $key + $orders->firstItem() }}</td>
                        @unlessrole('Company|Worker')
                            <td>
                                {{ $order->company->user->name }} <br>
                                {{ $order->company->user->email }} <br>
                                {{ $order->company->user->phone }} <br>
                            </td>
                        @endunlessrole
                        <td>{{ $order->custom }}</td>
                        <td>{{ $order->workers_count }} Persons</td>
                        <td>{{ $order->total }} {{ $basic->currency }}</td>
                        <td>
                            @if ($order->payment_at)
                                <span class="badge badge-success">Completed</span> <br>
                                {{ $order->payment_at->format('dS M, Y h:i A') }}
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if ($order->status == 1)
                                <span class="badge badge-success">Completed</span>
                            @elseif($order->status == 2)
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> View</a>
                        </td>
                    </tr>
                @empty
                @endforelse

            </tbody>
        </table>

    </x-button-layout>
@endsection
