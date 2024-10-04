<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->has('email') && $request->has('password')) {
            // Validate email/password login input
            $user = User::where('email', $request->email)->where('role','USER')->first();
            $userpassword = $user->password;
            \Log::info($userpassword);

            if ($user && Hash::check($request->password, $user->password)) {
                \Log::info("'success', 'Login successful!'");
                Auth::login($user);

                // Return JSON response for success
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'redirect_url' => url('app'), // Redirect URL to dashboard or intended page
                ]);
            }
            else{
                    return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                ], 401);
            }
        }
        \Log::info('Invalid email or password');
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.',
        ], 401);
    }

    public function Adminlogin(Request $request)
    {
        if ($request->has('email') && $request->has('password')) {
            $user = User::where('email', $request->email)->where('role','ADMIN')->first();
            $userpassword = $user->password;
            \Log::info($userpassword);

            if ($user && Hash::check($request->password, $user->password)) {
                \Log::info("'success', 'Login successful!'");
                Auth::login($user);
                // Return JSON response for success
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'redirect_url' => url('app'),
                ]);
            }
            else{
                    return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                ], 401);
            }
        }
        \Log::info('Invalid email or password');
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.',
        ], 401);
    }


    public function fetchTasksByStatus(Request $request)
    {
        
        $user = auth()->user();
        
        $status = $request->query('status');
        
        $tasksQuery = Task::with('user'); // Eager load the user relationship
        \Log::info("taskquery",[$tasksQuery]);
        
        if ($user->role === 'ADMIN') {
            // Fetch tasks for admin based on the specified status
            if ($status === 'COMPLETED') {
                $tasksQuery->where('completed', 'COMPLETED');
            } elseif ($status === 'INPROGRESS') {
                $tasksQuery->where('completed', 'INPROGRESS'); // Assuming 'status' column has these values
            } elseif ($status === 'INITIATED') {
                $tasksQuery->where('status', 'INITIATED');
            }
        } else {
            // For regular users, fetch tasks assigned to them
            $tasksQuery->where('assigned_to', $user->user_id); // Ensure only tasks assigned to the user are fetched
            if ($status === 'COMPLETED') {
                $tasksQuery->where('completed', 'COMPLETED');
            } elseif ($status === 'INPROGRESS') {
                $tasksQuery->where('completed', 'INPROGRESS');
            } elseif ($status === 'INITIATED') {
                $tasksQuery->where('status', 'INITIATED');
            }
        }
        // Execute the query and get the tasks
        $tasks = $tasksQuery->get();
        // Return the tasks as a JSON response
        return response()->json($tasks);
    }

    public function showDashboard()
    {
            // Fetch the authenticated user
            $user = auth()->user();
            // Initialize task counts
            $totalTasks = 0;
            $completedTasks = 0;
            $inProgressTasks = 0;
            $initiatedTasks=[];
            // Check the user's role and fetch task counts accordingly
            if ($user->role === 'ADMIN') {
                // Fetch all tasks for admin
                $totalTasks = Task::count(); // Count of all tasks
                $initiatedTasks = Task::where('completed', 'INITIATED')->count();
                $completedTasks = Task::where('completed', 'COMPLETED')->count(); // Count of completed tasks
                $inProgressTasks = Task::where('completed', 'INPROGRESS')->count(); // Count of in-progress tasks
                // Fetch users with role 'USER'
                $users = User::where('role', 'USER')->get();
                $usercount=$users->count(); 
            } else {
                // Fetch tasks related to the logged-in user
                $totalTasks = Task::where('assigned_to', $user->user_id)->count(); // Count of tasks for the user
                $completedTasks = Task::where('assigned_to', $user->user_id)->where('completed', 'COMPLETED')->count(); // Count of completed tasks for user
                $inProgressTasks = Task::where('assigned_to', $user->user_id)->where('completed', 'INPROGRESS')->count(); // Count of in-progress tasks for user
                $initiatedTasks = Task::where('assigned_to', $user->user_id)->where('completed', 'INITIATED')->count();
                // No need to fetch users for regular users in this context
                $users = User::where('role', 'USER')->get();
                $usercount=$users->count();
                // $users = collect(); // Empty collection for regular users
            }
            // Pass user, task counts, and users to the view
            return view('app', compact('user', 'totalTasks', 'completedTasks', 'inProgressTasks','initiatedTasks', 'users','usercount'));
    }

    public function logout()
    {
        Auth::logout(); // Log the user out
        return redirect()->route('login'); // Redirect to the login page
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function ShowUserCreation()
    {
        return view('auth.usercreation');
    }

    public function register(Request $request)
    {
            $user = User::create([
                'name'=>$request->name,
                'email' => $request->email,
                'mobile'=>$request->mobile,
                'password' => bcrypt($request->password),
                'role'=>'ADMIN',
            ]);
            
            return redirect()->intended('login')->with('success', 'Login successful');
    }

    public function usercreation(Request $request)
    {
            $user = User::create([
                'name'=>$request->name,
                'email' => $request->email,
                'mobile'=>$request->mobile,
                'password' => bcrypt($request->password),
                'role'=>$request->role,
            ]);
        return redirect()->intended('app')->with('success', 'successful user created');
    }


    public function fetchUsers(Request $request)
    {
        $role = $request->query('role');
        $users = User::where('role', $role)->select('name', 'email', 'mobile', 'role','user_id')->get();
        return response()->json($users);
    }

    public function showUserforEdit($userId)
    {   
        $user = User::where('user_id',$userId)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['user' => $user]);
    }

    public function UsersforEdit(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|string|max:15',
            'role' => 'required|string',
        ]);
        // Find the user by ID
        $user = User::where('user_id',$id)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => $request->role,
        ]);
        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($userId)
    {
        // Find the user by their ID
        $user = User::where('user_id',$userId)->first();
        // If user is not found, return a 404 error
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // Soft delete all tasks assigned to this user
        Task::where('assigned_to', $userId)->delete();
        // Soft delete the user
        $user->delete();
        // Return success response
        return response()->json(['message' => 'User and related tasks soft deleted successfully.']);
    }

}

