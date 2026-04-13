@extends('layouts.app')

@section('meta_title', 'Danh sách sản phẩm - SportWear Shop')
@section('meta_description', 'Tìm và lọc các sản phẩm quần áo thể thao nam theo danh mục, thương hiệu, kích cỡ, màu sắc')

@section('content')
    <livewire:product-filter />
@endsection
