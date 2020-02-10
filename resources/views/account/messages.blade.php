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

                @if (Session::has('flash_notification'))
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                @include('flash::message')
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-3 page-sidebar">
                    @include('account.inc.sidebar')
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        <h2 class="title-2"><i class="icon-mail"></i> {{ t('Messages') }} </h2>

                        <div style="clear:both"></div>

                        <?php
                        if (isset($conversation) && !empty($conversation) > 0):

                        // Conversation URLs
                        $consUrl = lurl('account/conversations');
                        $conDelAllUrl = lurl('account/conversations/' . $conversation->id . '/messages/delete');
                        ?>
                        <div class="table-responsive">
                            <form name="listForm" method="POST" action="{{ $conDelAllUrl }}">
                                {!! csrf_field() !!}
{{--                                <div class="table-action">--}}
{{--                                    <div class="table-search pull-right col-sm-7">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="row">--}}
{{--                                                <label class="col-sm-5 control-label text-right">{{ t('Search') }} <br>--}}
{{--                                                    <a title="clear filter" class="clear-filter"--}}
{{--                                                       href="#clear">[{{ t('clear') }}]</a>--}}
{{--                                                </label>--}}
{{--                                                <div class="col-sm-7 searchpan">--}}
{{--                                                    <input type="text" class="form-control" id="filter">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <table id="addManageTable"
                                       class="table table-striped table-bordered add-manage-table table demo"
                                       data-filter="#filter" data-filter-text-only="true">
                                    <thead>
                                    <tr>
                                        <th data-sort-ignore="true" colspan="3">
                                            <a href="{{ $consUrl }}"><i class="icon-level-up"></i> {{ t('Back') }}</a>&nbsp;|&nbsp;
                                            {{ t("Conversation") }} #{{ $conversation->id }}&nbsp;|&nbsp;
                                            {{ $conversation->subject }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Main Conversation -->
                                    <tr>
                                        <?php
                                        $from_user= \App\Models\User::where('id',$conversation->from_user_id)->first();
                                        !empty($from_user)?
                                            $from_user_type=  \App\Models\UserType::where('id',$from_user->user_type_id )->first():	$from_user_type="";
                                        $to_user= \App\Models\User::where('id',$conversation->to_user_id)->first();
                                        !empty($to_user)?
                                            $to_user_type=  \App\Models\UserType::where('id',$to_user->user_type_id )->first():$to_user_type='';
                                        ?>
                                        <td colspan="3">
                                            <div class="row" style="max-width: 100%;">
                                                <div class="col-md-6">
                                                    <strong>{{ t("Sender's Name") }}
                                                        :</strong> {{ $conversation->from_name ?? '--' }} <span
                                                            class="btn-success msg-badge">{{ $from_user_type->name ?? '--' }}</span><br>
                                                    <strong>{{ t("Sender's Email") }}
                                                        :</strong> {{ $conversation->from_email ?? '--' }}<br>
                                                    <strong>{{ t("Sender's Phone") }}
                                                        :</strong> {{ $conversation->from_phone ?? '--' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ t("Receiver's Name") }}
                                                        :</strong> {{ $conversation->to_name ?? '--' }} <span
                                                            class="btn-success msg-badge">{{ $to_user_type->name ?? '--' }}</span><br>
                                                    <strong>{{ t("Receiver's Email") }}
                                                        :</strong> {{ $conversation->to_email ?? '--' }}<br>
                                                    <strong>{{ t("Receiver's Phone") }}
                                                        :</strong> {{ $conversation->to_phone ?? '--' }}<br>
                                                </div>
                                            </div>
                                            <hr>
                                    <?php
                                    $isJson = json_decode($conversation->message);

                                    if ($isJson instanceof \stdClass || is_array($isJson)) {
                                    foreach (json_decode($conversation->message) as $key => $mass) {
                                    echo "<table style='margin: auto;'>";
                                    foreach ($mass as $key => $value) {
                                        echo "<tr class = 'car-stat'>";
                                        echo "<td>" . $key . "<td>";
                                        echo "<td>" . $value . "<td>";
                                        echo "</tr>";
                                    }
                                    ?>

                                    @if (!empty($conversation->image_driving_license))
                                        <tr>
                                            <td>{{t("driving_license")}}</td>
                                            <td></td>
                                            <td>
                                                <img class="msg-img"
                                                     src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_driving_license)}}"
                                                     data-toggle="modal" data-target="#dlModal"
                                                     title="{{t('click_enlarge')}}">
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif

                                    @if (!empty($conversation->image_purchaser_id))
                                        <tr>
                                            <td>{{t('purchaser id image')}}</td>
                                            <td></td>
                                            <td>
                                                <img class="msg-img"
                                                     src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_purchaser_id)}}"
                                                     data-toggle="modal" data-target="#piModal"
                                                     title="{{t('click_enlarge')}}">
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif

                                    @if (!empty($conversation->image_seller_id))
                                        <tr>
                                            <td>{{t('seller id image')}}</td>
                                            <td></td>
                                            <td>
                                                <img class="msg-img"
                                                     src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_seller_id)}}"
                                                     data-toggle="modal" data-target="#siModal"
                                                     title="{{t('click_enlarge')}}">
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif

                                    <?php
                                    if(!empty($conversation->image_car_arr)){

                                    ?>
                                    <tr>
                                        <td>{{t('car Pictures')}}</td>
                                        <td></td>
                                        <td>
                                            <?php
                                            foreach (explode(",", $conversation->image_car_arr) as $key => $mass) { ?>
                                            <img class='msg-img car-img-ow' src=" {{ url('/storage/app/service/' . $conversation->id_code.'/'. $mass)}} "
                                                 data-toggle='modal' data-target='#cpModal'
                                                 title=" {{t('click_enlarge') }} ">
                                            <?php   } ?>
                                        </td>

                                        <td></td>
                                    </tr>
                                    <?php  } ?>
                                    <?php
                                    echo "</table>";
                                    //													print_r($mass);
                                    }
                                    } else {
                                        echo nl2br($conversation->message);
                                    }
                                    ?>

                                    <!-- Start Driving License Modal -->
                                    <div class="modal fade" id="dlModal" tabindex="-1" role="dialog"
                                         aria-labelledby="dlModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="dlModalLabel">{{t("driving_license")}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_driving_license)}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Driving License Modal -->

                                    <!-- Start Purchaser Id Image Modal -->
                                    <div class="modal fade" id="piModal" tabindex="-1" role="dialog"
                                         aria-labelledby="piModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="piModalLabel">{{t('purchaser id image')}}</h4>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_purchaser_id)}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Purchaser Id Image Modal -->

                                    <!-- Start Seller Id Image Modal -->
                                    <div class="modal fade" id="siModal" tabindex="-1" role="dialog"
                                         aria-labelledby="siModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="siModalLabel">{{t('seller id image')}}</h4>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{url('/storage/app/service/'. $conversation->id_code.'/'.$conversation->image_seller_id)}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Seller Id Image Modal -->


                                    <!-- Start Car Pictures Modal -->
                                    <div class="modal fade" id="cpModal" tabindex="-1" role="dialog"
                                         aria-labelledby="cpModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="cpModalLabel">{{t('car Pictures')}}</h4>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--{!! nl2br($conversation->message) !!}--}}
                                    @if (!empty($conversation->filename) and \Storage::exists($conversation->filename))
                                        <br><br><a class="btn btn-info"
                                                   href="{{ \Storage::url($conversation->filename) }}">{{ t('Download') }}</a>
                                    @endif
                                    <hr>
                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                       data-target="#replyTo{{ $conversation->id }}">
                                        <i class="icon-reply"></i> {{ t('Reply') }}
                                    </a>
                                    </td>
                                    </tr>
                                    <!-- All Conversation's Messages -->
                                    <?php
                                    if (isset($messages) && $messages->count() > 0):
                                    foreach($messages as $key => $message):
                                    ?>
                                    <tr>
                                        @if ($message->from_user_id == auth()->user()->id)
                                            <td class="add-img-selector">
                                                <div class="checkbox" style="width:2%">
                                                    <label><input type="checkbox" name="entries[]"
                                                                  value="{{ $message->id }}"></label>
                                                </div>
                                            </td>
                                            <td style="width:88%;">
                                                <div style="word-break:break-all;">
                                                    <strong>
                                                        <i class="icon-reply"></i> {{ $message->from_name }}:
                                                    </strong><br>
                                                    {!! nl2br($message->message) !!}
                                                    @if (!empty($message->filename) and \Storage::exists($message->filename))
                                                        <br><br><a class="btn btn-info"
                                                                   href="{{ \Storage::url($message->filename) }}">{{ t('Download') }}</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="action-td" style="width:10%">
                                                <div>
                                                    <p>
                                                        <?php $conDelUrl = lurl('account/conversations/' . $conversation->id . '/messages/' . $message->id . '/delete'); ?>
                                                        <a class="btn btn-danger btn-sm delete-action"
                                                           href="{{ $conDelUrl }}">
                                                            <i class="fa fa-trash"></i> {{ t('Delete') }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </td>
                                        @else
                                            <td colspan="3">
                                                <div style="word-break:break-all;">
                                                    <strong>{{ $message->from_name }}:</strong><br>
                                                    {!! nl2br($message->message) !!}
                                                    @if (!empty($message->filename) and \Storage::exists($message->filename))
                                                        <br><br><a class="btn btn-info"
                                                                   href="{{ \Storage::url($message->filename) }}">{{ t('Download') }}</a>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                                @if (isset($messages) && $messages->count() > 0)
                                    <div class="table-action">
                                        <label for="checkAll">
                                            <input type="checkbox" id="checkAll">
                                            {{ t('Select') }}: {{ t('All') }} |
                                            <button type="submit" class="btn btn-sm btn-default delete-action">
                                                <i class="fa fa-trash"></i> {{ t('Delete') }}
                                            </button>
                                        </label>
                                    </div>
                                @endif

                            </form>
                        </div>

                        <nav>
                            {{ (isset($messages)) ? $messages->links() : '' }}
                        </nav>
                        <?php endif; ?>

                        <div style="clear:both"></div>

                    </div>
                </div>
                <!--/.page-content-->

            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!-- /.main-container -->

    @if (isset($conversation) && $conversation->count() > 0)
        @include('account.inc.reply-message')
    @endif

@endsection

@section('after_scripts')
    <script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $('#addManageTable').footable().bind('footable_filtering', function (e) {
                var selected = $('.filter-status').find(':selected').text();
                if (selected && selected.length > 0) {
                    e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                    e.clear = !e.filter;
                }
            });

            $('.clear-filter').click(function (e) {
                e.preventDefault();
                $('.filter-status').val('');
                $('table.demo').trigger('footable_clear_filter');
            });

            $('#checkAll').click(function () {
                checkAll(this);
            });

            $('a.delete-action, button.delete-action').click(function (e) {
                e.preventDefault();
                /* prevents the submit or reload */
                var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");

                if (confirmation) {
                    if ($(this).is('a')) {
                        var url = $(this).attr('href');
                        if (url !== 'undefined') {
                            redirect(url);
                        }
                    } else {
                        $('form[name=listForm]').submit();
                    }
                }

                return false;
            });
        });
    </script>
    <!-- include custom script for ads table [select all checkbox]  -->
    <script>
        function checkAll(bx) {
            var chkinput = document.getElementsByTagName('input');
            for (var i = 0; i < chkinput.length; i++) {
                if (chkinput[i].type == 'checkbox') {
                    chkinput[i].checked = bx.checked;
                }
            }
        }
    </script>
@endsection