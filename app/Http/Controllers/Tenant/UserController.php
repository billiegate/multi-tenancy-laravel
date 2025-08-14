<?php
namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
 
class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(User::all());
    }


    public function show(Request $request, $id)
    {
        return response()->json(['id' => $id, 'data' => $request->all()]);
    }

    public function store(Request $request)
    {
        // Validate and store the user data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        // Validate and update the user data
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Here you would typically find the user by ID and update it
        // For demonstration, we will just return the updated data
        return response()->json(['message' => 'User updated successfully', 'data' => $data]);
    }

    public function destroy($id)
    {
        // Here you would typically find the user by ID and delete it
        // For demonstration, we will just return a success message
        return response()->json(['message' => 'User deleted successfully'], 204);
    }

    public function create()
    {
        // This method is typically not used in API controllers
        // as it is meant for rendering a view in web applications.
        // You can return a response or redirect if needed.
        return response()->json(['message' => 'Create form not available'], 405);
    }

    public function edit($id)
    {
        // This method is typically not used in API controllers
        // as it is meant for rendering a view in web applications.
        // You can return a response or redirect if needed.
        return response()->json(['message' => 'Edit form not available'], 405);
    }

    public function storeUser(Request $request)
    {
        // Validate and store the user data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Here you would typically create a user model instance and save it
        // For demonstration, we will just return the validated data
        return response()->json(['message' => 'User created successfully', 'data' => $data], 201);
    }

    public function updateUser(Request $request, $id)
    {
        // Validate and update the user data
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Here you would typically find the user by ID and update it
        // For demonstration, we will just return the updated data
        return response()->json(['message' => 'User updated successfully', 'data' => $data]);
    }

    public function deleteUser($id)
    {
        // Here you would typically find the user by ID and delete it
        // For demonstration, we will just return a success message
        return response()->json(['message' => 'User deleted successfully'], 204);
    }

}