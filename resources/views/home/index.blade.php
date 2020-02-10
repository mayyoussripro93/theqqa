{{--
 * Theqqa - Ads Web Application
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
--}}
@extends('layouts.master')

@section('search')
    @parent
@endsection
@section('content')
    <div class="main-container" id="homepage">

        @if (Session::has('flash_notification'))
            @include('common.spacer')
            <?php $paddingTopExists = true; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 alert-container">
                        @include('flash::message')
                    </div>
                </div>
            </div>
        @endif

        @if (isset($sections) and $sections->count() > 0)
            @foreach($sections as $section)
                @if (view()->exists($section->view))
                    @include($section->view, ['firstSection' => $loop->first])
                @endif
            @endforeach
        @endif

    </div>
@endsection

@section('after_scripts')
@endsection
