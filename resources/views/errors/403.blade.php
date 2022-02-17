@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Доступ запрещен'))

@section('action')
    <div class="text-center mt-4 underline">
        <a class=" text-gray-500" href="/logout">Выйти</a>
    </div>
@endsection
