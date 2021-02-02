<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');
    }
    public function index(Request $request)
    {
        // if ($request->search) {
        //     $users = User::where('first_name', 'like', '%' . $request->search . '%')
        //         ->orWhere('last_name', 'like', '%' . $request->search . '%')
        //         ->get();
        //     // dd($users);
        // } else {
        //     $users = User::whereRoleIs('admin')->get();
        // }

        $users = User::whereRoleIs('admin')->where(function ($q) use ($request) {
            $q->when($request->search, function ($query) use ($request) {
                return $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        })->latest()->paginate(8);


        return view('dashboard.users.index', compact('users'));
    }
    public function create()
    {

        return view('dashboard.users.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'image' => 'image',
            'permissions' => 'required|min:1'
        ]);
        // dd($request->permissions);
        $request_data = $request->except(['password', 'password_confirmation', 'permissions', 'image']);
        $request_data['password'] = bcrypt($request->password);

        if ($request->image) {

            //save the images in folder

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            //add image to the request

            $request_data['image'] = $request->image->hashName();
        }

        $user = User::create($request_data);
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));
        return Redirect('dashboard/users/index');
    }
    public function update(Request $request, User $user)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
            'permissions' => 'required|min:1'
        ]);
        $request_data = $request->except(['permissions', 'image']);

        if ($request->image) {
            if ($user->image != 'default.png') {
                Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
            }
            //save the images in folder

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            //add image to the request

            $request_data['image'] = $request->image->hashName();
        }

        $user->update($request_data);
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfully'));
        return Redirect('dashboard/users/index');
    }
    public function edit(User $user)
    {

        return view('dashboard/users/edit', compact('user'));
    }
    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }
        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.index');
    }
}
