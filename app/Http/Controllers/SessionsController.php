<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller {

    public function __construct() {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create() {
        if (Auth::user()) {
            session()->flash('info', '您已经登录!');
            return redirect()->route('users.show', [Auth::user()]);
        }
        return view('sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
        //登录时判断账号是否激活,没激活返回登录页面并提示激活
        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来!');
                return redirect()->intended(route('users.show', [Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('warning','你的账号未激活,请检查邮箱中的注册邮件进行激活。');
                return back()->withInput();
            }
        } else {
            session()->flash('danger', '邮箱和密码不匹配,请检查您的输入!');
            return redirect()->back()->withInput();
        }
    }

    public function destroy() {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }

}
