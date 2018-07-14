<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index() {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(30);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request) {
        //不填密码默认密码不修改
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user) {
        //删除用户的同时删除用户的微博,关注的人以及粉丝
        //1.删除用户微博
        $user->statuses()->delete();
        //2.取消用户的粉丝和关注的人
        $user->followings()->detach();
        $user->followers()->detach();
        $name = $user->name;
        $user->delete();
        session()->flash('success', "成功删除用户  [$name]  !");
        return back();
    }

    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜您,激活成功!');
        return redirect()->route('users.show', $user->id);
    }

    public function followers(User $user) {
        $users = $user->followers()->paginate(15);
        $title = $user->name."的粉丝";
        if($user->id === Auth::id()){
           $title="我的粉丝"; 
        }
        return view('users.show_follow', compact('users', 'title','user'));
    }

    public function followings(User $user) {
        $users = $user->followings()->paginate(15);
        $title = $user->name."关注的人";
        if($user->id === Auth::id()){
           $title="我关注的人"; 
        }
        return view('users.show_follow', compact('users', 'title','user'));
    }

    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $name = 'Xiyoulan';
        $to = $user->email;
        $subject = "感谢注册 Hey 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

}
