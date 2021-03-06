@extends('layout.index')
@section('css')
    @parent
    {{HTML::style('css/common.css')}}
    {{HTML::style('css/register.css')}}
@stop

@section('js')
    @parent
    {{HTML::script('js/register.js')}}
@stop

@section('body')
    @include('index.menu')
    <div class="col-sm-6 col-sm-offset-3 register-div">
        <div class="col-sm-10 col-sm-offset-1 register-title">
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <button type="button" target="user" class="btn btn-default @if($type=="user") active @endif">用户</button>
                </div>
                <div class="btn-group">
                    <button type="button" target="doctor" class="btn btn-default @if($type=="doctor") active @endif">医生</button>
                </div>
            </div>
        </div>
        <form role="form" method="post" action="@if($type=="user") /usrreg/usersignin @elseif($type=="doctor") /docreg/docsignin @endif" class="reg-form form-horizontal col-sm-10 col-sm-offset-1">
            <div class="form-group">
                           <label for="username" class="col-sm-2 control-label">用户名</label>
                           <div class="col-sm-10">
                               <input type="text" name="username" class="form-control" id="username" placeholder="请输入用户名">
                           </div>
                         </div>
            <div class="form-group">
                             <label for="password" class="col-sm-2 control-label">密码</label>
                             <div class="col-sm-10">
                                 <input type="password" name="password" class="form-control" id="password" placeholder="请输入密码">
                             </div>
                         </div>
            <div class="form-group">
                             <label for="repassword" class="col-sm-2 control-label">确认密码</label>
                             <div class="col-sm-10">
                                 <input type="password" class="form-control" id="repassword" placeholder="再一次输入密码">
                             </div>
                         </div>
            <div class="form-group">
                 <label for="tel" class="col-sm-2 control-label">手机</label>
                 <div class="col-sm-10">
                     <input type="tel" name="tel" class="form-control" id="tel" placeholder="请输入身份证号码">
                 </div>
              </div>

            <div class="form-group different @if($type=="doctor") not-current-user @endif ">
                <label for="name" class="col-sm-2 control-label">真实姓名</label>
                <div class="col-sm-10">
                   <input type="text" name="name" class="form-control" id="name" placeholder="请输入真实姓名">
                </div>
              </div>
            <div class="form-group different @if($type=="doctor") not-current-user @endif ">
                 <label for="idnum" class="col-sm-2 control-label">身份证</label>
                 <div class="col-sm-10">
                     <input type="text" name="idnum" class="form-control" id="idnum" placeholder="请输入身份证号码">
                 </div>
             </div>

            <div class="form-group different @if($type=="user") not-current-user @endif ">
                    <label for="department_id" class="col-sm-2 control-label">部门ID</label>
                    <div class="col-sm-10">
                       <input type="text" name="department_id" class="form-control" id="department_id" placeholder="请输入部门ID">
                    </div>
                </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default col-sm-offset-2 col-sm-3">注册</button>
            </div>
        </form>
    </div>
@stop