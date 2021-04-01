<?php namespace App\Services;

use App\Models\Photo;
use App\Models\User;
use App\Contracts\UserContract;
use App\viewModels\TreeModels;
use App\viewModels\childModels;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserService implements UserContract
{

    public function getAll()
    {
        //Permission::create(['name' => 'update']);
        $user = User::all();
        $arrRole = Role::all();
        $result = array('user' => $user, 'arrayRole' => $arrRole);
        return $result;
    }

    public function show($id)
    {
        $users = User::findOrFail($id);
        return $users;
    }

    public function create($request)
    {
        $user = User::create([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'userName' => $request['userName'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->assignRole($request['arrayRole']);
        return $user;
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->all();

        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file' => $name]);
            $input['photo_id'] = $photo->id;

        }

        $input['password'] = bcrypt($request->password);
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->userName = $request->input('userName');
        $email = User::all()->where('email', $input['email'])->first();
        if ($email === $user->email && $email !== null || $email === null) {
            $user->email = $request->input('email');
        }
        $user->password = $input['password'];
        if (array_key_exists('photo_id', $input)) {
            $user->photo_id = $input['photo_id'];
        }

        $user->save();

        $request->session()->flash('update-user', 'User update success!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo) {
            unlink(public_path() . $user->photo->file);
            $user->photo->delete();
        }
        $user::destroy($id);

        session()->flash('delete-user', 'Delete  user success!');
    }

    public function addRole($request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->all();
        $arrayNameRole = array();
        $originRole = Role::all();

        foreach ($originRole as $nameRole) {
            array_push($arrayNameRole, $nameRole->name);
        }

        if (array_key_exists('arrayIdRole', $input)) {
            foreach ($originRole as $role) {
                if (in_array($role, $input['arrayIdRole']) == false) {

                    $user->removeRole($role);
                }
            }
            $user->assignRole($input['arrayIdRole']);
        } else {
            $user->syncRoles([]);
        }
        $request->session()->flash('assignRole-user', 'Assign role user success!');
    }

    public function editRole($id)
    {
        $user = User::findOrFail($id);
        $data = $this->showAccess($user);
        return $data;
    }

    private function showAccess(User $user)
    {
        $arrayJson = array();
        $role = Role::all();
        if (count($role) > 0) {
            foreach ($role as $roles) {
                $name = $roles->name;
                $array = $roles->getPermissionNames();
                $treeModels = new TreeModels();
                $checked = $this->checkRoleByUserId($user, $roles);
                $treeModels->setChecked($checked);
                $treeModels->setText($name);
                if (count($array) > 0) {
                    foreach ($array as $namePer) {
                        $child = new childModels();
                        $checked = false;
                        if ($user->hasRole($roles) && $user->hasDirectPermission($namePer)) {
                            $checked = $this->checkPermissionByUser($user, $namePer);
                        } else {
                            $treeModels->setChecked($checked);
                        }
                        $child->setChecked($checked);
                        $child->setText($namePer);
                        $treeModels->setChildren($child);
                    }
                }

                array_push($arrayJson, $treeModels);
            }
        }
        return $arrayJson;
    }

    private function checkPermissionByUser(User $user, $permission): bool
    {
        $check = false;
        if ($user->hasDirectPermission($permission)) {
            $check = true;
        }
        return $check;
    }

    private function checkRoleByUserId(User $user, $role): bool
    {
        $check = false;
        if ($user->hasRole($role)) {
            $check = true;
        }
        return $check;
    }

    public function edit($id)
    {
        $users = User::findOrFail($id);
        return $users;
    }
}
