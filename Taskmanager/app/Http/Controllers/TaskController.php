<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
class TaskController extends Controller
{

    public function index()
    {
            try {
                $tasks = Task::all();
                return response()->json($tasks);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Tasks not found'], 404);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred while fetching tasks: ' . $e->getMessage()], 500);
            }
    }

    public function update(Request $request, $id)
    {
            try {
                \Log::info([$id]);
                \Log::info('Request Data:', $request->all());
                // Validate the incoming request data
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'completed' => 'string',
                    'assignee' => 'sometimes|required|string',  // Validate if present
                    'assigneeto' => 'sometimes|required|string', // Validate if present
                ]);

                \Log::info('Request Data $$validatedData:', [$validatedData] );
                // Find the task by ID
                $task = Task::where('id',$id)->first(); // This will throw a ModelNotFoundException if the task is not found
                // Update the task attributes
                \Log::info('Request Data $task:', [$task] );
                $task->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'completed' => $validatedData['completed'],
                    'assigned_to' => $validatedData['assigneeto'] ?? $validatedData['assignee'],
                ]);
                // Return a success response
                return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
            } catch (ModelNotFoundException $e) {
                // Handle the case where the task is not found
                return response()->json(['error' => 'Task not found'], 404);
            } catch (Exception $e) {
                // Handle any other exceptions
                return response()->json(['error' => 'An error occurred while updating the task: ' . $e->getMessage()], 500);
            }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id); 
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the task: ' . $e->getMessage()], 500);
        }
    }

        

    public function editeachtask($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json([
            'task' => $task
        ]);
    }


        
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'completed' => 'required|in:COMPLETED,INITIATED',
                'assignee' => 'nullable|exists:users,user_id', // Ensure the assignee exists in the users table
            ], [
                'title.required' => 'Please enter a title.',
                'description.required' => 'Please enter a description.',
                'completed.required' => 'Please select a completion status.',
                'completed.in' => 'Please select a valid completion status.',
                'assignee.exists' => 'Please select a valid user to assign the task.'
            ]);

            // Create the task
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'assigned_to' => $request->assignee,
                'completed' => $request->completed,
            ]);

            // Redirect with success message
            return redirect()->route('app')->with('success', 'Task created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating task: ' . $e->getMessage());
            return redirect()->route('app')->withErrors(['error' => 'An error occurred while creating the task. Please try again.']);
        }
    }


    public function fetchTasksByStatus(Request $request)
    {
            $user = auth()->user();
            $status = $request->input('status');
            // Determine which tasks to fetch based on user role and status
            if ($user->role === 'ADMIN') {
                $tasks = Task::where('completed', strtoupper($status))->get();
            } else {
                $tasks = Task::where('assigned_to', $user->id)
                            ->where('completed', strtoupper($status))
                            ->get();
            }
            return response()->json($tasks);
    }

    public function getTaskInfo()
    {
        $tasks = Task::all();
        return view('app', compact('tasks'));
    }

}
