@extends('layouts.main')
@section('name')
    <h1>Users</h1>
@endsection
@section('root')
    App
@endsection
@section('model')
    Users
@endsection
@section('content')

    <div class="col-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Basic Data Table</h2>

                <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                        data-target="#exampleModalForm">
                    <i class=" mdi mdi-plus-circle"></i> Create User

                </button>

            </div>

            <div class="card-body">
                <div class="basic-data-table">
                    <table id="basic-data-table" class="table nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Last name</th>
                            <th>First name</th>
                            <th>User Name</th>
                            <th>E-mail</th>
                            <th>Role Name</th>
                            <td></td>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($user as $users )
                            <tr>
                                <td>{{$users->id}}</td>
                                <td> {{$users->lastName }}</td>
                                <td> {{$users->firstName }} </td>
                                <td><a href="{{route('user.show',$users->id)}}"> {{$users->userName}}</a></td>
                                <td>{{$users->email}}</td>

                                @if(count($users->roles) ==0)
                                    <td>{{__('No active')}}</td>
                                @else
                                    <td>
                                        @foreach($users->roles as $role)
                                            <span class="mb-2 mr-2 badge badge-pill badge-info">{{$role->name}}</span>
                                        @endforeach
                                    </td>
                                @endif
                                <td class="text-right">
                                    <div class="dropdown show d-inline-block widget-dropdown">
                                        <a class="dropdown-toggle icon-burger-mini" href="#" role="button"
                                           id="dropdown-recent-order5" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false" data-display="static"></a>
                                        <ul class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdown-recent-order5">
                                            <li class="dropdown-item">
                                                <a href="{{route('user.show',$users->id)}}">View</a>
                                            </li>
                                            <li class="dropdown-item">
                                                <a href="{{route('user.destroy',$users->id)}}">Remove</a>
                                            </li>
                                            <li class="dropdown-item">
                                                <a type="button" style="cursor:pointer" data-value="{{$users->id}}"
                                                   onclick="getIdUser(this)"
                                                   id='editRole' data-toggle="modal" data-target="#exampleModal">
                                                    role </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    @include('admin.user.create',['arrRoles'=>$arrRole])
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Role permissions</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <div id="tree"></div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close
                                    </button>
                                    <button type="submit" id="role-submit" class="btn btn-primary btn-pill">Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="error-handler/exception.js"></script>
    <script>

        $('#btn-submit').click(function (e) {
            e.preventDefault();
            $('#overlay').show();
            setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "{{route('user.store')}}",
                    data: {
                        "_token": '{{csrf_token()}}',
                        "lastName": $('#lastName').val(),
                        "firstName": $('#firstName').val(),
                        "userName": $('#userName').val(),
                        "email": $('#email').val(),
                        "password": $('#password').val(),
                        "password_confirmation": $("#password_confirmation").val(),
                        "arrayRole": $('#arrayRole').val()
                    },

                    success: function (data) {
                        console.log(data.user);
                        var tools = '<td class="text-right">' +
                            '<div class="dropdown show d-inline-block widget-dropdown">' +
                            '<a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-recent-order5" data-toggle="dropdown" aria-haspopup="true" ' +
                            'aria-expanded="false" data-display="static"></a>' +
                            '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-recent-order5">' +
                            '<li class="dropdown-item">' +
                            '<a href="' + 'user' + '/show/' + data.user.id + '">' + 'View' + '</a>' +
                            '</li>' +
                            '<li class="dropdown-item">' +
                            '<a href="' + 'user' + '/destroy/' + data.user.id + '">' + 'Remove' + '</a>' +
                            '</li>' +
                            '<li class="dropdown-item">' +
                            '<a href="' + 'user' + '/destroy/' + data.user.id + '">' + 'Assign access' + '</a>' +
                            '</li>' +
                            '</ul>' +
                            '</div>' +
                            '</td>';

                        var rowName = '<td>' + '<a href="/user/' + data.user.id + '">' + data.user.userName + '</a>' + '</td>'
                        var $row = $('<tr>' +
                            '<td>' + data.user.id + '</td>' +
                            '<td>' + data.user.lastName + '</td>' +
                            '<td>' + data.user.firstName + '</td>' +
                            rowName +
                            '<td>' + data.user.email + '</td>' +
                            '<td>' + '</td>' +
                            tools +
                            '</tr>');
                        var rowRoles = '<span>No active</span>';
                        if (data.user.roles.length !== 0) {
                            var array = data.user.roles;
                            array.forEach(function (item) {
                                rowRoles = '<span class="mb-2 mr-2 badge badge-pill badge-info">' + item.name + '</span>' + '  ';
                                $row.find("td").eq(5).append(rowRoles);
                            })
                        } else {

                            $row.find("td").eq(5).append(rowRoles);
                        }
                        $('table> tbody:last').append($row);
                        $('.alert-highlighted').show();
                        $('#overlay').hide();
                        $('#exampleModalForm').modal('hide');
                        $('.alert-highlighted').fadeOut(5000);

                    },
                    error: function (data) {
                        $.fn.handlerError(data);
                    }
                });
            }, 500);

        });
        let tree;
        let id;
        let reportRecipientsDuplicate =[];
        function  checkDuplicate(reportRecipient){
                $.each(reportRecipient,function (i,el){
                      if($.inArray(el,reportRecipientsDuplicate)===-1){
                              reportRecipientsDuplicate.push(el);
                      }

                })
        }

        function getIdUser(item) {

            id = item.getAttribute("data-value");
            console.log(id);
            tree = $('#tree').tree({
                //  idUser:idUser,
                primaryKey: 'text',
                uiLibrary: 'bootstrap4',
                dataSource: '/user/' + id + '/role',
                checkboxes: true
            });

            console.log(tree);
        }


        $('#role-submit').click(function () {

           var checkedText = tree.getCheckedNodes();
            checkDuplicate(checkedText);
            console.log(reportRecipientsDuplicate);
            $.ajax({
                type: 'POST',
                catch: false,
                url: 'user/' + id + '/addRole',

                data: {
                    "_token": '{{csrf_token()}}',
                    'data': reportRecipientsDuplicate

                },
               success:function(data){
                      console.log(data.success);
               },
               error:function (error){
                   console.log(error);
               }
            })

        })


    </script>
@endsection



