<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(config('erp.pagination_size'));

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create', [
            'roles' => UserRole::values(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password,
                'role'      => $request->role,
                'is_active' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user'  => $user,
            'roles' => UserRole::values(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = [
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        try {
            $user->update([
                'is_active' => !$user->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('User status toggle failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('users.index')
            ->with('success', "User {$status} successfully.");
    }
}
