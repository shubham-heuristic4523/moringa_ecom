{{-- Demo page for <x-admin.form>. To reuse for another section (Orders, Customers…),
     only $demoFields below changes — the markup stays the same. --}}
@extends('layouts.app')

@section('title', 'Form Demo')
@section('page-title', 'Form Demo')
@section('page-subtitle', 'Reusable form — swap the field array to reuse for any section.')

@php
    $demoFields = [
        ['name' => 'name', 'label' => 'Product Name', 'required' => true, 'width' => 'half', 'placeholder' => 'e.g. Moringa Powder 250g'],
        ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'step' => '0.01', 'required' => true, 'width' => 'half'],
        ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'width' => 'half', 'placeholder' => 'Select a category', 'options' => [
            'powder' => 'Powder',
            'capsules' => 'Capsules',
            'tea' => 'Tea',
            'oil' => 'Oil',
        ]],
        ['name' => 'image', 'label' => 'Product Image', 'type' => 'file', 'accept' => 'image/*', 'width' => 'half'],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'width' => 'full', 'placeholder' => 'Short description of the product…'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'checkboxLabel' => 'Show on homepage', 'width' => 'full'],
    ];
@endphp

@section('content')
    <x-admin.form
        title="Add Product"
        subtitle="Sample fields — swap `fields` to reuse this form for any other section."
        action="#"
        :fields="$demoFields"
        submit-label="Save Product"
        cancel-url="{{ route('admin.list') }}"
    />
@endsection
