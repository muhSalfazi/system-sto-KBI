<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index()
    {
        // Ambil semua user yang role-nya bukan superadmin
        $users = User::whereHas('role', function ($query) {
            $query->where('name', '!=', 'superadmin');
        })->get();

        return view('Users.index', compact('users'));
    }


    // Menampilkan form untuk menambah pengguna baru
    public function create()
    {
        // Ambil semua role kecuali superadmin
        $roles = Role::where('name', '!=', 'superadmin')->get();
        return view('Users.create', compact('roles'));
    }

    // Menyimpan pengguna baru ke dalam database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:tbl_user',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'ID-card' => 'required|string|min:3',
            'role' => 'required|exists:tbl_role,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Gunakan password dari input atau fallback ke ID-card
        $rawPassword = $request->password ?: $request->input('ID-card');
        $role = Role::where('name', $request->role)->first();

        User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nik' => $request->input('ID-card'),
            'password' => Hash::make($rawPassword),
            'id_role'    => $role->id,
        ]);

        // Cari ID role berdasarkan name

        if (!$role) {
            return redirect()->back()->with('error', 'Role tidak ditemukan.');
        }

        return redirect()->route('users.index')->with('success', 'User successfully created');
    }


    // Menampilkan form untuk mengedit pengguna
        public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();  // Ambil semua role untuk dipilih di form
        return view('Users.edit', compact('user', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:tbl_user,username,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ]
        ], [
            'password.regex' => 'Password harus mengandung minimal satu huruf besar, satu huruf kecil, satu angka, dan satu simbol.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User successfully updated');
    }


    // Menghapus pengguna dari database
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User successfully deleted');
    }
}
