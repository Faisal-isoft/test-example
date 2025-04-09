@extends('admin.layout')

@section('title', 'Admin - Products')

@section('header', 'Admin - Products')

@section('content')
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ env('APP_URL') }}/{{ $product->image }}" width="50" height="50" alt="{{ $product->name }}">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('admin.edit.product', $product->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('admin.delete.product', $product->id) }}" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
