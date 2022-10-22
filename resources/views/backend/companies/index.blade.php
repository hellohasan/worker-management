@extends('layouts.backend')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-list" btnText="Add Company" btnIcon="fas fa-plus" :btnRoute="route('companies.create')" :permissions="['companies.create']">
        <table id="myTable" class="table table-bordered table-striped display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Company Name</th>
                    <th>Company Phone</th>
                    <th>Company Email</th>
                    <th>Owner Name</th>
                    <th>Register Number</th>
                    <th>Leave Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </x-button-layout>

    <x-delete-modal route="companies.destroy"></x-delete-modal>
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
                ajax: "{{ route('companies.index') }}",
                columns: [{
                        "data": 'DT_RowIndex',
                        "name": 'DT_RowIndex',
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": 'name',
                        "name": 'name',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "data": 'phone',
                        "name": 'phone',
                        "searchable": true,
                        "orderable": false,
                    },
                    {
                        "data": 'email',
                        "name": 'email',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "data": 'owner_name',
                        "name": 'owner_name',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "data": 'register_number',
                        "name": 'register_number',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "data": 'leave_number',
                        "name": 'leave_number',
                        "searchable": true,
                    },
                    {
                        "data": 'status',
                        "name": 'status',
                        "searchable": true,
                    },
                    {
                        "data": 'action',
                        "name": 'action',
                        "searchable": false,
                        "orderable": false,
                    }
                ]
            });
        });
    </script>
@endpush
