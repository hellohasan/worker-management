@extends('layouts.backend')

@section('content')
    <x-basic-layout :title="$page_title" type="primary">
        <div class="row pb-5">
            <div class="col-md-6">
                <div class="chart-container" style="position: relative; height:200px;">
                    <h5 class="text-center">Country Wise Employee</h5>
                    {!! $workersChart->render() !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container" style="position: relative; height:200px;">
                    <h5 class="text-center">Employee Status</h5>
                    {!! $statusChart->render() !!}
                </div>
            </div>
        </div>
        @role('Super Admin')
            <div class="row pb-5">
                <div class="col-md-12">
                    <div class="chart-container" style="position: relative; height:200px;">
                        <h5 class="text-center">last 30 days orders</h5>
                        {!! $last30Days->render() !!}
                    </div>
                </div>
            </div>
        @endrole

        <hr>

        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center">Latest Available Workers</h3>
            </div>
            @foreach ($workers as $key => $worker)
                <div class="col-md-2">
                    <div class="card">
                        <img class="card-img-top" src="{{ $worker->user->avatar }}" alt="Card image cap">
                        <div class="card-header d-flex justify-content-between bd-highlight">
                            <h5 class="card-title">{{ Str::limit($worker->user->name, 25) }}</h5>
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
                                @hasrole('company')
                                    <button class="btn btn-success cart_button" data-toggle="modal" data-target="#CartModal" data-id="{{ $worker->id }}" type="button"><i class="fa fa-shopping-bag"></i> Add to Bucket</button>
                                @endhasrole
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </x-basic-layout>
@endsection
