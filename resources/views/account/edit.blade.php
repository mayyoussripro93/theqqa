{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
 */
--}}
@extends('layouts.master')

@section('content')
    @include('common.spacer')
    <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @include('account.inc.sidebar')
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">

                    @include('flash::message')

                    @if (isset($errors) and $errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5>
                                <strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong>
                            </h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>

                    <div class="inner-box default-inner-box">
                        <div class="row">
                            <div class="col-md-5 col-xs-4 col-xxs-12">
                                <h3 class="no-padding text-center-480 useradmin">
                                    <a href="">
                                        @if (!empty($userPhoto))
                                            <img id="userImg" class="userImg" src="{{ $userPhoto }}" alt="user">&nbsp;
                                        @else
                                            <img id="userImg" class="userImg" src="{{ url('images/user.jpg') }}"
                                                 alt="user">
                                        @endif
                                        {{ $user->name }}
                                    </a>
                                </h3>
                            </div>
                            <div class="col-md-7 col-xs-8 col-xxs-12">
                                <div class="header-data text-center-xs">
                                    <!-- Conversations Stats -->
                                    <div class="hdata">
                                        <div class="mcol-left">
                                            <i class="fas fa-envelope ln-shadow"></i></div>
                                        <div class="mcol-right">
                                            <!-- Number of messages -->
                                            <p>
                                                <a href="{{ lurl('account/conversations') }}">
                                                    {{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
                                                    <em>{{ trans_choice('global.count_mails', getPlural($countConversations)) }}</em>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <!-- Traffic Stats -->
                                    <div class="hdata">
                                        <div class="mcol-left">
                                            <i class="fa fa-eye ln-shadow"></i>
                                        </div>
                                        <div class="mcol-right">
                                            <!-- Number of visitors -->
                                            <p>
                                                <a href="{{ lurl('account/my-posts') }}">
                                                    <?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
                                                    {{ \App\Helpers\Number::short($totalPostsVisits) }}
                                                    <em>{{ trans_choice('global.count_visits', getPlural($totalPostsVisits)) }}</em>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <!-- Ads Stats -->
                                    <div class="hdata">
                                        <div class="mcol-left">
                                            <i class="icon-th-thumb ln-shadow"></i>
                                        </div>
                                        <div class="mcol-right">
                                            <!-- Number of ads -->
                                            <p>
                                                <a href="{{ lurl('account/my-posts') }}">
                                                    {{ \App\Helpers\Number::short($countPosts) }}
                                                    <em>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</em>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <!-- Favorites Stats -->
                                    <div class="hdata">
                                        <div class="mcol-left">
                                            <i class="fa fa-user ln-shadow"></i>
                                        </div>
                                        <div class="mcol-right">
                                            <!-- Number of favorites -->
                                            <p>
                                                <a href="{{ lurl('account/favourite') }}">
                                                    {{ \App\Helpers\Number::short($countFavoritePosts) }}
                                                    <em>{{ trans_choice('global.count_favorites', getPlural($countFavoritePosts)) }} </em>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inner-box default-inner-box">
                        <div class="welcome-msg">
                            <h3 class="page-sub-header2 clearfix no-padding">{{ t('Hello') }} {{ $user->name }} ! </h3>
                            <span class="page-sub-header-sub small">
                                {{ t('You last logged in at') }}
                                : {{ $user->last_login_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                            </span>
                        </div>

                        <div id="accordion" class="panel-group">
                            <!-- PHOTO -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="#photoPanel" data-toggle="collapse"
                                           data-parent="#accordion">{{ t('Photo or Avatar') }}</a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='photoPanel') ? 'show' : '' }}"
                                     id="photoPanel">
                                    <div class="card-body">
                                        <form name="details" class="form-horizontal" role="form" method="POST"
                                              action="{{ lurl('account/' . $user->id . '/photo') }}">
                                            <div class="row">
                                                <div class="col-xl-12 text-center">

                                                    <?php $photoError = (isset($errors) and $errors->has('photo')) ? ' is-invalid' : ''; ?>
                                                    <div class="photo-field">
                                                        <div class="file-loading">
                                                            <input id="photoField" name="photo" type="file"
                                                                   class="file {{ $photoError }}">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- USER -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="#userPanel" aria-expanded="true" data-toggle="collapse"
                                           data-parent="#accordion">{{ t('Account Details') }}</a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }}"
                                     id="userPanel">
                                    <div class="card-body">
                                        <form name="details" class="form-horizontal" role="form" method="POST"
                                              action="{{ url()->current() }}">
                                            {!! csrf_field() !!}
                                            <input name="_method" type="hidden" value="PUT">
                                            <input name="panel" type="hidden" value="userPanel">

                                            <!-- gender_id -->
                                            <?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-3 col-form-label">{{ t('Gender') }}</label>
                                                <div class="col-md-9">
                                                    @if ($genders->count() > 0)
                                                        @foreach ($genders as $gender)
                                                            <div class="form-check form-check-inline pt-2">
                                                                <input name="gender_id"
                                                                       id="gender_id-{{ $gender->tid }}"
                                                                       value="{{ $gender->tid }}"
                                                                       class="form-check-input{{ $genderIdError }}"
                                                                       type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}
                                                                >
                                                                <label class="form-check-label"
                                                                       for="gender_id-{{ $gender->tid }}">
                                                                    {{ $gender->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- name -->
                                            <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-3 col-form-label">{{ t('Name') }}
                                                    <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="name" type="text" class="form-control{{ $nameError }}"
                                                           placeholder="" value="{{ old('name', $user->name) }}">
                                                </div>
                                            </div>

                                            <!-- username -->
                                            <?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-3 col-form-label" for="email">{{ t('Username') }}
                                                    <sup>*</sup></label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="icon-user"></i></span>
                                                    </div>

                                                    <input id="username"
                                                           name="username"
                                                           type="text"
                                                           class="form-control{{ $usernameError }}"
                                                           placeholder="{{ t('Username') }}"
                                                           value="{{ old('username', $user->username) }}"
                                                    >
                                                </div>
                                            </div>

                                            <!-- email -->
                                            <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-3 col-form-label">{{ t('Email') }}
                                                    <sup>*</sup></label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="icon-mail"></i></span>
                                                    </div>

                                                    <input id="email"
                                                           name="email"
                                                           type="email"
                                                           class="form-control{{ $emailError }}"
                                                           placeholder="{{ t('Email') }}"
                                                           value="{{ old('email', $user->email) }}"
                                                    >
                                                </div>
                                            </div>

                                            <!-- country_code -->
                                            <?php
                                            /*
                                            <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 control-label{{ $countryCodeError }}" for="country_code">
                                            		{{ t('Your Country') }} <sup>*</sup>
                                            	</label>
												<div class="col-md-9">
													<select name="country_code" class="form-control sselecter{{ $countryCodeError }}">
														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>
															{{ t('Select a country') }}
														</option>
														@foreach ($countries as $item)
															<option value="{{ $item->get('code') }}" {{ (old('country_code', $user->country_code)==$item->get('code')) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
                                            */
                                            ?>
                                            <input name="country_code" type="hidden" value="{{ $user->country_code }}">

                                            <!-- phone -->
                                            <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label for="phone" class="col-md-3 col-form-label">{{ t('Phone') }}
                                                    <sup>*</sup></label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-prepend">
                                                        <span id="phoneCountry"
                                                              class="input-group-text">{!! getPhoneIcon(old('country_code', $user->country_code)) !!}</span>
                                                    </div>

                                                    <input id="phone" name="phone" type="text"
                                                           class="form-control{{ $phoneError }}"
                                                           placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
                                                           value="{{ phoneFormat(old('phone', $user->phone), old('country_code', $user->country_code)) }}">

                                                    <div class="input-group-append">
														<span class="input-group-text">
															<input name="phone_hidden" id="phoneHidden" type="checkbox"
                                                                   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;
															<small>{{ t('Hide') }}</small>
														</span>
                                                    </div>

                                                </div>
                                            </div>
                                            @if(auth()->user()->user_type_id != 2)
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-9">
                                                        <div class="form-group ads-googlemaps" id="googleMap_from"
                                                             style="width:100%;height:250px;">
                                                            <div class="card ">
                                                                <div class="card-header">{{ t('Location\'s Map') }}</div>
                                                                <div class="card-content">
                                                                    <div class="card-body text-left p-0">
                                                                        <div class="ads-googlemaps">
                                                                        </div>
                                                                        {{--<iframe id="googleMaps" width="100%" height="250" frameborder="0"--}}
                                                                        {{--scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $subladmin1 = (isset($errors) and $errors->has('subladmin1')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required" id="subadmin1">
                                                    <label class="col-md-3 col-form-label">{{ t( 'subladmin1') }}
                                                        <sup>*</sup></label>
                                                    <div class="col-md-6">



                                                        <select name="exhibitions_place[]" id="exhibitions_place"
                                                                class="form-control {{ $subladmin1 }}" multiple="multiple">
                                                            @foreach ($subladmin1s as $subladmin1)
                                                                <option lat="{{ $subladmin1->latitude }}"
                                                                        long="{{ $subladmin1->longitude }}"
                                                                        value="{{ $subladmin1->id }}"> {{ $subladmin1->name }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>
                                            @endif
                                            <div class="form-group row">
                                                <div class="offset-md-3 col-md-9"></div>
                                            </div>

                                            <!-- Button -->
                                            <div class="form-group row">
                                                <div class="offset-md-3 col-md-9">
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ t('Update') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTS -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="#docPanel" aria-expanded="true" data-toggle="collapse"
                                           data-parent="#accordion">{{ t('doc_pic') }}</a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='docPanel') ? 'show' : '' }}"
                                     id="docPanel">
                                    <div class="card-body">
                                        <?php
                                        if(!empty($user->image_data)){
                                        $isJson = json_decode($user->image_data);
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="msg-title">{{t('doc_pic')}}</h4>
                                            </div>
                                        <?php
                                        if ($isJson instanceof \stdClass || is_array($isJson)) {
                                            foreach (json_decode($user->image_data) as $key => $mass) {
                                                echo "<div class='col-md-3'><img  class='msg-img' id='image_data' src=" . url('/storage/app/' . $mass) . " data-toggle='modal' data-target='#docModal' title=" . t('click_enlarge') . "></div>";
                                            }
                                        }

                                        ?>
                                        <!-- Start Uploaded Documents Modal -->
                                            <div class="modal fade" id="docModal" tabindex="-1" role="dialog"
                                                 aria-labelledby="docModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"
                                                                id="docModalLabel">{{t('doc_pic')}}</h4>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <?php
                                                            echo "<img id='image_data' src=" . url('/storage/app/' . $mass) . " >"; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <?php }?>
                                        <!-- End Uploaded Documents Modal -->
                                    </div>
                                </div>
                            </div>

                           <!-- SETTINGS -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title"><a href="#settingsPanel" data-toggle="collapse"
                                                              data-parent="#accordion">{{ t('Settings') }}</a></h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='settingsPanel') ? 'show' : '' }}"
                                     id="settingsPanel">
                                    <div class="card-body">
                                        <form name="settings" class="form-horizontal" role="form" method="POST"
                                              action="{{ lurl('account/settings') }}">
                                            {!! csrf_field() !!}
                                            <input name="_method" type="hidden" value="PUT">
                                            <input name="panel" type="hidden" value="settingsPanel">

                                            @if (config('settings.single.activation_facebook_comments') and config('services.facebook.client_id'))
                                            <!-- disable_comments -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 col-form-label"></label>
                                                    <div class="col-md-9">
                                                        <div class="form-check form-check-inline pt-2">
                                                            <label>
                                                                <input id="disable_comments"
                                                                       name="disable_comments"
                                                                       value="1"
                                                                       type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}
                                                                >
                                                                {{ t('Disable comments on my ads') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        <!-- password -->
                                            <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('New Password') }}</label>
                                                <div class="col-md-9">
                                                    <input id="password" name="password" type="password"
                                                           class="form-control{{ $passwordError }}"
                                                           placeholder="{{ t('Password') }}">
                                                </div>
                                            </div>

                                            <!-- password_confirmation -->
                                            <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('Confirm Password') }}</label>
                                                <div class="col-md-9">
                                                    <input id="password_confirmation" name="password_confirmation"
                                                           type="password"
                                                           class="form-control{{ $passwordError }}"
                                                           placeholder="{{ t('Confirm Password') }}">
                                                </div>
                                            </div>

                                            <!-- Button -->
                                            <div class="form-group row">
                                                <div class="offset-md-3 col-md-9">
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ t('Update') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--/.row-box End-->

                    </div>
                </div>
                <!--/.page-content-->
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!-- /.main-container -->
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
    @endif
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }

        .file-loading:before {
            content: " {{ t('Loading') }}...";
        }
    </style>
    <style>
        /* Avatar Upload */
        .photo-field {
            display: inline-block;
            vertical-align: middle;
        }

        .photo-field .krajee-default.file-preview-frame,
        .photo-field .krajee-default.file-preview-frame:hover {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            text-align: center;
        }

        .photo-field .file-input {
            display: table-cell;
            width: 150px;
        }

        .photo-field .krajee-default.file-preview-frame .kv-file-content {
            width: 150px;
            height: 160px;
        }

        .kv-reqd {
            color: red;
            font-family: monospace;
            font-weight: normal;
        }

        .file-preview {
            padding: 2px;
        }

        .file-drop-zone {
            margin: 2px;
        }

        .file-drop-zone .file-preview-thumbnails {
            cursor: pointer;
        }

        .krajee-default.file-preview-frame .file-thumbnail-footer {
            height: 30px;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    @if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
        <script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}"
                type="text/javascript"></script>
    @endif
    <script>
        var photoInfo = '<h6 class="text-muted pb-0">{{ t('Click to select') }}</h6>';
        var footerPreview = '<div class="file-thumbnail-footer pt-2">\n' +
            '    {actions}\n' +
            '</div>';

        $('#photoField').fileinput(
            {
                theme: "fa",
                language: '{{ config('app.locale') }}',
                @if (config('lang.direction') == 'rtl')
                rtl: true,
                @endif
                overwriteInitial: true,
                showCaption: false,
                showPreview: true,
                allowedFileExtensions: {!! getUploadFileTypes('image', true) !!},
                uploadUrl: '{{ lurl('account/' . $user->id . '/photo') }}',
                uploadAsync: false,
                showBrowse: false,
                showCancel: true,
                showUpload: false,
                showRemove: false,
                maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }},
                browseOnZoneClick: true,
                minFileCount: 0,
                maxFileCount: 1,
                validateInitialCount: true,
                uploadClass: 'btn btn-primary',
                defaultPreviewContent: '<img src="{{ !empty($gravatar) ? $gravatar : url('images/user.jpg') }}" alt="{{ t('Your Photo or Avatar') }}">' + photoInfo,
                /* Retrieve current images */
                /* Setup initial preview with data keys */
                initialPreview: [
                    @if (isset($user->photo) and !empty($user->photo))
                        '{{ resize($user->photo) }}'
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                /* Initial preview configuration */
                initialPreviewConfig: [
                    {
                        <?php
                            // File size
                            try {
                                $fileSize = (int)File::size(filePath($user->photo));
                            } catch (\Exception $e) {
                                $fileSize = 0;
                            }
                            ?>
                                @if (isset($user->photo) and !empty($user->photo))
                        caption: '{{ last(explode('/', $user->photo)) }}',
                        size: {{ $fileSize }},
                        url: '{{ lurl('account/' . $user->id . '/photo/delete') }}',
                        key: {{ (int)$user->id }}
                        @endif
                    }
                ],

                showClose: false,
                fileActionSettings: {
                    removeIcon: '<i class="far fa-trash-alt"></i>',
                    removeClass: 'btn btn-sm btn-danger',
                    removeTitle: '{{ t('Remove file') }}'
                },

                elErrorContainer: '#avatarUploadError',
                msgErrorClass: 'alert alert-block alert-danger',

                layoutTemplates: {main2: '{preview} {remove} {browse}', footer: footerPreview}
            });

        /* Auto-upload added file */
        $('#photoField').on('filebatchselected', function (event, data, id, index) {
            if (typeof data === 'object') {
                {{--
                    Display the exact error (If it exists (Before making AJAX call))
                    NOTE: The index '0' is available when the first file size is smaller than the maximum size allowed.
                          This index does not exist in the opposite case.
                --}}
                if (data.hasOwnProperty('0')) {
                    $(this).fileinput('upload');
                    return true;
                }
            }

            return false;
        });

        /* Show upload status message */
        $('#photoField').on('filebatchpreupload', function (event, data, id, index) {
            $('#avatarUploadSuccess').html('<ul></ul>').hide();
        });

        /* Show success upload message */
        $('#photoField').on('filebatchuploadsuccess', function (event, data, previewId, index) {
            /* Show uploads success messages */
            var out = '';
            $.each(data.files, function (key, file) {
                if (typeof file !== 'undefined') {
                    var fname = file.name;
                    out = out + {!! t('Uploaded file #key successfully') !!};
                }
            });
            $('#avatarUploadSuccess ul').append(out);
            $('#avatarUploadSuccess').fadeIn('slow');

            $('#userImg').attr({'src': $('.photo-field .kv-file-content .file-preview-image').attr('src')});
        });

        /* Delete picture */
        $('#photoField').on('filepredelete', function (jqXHR) {
            var abort = true;
            if (confirm("{{ t('Are you sure you want to delete this picture?') }}")) {
                abort = false;
            }
            return abort;
        });

        $('#photoField').on('filedeleted', function () {
            $('#userImg').attr({'src': '{!! !empty($gravatar) ? $gravatar : url('images/user.jpg') !!}'});

            var out = "{{ t('Your photo or avatar has been deleted.') }}";
            $('#avatarUploadSuccess').html('<ul><li></li></ul>').hide();
            $('#avatarUploadSuccess ul li').append(out);
            $('#avatarUploadSuccess').fadeIn('slow');
        });
    </script>


    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>
    <link href="css/multi-select.css"/>
    <script src="js/jquery.multi-select.js" type="text/javascript"></script>
    <script>

        @if (config('settings.single.show_post_on_googlemap'))
        /* Google Maps */
        getGoogleMaps(
            '{{ config('services.googlemaps.key') }}',
            '{{ (isset($post->city) and !empty($post->city)) ? addslashes($post->city->name) . ',' . config('country.name') : config('country.name') }}',
            '{{ config('app.locale') }}'
        );
        function getCities(rlat, rlng, url) {
            $.ajax({
                type: "GET",
                data: {
                    lat: rlat,
                    lng: rlng,
                },
                url: url,
                cache: false,
                success: function (res) {
                    if($("#exhibitions_place").val() == null){
                        let all = [];
                        all.push(res.city.id);
                        $("#exhibitions_place").val(all)
                        $('#exhibitions_place').multiSelect();
                        changeLocation()
                    }else{
                        let all = $("#exhibitions_place").val();
                        all.push(res.city.id);
                        $("#exhibitions_place").val(all)
                        $('#exhibitions_place').multiSelect();
                        changeLocation()
                    }
                }
            });
        }
        var rlat, rlng;
        map = null;
        function myMap() {
            markers = [];
            function showPosition(position) {
                if (position == null) {
                    rlat =  {!! $city->latitude !!};
                    rlng = {!! $city->longitude!!};
                } else {
                    rlat = position.coords.latitude;
                    rlng = position.coords.longitude;
                }
                var url = window.location.protocol + "//" + window.location.host + "/contactfor/ownership";
                var mapProp = {
                    center: new google.maps.LatLng(rlat, rlng),
                    zoom: 4,
                };
                map = new google.maps.Map(document.getElementById("googleMap_from"), mapProp);
                var cities;
                @if(!empty($cities))
                    cities = {!! $cities !!};
                @endif

                $("#exhibitions_place option").each(function () {
                    if($.inArray($(this).val(),cities) > -1){
                        // Set the value
                        $("#exhibitions_place").val(cities);
                        changeLocation()
                        $('#exhibitions_place').multiSelect();

                    }
                })
                map.addListener('click', function (event) {
                    rlat = event.latLng.lat()
                    rlng = event.latLng.lng()
                    marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                    });
                    markers.push(marker);
                    getCities(rlat, rlng, url);
                })
            }
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (success) {
                        showPosition(success)
                    },
                    function (failure) {
                        showPosition(null)
                    });
            } else {
                $('#location').html('Geolocation is not supported by this browser.');
            }
        }
        @endif
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIZUZRKJadLxndLkF4nocisyxkV6aC-nw&callback=myMap"></script>

    <script src="{{ url('assets/js/form-validation.js') }}"></script>
    <script>
        function changeLocation(){
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
            if($("#exhibitions_place").val() != null && $("#exhibitions_place").val().length > 0) {
                for (locationId of $("#exhibitions_place").val()) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng($("[value='" + locationId +"']").attr("lat"), $("[value='" + locationId +"']").attr("long")),
                        map: map,
                    });

                    markers.push(marker);
                }
            }
        }
        $(document).ready(function () {
            $("#exhibitions_place").change(function () {
                changeLocation()
                $('#exhibitions_place').multiSelect();
            });





        });
    </script>
@endsection
