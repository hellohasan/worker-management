@extends('layouts.backend')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
    <x-basic-layout :title="$page_title" icon="fas fa-shopping-bag">

        <table id="myTable" class="table table-bordered table-striped display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Company Info</th>
                    <th>Company Contact</th>
                    <th>Order Number</th>
                    <th>Total Worker</th>
                    <th>Total Amount</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            {{--  <tbody>
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
                            <a href="{{ route('hire-details', $order->custom) }}" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> View</a>
                        </td>
                    </tr>
                @empty
                @endforelse

            </tbody>  --}}
        </table>

    </x-basic-layout>
@endsection
@push('scripts')
    <script src="{{ asset('backend/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function() {
            $("#myTable").DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 75, 100, -1],
                    [20, 50, 75, 100, "All"]
                ],
                ajax: "{{ route('hire.history') }}",
                columns: [{
                        "data": 'DT_RowIndex',
                        "name": 'DT_RowIndex',
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": 'company_info',
                        'name': 'company_info',
                        'orderable': false,
                        'search': true,
                    },
                    {
                        "data": 'company_contact',
                        'name': 'company_contact',
                        'orderable': false,
                        'search': true,
                    },
                    {
                        "data": 'custom',
                        'name': 'custom',
                        'orderable': false,
                        'search': true,
                    },
                    {
                        "data": 'total_worker',
                        'name': 'total_worker',
                        'orderable': false,
                        'search': false,
                    },
                    {
                        "data": 'total',
                        'name': 'total',
                        'orderable': false,
                        'search': false,
                    },
                    {
                        "data": 'payment_at',
                        'name': 'payment_at',
                        'orderable': false,
                        'search': false,
                    },
                    {
                        "data": 'status',
                        'name': 'status',
                        'orderable': false,
                        'search': false,
                    },
                    {
                        "data": 'action',
                        'name': 'action',
                        'orderable': false,
                        'search': false,
                    }
                ]
            })
        })
    </script>
@endpush
