@extends('layouts.backend')
@section('content')
    <x-button-layout :title="$page_title" icon="fas fas fa-th-large" btnText="Add Category" btnIcon="fas fa-plus" :btnRoute="route('categories.create')" :permissions="['categories.create']">
        <x-table :headers="['#SL', 'Name', 'Featured', 'Status', 'Action']">
            @forelse ($categories as $key => $category)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <x-status-badge :value="$category->featured" successText="Featured" failText="Non Featured"></x-status-badge>
                    </td>
                    <td>
                        <x-status-badge :value="$category->status"></x-status-badge>
                    </td>
                    <td>
                        <x-edit-button :route="route('categories.edit', $category->id)"></x-edit-button>
                        <x-delete-button :id="$category->id"></x-delete-button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center font-weight-bold">No Category Found</td>
                </tr>
            @endforelse
        </x-table>
    </x-button-layout>

    <x-delete-modal route="categories.destroy"></x-delete-modal>
@endsection
