@extends('layouts.backend')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> {{ $page_title }}</h3>
                    <div class="card-tools">
                        <a href="worker-cart" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i> Your Bucket <span class="badge badge-light">{{ \Cart::count() }}</span>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
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
                        <tbody>
                            @foreach ($workers as $key => $worker)
                                <tr>
                                    <td>{{ $key + $workers->firstItem() }}</td>
                                    <td>{{ $worker->category->name }}</td>
                                    <td><img src="{{ $worker->user->avatar }}" alt=""></td>
                                    <td>
                                        {{ $worker->user->name }} <br>
                                        {{ $worker->user->email }} <br>
                                        {{ $worker->user->phone }} <br>
                                    </td>
                                    <td>{{ $worker->country }}</td>
                                    <td>{{ \Carbon\Carbon::parse($worker->dob)->diff(\Carbon\Carbon::now())->format('%y Years %m Month %d Days') }}</td>
                                    <td>
                                        @if (\Carbon\Carbon::parse($worker->passport_ex)->isPast())
                                            Expired
                                        @else
                                            {{ \Carbon\Carbon::parse($worker->passport_ex)->diff(\Carbon\Carbon::now())->format('%y Years %m Month %d Days') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (\Carbon\Carbon::parse($worker->visa_ex)->isPast())
                                            Expired
                                        @else
                                            {{ \Carbon\Carbon::parse($worker->visa_ex)->diff(\Carbon\Carbon::now())->format('%y Years %m Month %d Days') }}
                                        @endif
                                    </td>
                                    <td>{{ $worker->status->name }}</td>
                                    <td>
                                        <button class="btn btn-success cart_button" data-toggle="modal" data-target="#CartModal" data-id="{{ $worker->id }}" type="button"><i class="fa fa-shopping-bag"></i> Add to Bucket</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="CartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-danger" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger">Are you sure? You want to add this worker to bucket.</h5>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('hire-cart') }}" method="post" id="cartForm">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" class="workerId" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Yes Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@pushOnce('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", '.cart_button', function(e) {
                var id = $(this).data('id');
                $(".workerId").val(id);
            });
        });
    </script>
@endpushOnce
