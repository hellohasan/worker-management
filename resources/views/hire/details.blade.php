@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fa-shopping-bag" btnText="Order History" btnIcon="fas fa-list" :btnRoute="route('hire.history')" :permissions="['hire-history']">

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Order Number</td>
                            <td>{{ $order->custom }}</td>
                        </tr>
                        <tr>
                            <td>Company Name</td>
                            <td>{{ $order->company->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Company Phone</td>
                            <td>{{ $order->company->user->phone }}</td>
                        </tr>
                        <tr>
                            <td>Company Email</td>
                            <td>{{ $order->company->user->email }}</td>
                        </tr>
                        <tr>
                            <td>Company Owner</td>
                            <td>{{ $order->company->owner_name }}</td>
                        </tr>
                        <tr>
                            <td>Company Address</td>
                            <td>{{ $order->company->address }}</td>
                        </tr>
                        <tr>
                            <td>Order Total</td>
                            <td>{{ $order->total }} {{ $basic->currency }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered table-active table-striped">
                    <thead>
                        <th>Sl</th>
                        <th>Category</th>
                        <th>Country</th>
                        <th>Image</th>
                        <th>Details</th>
                        <th>Passport</th>
                        <th>Visa</th>
                        <th>Charge</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @forelse ($order->workers as $key => $w)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $w->worker->category->name }}</td>
                                <td>{{ $w->worker->country }}</td>
                                <td>
                                    <img src="{{ $w->worker->user->avatar }}" alt="">
                                </td>
                                <td>
                                    {{ $w->worker->user->name }} <br>
                                    {{ $w->worker->user->email }} <br>
                                    {{ $w->worker->user->phone }}
                                </td>
                                <td>
                                    {{ $w->worker->passport }} <br>
                                    {{ $w->worker->getPassportStatus() }} <br>
                                </td>
                                <td>
                                    {{ $w->worker->visa }} <br>
                                    {{ $w->worker->getVisaStatus() }} <br>
                                </td>
                                <td>
                                    {{ $basic->symbol }}{{ $w->worker->charge }}
                                </td>
                                <td>
                                    {{ $w->worker->status->name }}
                                </td>

                                <td>
                                    @if ($order->status == 0)
                                        <x-delete-button :id="$w->id" text="Remove" icon="fas fa-times" btnClass="btn-warning"></x-delete-button>
                                    @elseif($order->status == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Reject</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    <h4>No Worker found on this order.</h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('hire.updated') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="form-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="payment">Payment Status</label>
                                <select name="payment" id="payment" class="form-control">
                                    <option value="0" {{ $order->payment_at == null ? 'selected' : '' }}>Unpaid</option>
                                    <option value="1" {{ $order->payment_at != null ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Order Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Approve</option>
                                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Reject</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg text-uppercase font-weight-bold">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
        <x-delete-modal route="hire-employee-destroy"></x-delete-modal>
    </x-button-layout>
@endsection
