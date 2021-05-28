@extends('backend.layouts.app')
@push('after-styles')
    <style>
        #news .table-responsive , #categories .table-responsive {
            height: 500px;
            overflow-y: scroll;
        }
    </style>
    @endpush

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('labels.backend.access.users.management') }} <small class="text-muted">{{ __('labels.backend.access.users.active') }}</small>
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                @include('backend.auth.user.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('labels.backend.access.users.table.last_name')</th>
                            <th>@lang('labels.backend.access.users.table.first_name')</th>
                            <th>@lang('labels.backend.access.users.table.email')</th>
                            <th>Categories</th>
                            <th>News</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><button class="btn btn-primary" onclick="openCategory({{$user->id}} , '{{$user->first_name}}')">Categories</button></td>
                                <td><button class="btn btn-primary" onclick="openNews({{$user->id}} , '{{$user->first_name}}')">News</button></td>
                                <td>{{ $user->updated_at->diffForHumans() }}</td>
                                <td class="btn-td">@include('backend.auth.user.includes.actions', ['user' => $user])</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->

        <!-- Modal -->
        <div class="modal fade" id="categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryUserName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Category Name</th>
                            </tr>
                            </thead>
                            <tbody id="categoriescontent">

                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="news" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newsChannelName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Channel Name</th>
                                </tr>
                                </thead>
                                <tbody id="newscontent">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $users->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection

    <script>
        function openCategory(id , name){
            let url = "{{url('admin/auth/categories/')}}" +'/'+ id
            $.ajax({
                url: url,
                success: function (result){
                    let html = ``;
                    $.each(result , function (index , i){
                        html += `<tr><td>${i.category_name}</td></tr>`
                    })
                    html += ``;
                    $('#categoriescontent').html(html);
                    $('#categoryUserName').text(name)
                    $('#categories').modal('show');
                }
            })
        }

        function openNews(id , name){
            let url = "{{url('admin/auth/news/')}}" +'/'+ id
            $.ajax({
                url: url,
                success: function (result){
                    let html = ``;
                    $.each(result , function (index , i){
                        html += `<tr><td>${i.channel_name}</td></tr>`
                    })
                    html += ``;
                    $('#newscontent').html(html);
                    $('#newsChannelName').text(name)
                    $('#news').modal('show');
                }
            })
        }
    </script>
