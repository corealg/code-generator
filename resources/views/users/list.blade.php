@extends('layouts.app')

@section("page_title") User @endsection

@section("content_header") User @endsection

@section('content')
<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User List ({{ $users->count() }})</h5>
                <div class="heading-elements">
                    @can('create', \App\User::class)
                        <a href="{{url('user/create')}}" class="btn btn-danger mr-1 mb-1">
                            <i class="bx bx-plus-circle"></i> Create New
                        </a>
                    @else
                        <a href="{{url('user/create')}}" class="btn btn-danger mr-1 mb-1 disabled">
                            <i class="bx bx-plus-circle"></i> Create New
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">Sl.</th>
                                    <th>Id</th>
                                    <th width="10%" class="text-center">Created At</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @forelse($users as $user)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ $user->id }}</td>
                                    <td class="text-center">{{ Parser::parseDate($user->created_at) }}</td>
                                    <td class="text-center">

                                        @can('view', $user)
                                            <a href="{{url('user')}}/{{ $user->id }}" class="btn btn-icon btn-info glow mr-1 mb-1"><i class="bx bx-show"></i></a>
                                        @else
                                            <a href="#" class="btn btn-icon btn-info glow mr-1 mb-1 disabled"><i class="bx bx-eye"></i></a>
                                        @endcan

                                        @can('edit', $user)
                                            <a href="{{url('user/edit')}}/{{ $user->id }}" class="btn btn-icon btn-success glow mr-1 mb-1"><i class="bx bx-edit-alt"></i></a>
                                        @else
                                            <a href="#" class="btn btn-icon btn-success glow mr-1 mb-1 disabled"><i class="bx bx-edit-alt"></i></a>
                                        @endcan

                                        @can('delete', $user)
                                            <a href="#" class="btn btn-icon btn-danger alert-dialog glow mr-1 mb-1" data-id="{{ $user->id }}" data-action="user/delete" data-message="Are you sure, You want to remove this User?"><i class="bx bx-trash-alt"></i></a>
                                        @else
                                            <a href="#" class="btn btn-icon btn-danger glow mr-1 mb-1 disabled"><i class="bx bx-trash-alt"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No Records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        @if(!empty($users->pagination_summary))
                                            <span class="label label-primary">{{ $users->pagination_summary }}</span>
                                        @endif
                                    </td>
                                    <td colspan="2">
                                        <div class="pull-right">{{ $users->links() }}</div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection