<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');
        return User::when($q, fn($s) => $s->where('name','like',"%{$q}%"))
            ->paginate(10);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'is_admin'=>'boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(Request $r, User $user)
    {
        $data = $r->validate([
            'name'=>'sometimes|string|max:100',
            'email'=>'sometimes|email|unique:users,email,'.$user->id,
            'password'=>'nullable|min:6',
            'is_admin'=>'boolean',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
