@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fa-shopping-bag" btnText="Worker Details" btnIcon="fas fa-user" :btnRoute="route('workers.index')" :permissions="['workers']">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Details</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($workers as $key => $worker)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $worker->name }}</td>
                        <td>{{ $worker->options->country }}</td>
                        <td>
                            Passport: {{ $worker->options->passport_ex }} <br>
                            Visa: {{ $worker->options->visa_ex }}
                        </td>
                        <td>{{ $worker->price }} {{ $basic->currency }}</td>
                        <td>
                            <a href="{{ route('hire-remove', $worker->rowId) }}" class="btn btn-danger btn-xs"><i class="fas fa-times"></i> Remove</a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right">Total</td>
                    <td colspan="2">{{ $total }} {{ $basic->currency }}</td>
                </tr>
            </tbody>
        </table>
        @if ($workers->count())
            <div class="row mt-2">
                <div class="col-md-6">
                    <button class="btn btn-danger btn-block btn-lg" data-toggle="modal" data-target="#CancelModal"><i class="fas fa-trash"></i> Destroy this Bucket</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-block btn-lg" data-toggle="modal" data-target="#ConfirmModal"><i class="fas fa-paper-plane"></i> Checkout Now</button>
                </div>
            </div>
        @endif

    </x-button-layout>

    <div class="modal fade" id="ConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-danger" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger">Are you sure? You want to place this order.</h5>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('hire-confirm') }}" method="post">
                        {!! csrf_field() !!}
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Yes Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-danger" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger">Are you sure? You want to destroy this bucket.</h5>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('hire-destroy') }}" method="post">
                        {!! csrf_field() !!}
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Yes Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
