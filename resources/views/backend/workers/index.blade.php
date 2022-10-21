@extends('layouts.backend')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatable/css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-users" btnText="Add Worker" btnIcon="fas fa-plus" :btnRoute="route('workers.create')" :permissions="['workers.create']">
        <table id="myTable" class="table table-bordered table-striped display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Age</th>
                    <th>Passport</th>
                    <th>Visa</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </x-button-layout>

    <x-delete-modal route="workers.destroy"></x-delete-modal>
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
                ajax: "{{ route('workers.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'categoryName',
                        name: 'categoryName',
                        "searchable": false,
                        "orderable": true,
                    },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        data: 'details',
                        name: 'details',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        data: 'country',
                        name: 'country',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        data: 'age',
                        name: 'age',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        data: 'passport',
                        name: 'passport',
                        "searchable": true,
                    },
                    {
                        data: 'visa',
                        name: 'visa',
                        "searchable": true,
                    },
                    {
                        data: 'statusName',
                        name: 'statusName',
                        "searchable": false,
                        "orderable": true,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        "searchable": false,
                        "orderable": false,
                    }
                ]
            });
        });
    </script>
@endpush
