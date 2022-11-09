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
                    <div class="row">
                        @foreach ($workers as $key => $worker)
                            <div class="col-md-2">
                                <div class="card">
                                    <img class="card-img-top" src="{{ $worker->user->avatar }}" alt="Card image cap">
                                    <div class="card-header d-flex justify-content-between bd-highlight">
                                        <h5 class="card-title">{{ $worker->user->name }}</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>Country:</div>
                                            <div>{{ $worker->country }}</div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>Age:</div>
                                            <div>{{ $worker->getShortAge() }}</div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>Passport:</div>
                                            <div>{{ $worker->getPassportStatus() }}</div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>Visa:</div>
                                            <div>{{ $worker->getVisaStatus() }}</div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>Charge:</div>
                                            <div>{{ $basic->symbol }}{{ $worker->charge }}</div>
                                        </li>
                                    </ul>
                                    <div class="card-footer">
                                        <div class="btn-group btn-group-sm d-flex justify-content-center" role="group" aria-label="Basic example">
                                            <a href="{{ route('employee-details', $worker->custom) }}" class="btn btn-primary btn-mini" title="View"><i class="fa fa-eye"></i> Details</a>
                                            <button class="btn btn-success cart_button" data-toggle="modal" data-target="#CartModal" data-id="{{ $worker->id }}" type="button"><i class="fa fa-shopping-bag"></i> Add to Bucket</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-12">
                            {!! $workers->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
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
