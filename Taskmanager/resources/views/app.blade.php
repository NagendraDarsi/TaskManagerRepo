<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskManagement')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Include your CSS -->

    <style>
        /* Basic layout styles for sidebar and topbar */
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
        }
        header {
            width: 100%;
            background-color: black;
            color: white;
            padding: 10px 20px;
        }
        #top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #top-bar img {
            height: 30px;
            width: 30px;
            margin-left: 10px;
        }

        .profile-image {
            margin-right: 20px;
        }

        #top-bar .profile-image {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            background-color: #ccc; /* Placeholder background for empty profile image */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px; /* Space between name and profile image */  
        }

        .container {
            display: flex;
            flex-grow: 1;
            flex-direction: row;
            overflow: hidden;
        }

        aside {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
            transition: width 0.3s ease;
        }

        aside ul {
            list-style-type: none;
            padding: 0;
        }

        aside ul li {
            margin: 15px 0;
        }

        aside ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        main {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px; /* Location of the box */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Max width for better appearance */
            border-radius: 5px; /* Rounded corners */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styles for cards */
        .card {
            background-color: #f4f4f4;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px; /* Space between cards */
            text-align: center; /* Center text in cards */
            transition: transform 0.2s; /* Animation effect */
        }

        .card:hover {
            transform: scale(1.02); /* Slightly enlarge card on hover */
        }

        .card h3 {
            margin: 0 0 10px; /* Space below heading */
            font-size: 1.5rem; /* Font size for heading */
        }

        .card p {
            font-size: 1.2rem; /* Font size for count */
            color: #007bff; /* Color for count */
        }

        /* Styles for form elements */
        .form-group {
            margin-bottom: 15px; /* Space between form groups */
        }

        .form-group label {
            display: block; /* Makes the label a block element */
            margin-bottom: 5px; /* Space between label and input */
            font-weight: bold; /* Optional: make the label bold */
        }

        .form-group input,
        .form-group select {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Padding inside the input */
            border: 1px solid #ccc; /* Border for the input */
            border-radius: 5px; /* Rounded corners */
            box-sizing: border-box; /* Includes padding and border in width */
        }

        .btn-primary {
            background-color: #007bff; /* Bootstrap primary button color */
            color: white; /* Text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
        }
        .btn-danger {
            background-color: red; /* Bootstrap primary button color */
            color: white; /* Text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Styles for task management */
        #taskManagement {
            display: none; /* Hidden by default */
        }

        #taskManagement h2 {
            margin: 20px 0;
            font-size: 2rem; /* Font size for main heading */
        }

        #taskManagement h3 {
            font-size: 1.5rem; /* Font size for subheadings */
            margin: 10px 0;
        }

         .summary-card {
            margin-bottom: 20px;
        }
        .card {
            background-color: #f0f0f0;
            padding: 15px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .task-container {
            padding-left: 20px;
            display: none; /* Hidden by default */
        }
        .task-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .task-card h4 {
            margin-bottom: 5px;
        }
        .navBtn.active {
            background-color: blue; 
            color: white;
        }

    </style>
</head>
    <body>
        <header>
            <div id="top-bar">
                <div><span style="margin-right: 5px;">Welcome, {{ Auth::user()->name }}</span></div>
                <div style="display: flex; align-items: center; justify-content: flex-end;">
                    <div>
                        <!-- Message and Notification logos -->
                        <img src="{{ asset('icons/messagesicon.png') }}" alt="Messages">
                        <img src="{{ asset('icons/notifyicon.png') }}" alt="Notifications">
                    </div>
                    <!-- Display profile image and user's name -->
                    @if (Auth::check())
                        <div class="profile-image">
                            <!-- Placeholder for user's profile image -->
                            <img src="{{ asset('icons/profileicon.jpeg') }}" alt="Profile" class="profile-image">
                        </div>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                </div>
            </div>
        </header>

        <div class="container">
            <aside>
                <ul>
                    <li><a href="#" id="tasksBtn" class="navBtn">Dashboard</a></li>
                    <li><a href="#" id="defaultBtn" class="navBtn">Tasks</a></li>
                    <li>@if(auth()->user()->role === 'ADMIN')<a href="#" id="createUserBtn" class="navBtn" >Create User</a>@endif</li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class='btn-danger'>Logout</button>
                        </form>
                    </li>      
                </ul>
            </aside>
                    

                <!-- Content of the page -->
            <main>
                @yield('content')
                    <div id="defaultContent">
                            <h2>Manage Tasks</h2>
                            @if(auth()->user()->role === 'ADMIN')
                                <button id="addTaskButton" class='btn-primary'>Add Task</button>
                            @endif
                        <div class="summary-card">
                            <div class="card" onclick="fetchTasks('all')">
                                <h3>Total Tasks</h3>
                                <p>{{ $totalTasks }}</p>
                            </div>
                            <div id="allTasks" class="task-container" style="display: none;"></div>
                        </div>
                    
                        <div class="summary-card">
                            <div class="card" onclick="fetchTasks('COMPLETED')">
                                <h3>Completed Tasks</h3>
                                <p>{{ $completedTasks }}</p>
                            </div>
                            <div id="completedTasks" class="task-container" style="display: none;"></div>
                        </div>

                        <div class="summary-card">
                            <div class="card" onclick="fetchTasks('INPROGRESS')">
                                <h3>In Progress Tasks</h3>
                                <p>{{ $inProgressTasks }}</p>
                            </div>  
                            <div id="inProgressTasks" class="task-container" style="display: none;"></div>
                        </div>
                    </div>

                    <div id="taskManagement">
                        @if(auth()->user()->role === 'USER')
                            <h3>My Tasks</h3>
                                <!-- Here you can dynamically load the user's tasks -->
                            <div class="card">
                                <h3>Created Tasks</h3>
                                <p>{{ $initiatedTasks  }}</p>
                            </div>        
                                
                            <div class="card">
                                    <h3>Inprogress Tasks</h3>
                                    <p>{{ $inProgressTasks }}</p>
                            </div>
                            <!-- <p>No tasks assigned yet.</p> Example content -->
                        @endif
                        @if(auth()->user()->role === 'ADMIN')
                            <div class="summary-card">
                                <div class="card" onclick="fetchUsers('USER')">
                                    <h3>Total Users</h3>
                                    <p>{{ $usercount }}</p>
                                </div>
                                <div id="UsersDisplay" class="task-container" style="display: none;"></div>
                            </div>
                        @endif
                        <h3>Total Tasks</h3>
                        <div class="card">
                                <h3>Total Tasks</h3>
                                <p>{{ $totalTasks }}</p>
                        </div>
                        <div class="card">
                            <h3>Completed Tasks</h3> 
                            <p>{{ $completedTasks }}</p>
                        </div>
                    </div>
                
                    <!--User Creation Modal -->
                    <div id="userCreationModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="closeModal">&times;</span>
                            <h2>Create User</h2>
                            <form method="POST" action="{{ route('usercreation') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile Number:</label>
                                    <input type="text" id="mobile" name="mobile" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender:</label>
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role:</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="ADMIN">Admin</option>
                                        <option value="USER">User</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>   

                    <div id="TaskCreation" class="modal">
                        <div class="modal-content">
                        <span class="close" id="closeTaskModal">&times;</span>
                        <h2>Create Task</h2>
                        <form method="POST" action="{{ route('taskcreation.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title" required class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" required class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="completed">Completed:</label>
                                <select id="completed" name="completed" class="form-control @error('completed') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="COMPLETED" {{ old('completed') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                                    <option value="INITIATED" {{ old('completed') == 'INITIATED' ? 'selected' : '' }}>INITIATED</option>
                                </select>
                                @error('completed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="assignee">Assigning To:</label>
                                <select id="assignee" name="assignee" class="form-control @error('assignee') is-invalid @enderror">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}" {{ old('assignee') == $user->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assignee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create Task</button>
                        </form>
                    </div>
                </div>
                    <!-- Edit Task Modal -->
                <div id="editTaskModal" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeEditTaskModal">&times;</span>
                        <h2>Edit Task</h2>
                        <form id="editTaskForm" method="POST" action="">
                            @csrf
                            <input type="hidden" id="editTaskId" name="id">
                            <div class="form-group">
                                <label for="editTitle">Title:</label>
                                <input type="text" id="editTitle" name="title" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editDescription">Description:</label>
                                <textarea id="editDescription" name="description" required class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editCompleted">Status:</label>
                                <select id="editCompleted" name="completed" class="form-control" required>
                                    <option value="COMPLETED">Completed</option>
                                    <option value="INITIATED">Initiated</option>
                                    <option value="INPROGRESS">In Progress</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assigneeto">Assigning To:</label>
                                <select id="assigneeto" name="assignee" class="form-control">
                                    @foreach($users as $user)  
                                        <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                <div id="editUserModal" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeEditUserModal">&times;</span>
                        <h2>Edit User</h2>
                        <form id="editUserForm" method="POST" action="">
                            @csrf
                            <input type="hidden" id="editUserId" name="id">
                            <div class="form-group">
                                <label for="editName">Name:</label>
                                <input type="text" id="editName" name="name" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editMobile">Mobile:</label>
                                <input id="editMobile" name="mobile" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editEmail">Email:</label>
                                <input id="editEmail" name="email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editRole">Role:</label>
                                <select id="editRole" name="role" class="form-control" required>
                                    <option value="ADMIN">ADMIN</option>
                                    <option value="USER">USER</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                <script>
                    // Show user creation modal when the button is clicked
                        var createUserBtn=document.getElementById('createUserBtn');
                            if(createUserBtn){
                            createUserBtn.onclick = function() {
                                document.getElementById('userCreationModal').style.display = 'block';
                            }
                            
                        }
                            // Check if the Add Task button exists
                        var addTaskButton = document.getElementById('addTaskButton');
                        if (addTaskButton) {
                            addTaskButton.onclick = function() {
                                document.getElementById('TaskCreation').style.display = 'block';
                            }
                        }

                            document.getElementById('closeTaskModal').onclick = function() {
                                document.getElementById('TaskCreation').style.display = 'none';
                            }
                            // Close the modal when the close button is clicked
                            document.getElementById('closeModal').onclick = function() {
                                document.getElementById('userCreationModal').style.display = 'none';
                            }

                            // Close the modal when clicking outside the modal
                            var TaskCreation=document.getElementById('TaskCreation');
                            if(TaskCreation){
                            window.onclick = function(event) {
                                const modal = document.getElementById('TaskCreation');
                                if (event.target == modal) {
                                    modal.style.display = 'none';
                                }
                            }
                        }
                            var userCreationModal=document.getElementById('userCreationModal');
                            if(userCreationModal){
                            window.onclick = function(event) {
                                const modal = document.getElementById('userCreationModal');
                                if (event.target == modal) {
                                    modal.style.display = 'none';
                                }
                            }
                        }

                            document.getElementById('tasksBtn').onclick = function() {
                                // Hide the default content
                                document.getElementById('defaultContent').style.display = 'none';
                                // Show the task management section
                                document.getElementById('taskManagement').style.display = 'block';
                            }

                        document.getElementById('defaultBtn').onclick = function () {
                        document.getElementById('taskManagement').style.display = 'none'; // Hide task management
                        document.getElementById('defaultContent').style.display = 'block'; // Show default content
                    }

                    function fetchUsers(role) {
                        fetch('/fetch-users?role=' + role, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if necessary
                            }
                        })
                        .then(response => {
                            console.log("Response status:", response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log("Data on fetch users:", data);
                            const container = document.getElementById('UsersDisplay');
                            // Clear previous users
                            container.innerHTML = '';
                            console.log("Number of users fetched:", data.length);
                            // const userRole = '{{ auth()->user()->role }}';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    let userCard = document.createElement('div');
                                    userCard.className = 'task-card';
                                    userCard.innerHTML = `
                                        <h4>${user.name}</h4>
                                        <p><strong>Email:</strong> ${user.email}</p>
                                        <p><strong>Mobile:</strong> ${user.mobile}</p>
                                        <p><strong>Role:</strong> ${user.role}</p>
                                    `;
                                    // Add action buttons if needed
                                    userCard.innerHTML += `
                                        <button onclick="editUser('${user.user_id}')" class="btn btn-primary">Edit</button>
                                        <button onclick="deleteUser('${user.user_id}')" class="btn btn-danger">Delete</button>
                                    `;
                                    container.appendChild(userCard);
                                });
                            } else {
                                container.innerHTML = '<p>No users found.</p>';
                            }

                            // Hide all other task containers if needed
                            document.querySelectorAll('.task-container').forEach((c) => c.style.display = 'none');

                            // Show the UsersDisplay container
                            container.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching users:', error);
                            alert('An error occurred while fetching users. Please try again.');
                        });
                    }

                    function editUser(userId) {
                        console.log("Editing user with ID:", userId);
                        // console.log("Editing user with ID:", $userId);
                        fetch('/users/'+ userId, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const user = data.user; // Adjust according to your API response structure
                            if (user && user.user_id) {
                                document.getElementById('editUserId').value = user.user_id;
                                document.getElementById('editName').value = user.name || '';
                                document.getElementById('editEmail').value = user.email || '';
                                document.getElementById('editMobile').value = user.mobile || '';
                                document.getElementById('editRole').value = user.role || '';
                                document.getElementById('editUserForm').action = `/users/${user.user_id}`; // Update action URL
                                document.getElementById('editUserModal').style.display = 'block'; // Show the modal
                            } else {
                                console.error('Invalid user data:', user);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching user for edit:', error);
                            alert('An error occurred while fetching user details. Please try again.');
                        });
                    }

                    document.getElementById('editUserForm').addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent default form submission
                        submitUserForm(); // Call the function to submit via AJAX
                    });

                    function submitUserForm() {
                        const form = document.getElementById('editUserForm');
                        console.log("formaction",form.action);
                        const formData = new FormData();
                        const isAdmin = {{ Auth::user()->role === 'ADMIN' ? 'true' : 'false' }}; // Check if user is admin
                        // Always include the ID
                        formData.append('user_id', document.getElementById('editUserId').value);
                        // Include all fields in the payload
                        if (isAdmin) {
                        formData.append('name', document.getElementById('editName').value);
                        formData.append('email', document.getElementById('editEmail').value);
                        formData.append('mobile', document.getElementById('editMobile').value);
                        formData.append('role', document.getElementById('editRole').value);
                        }
                        // Include completed status for both roles
                        fetch(form.action, {
                            method: 'POST', // Use PUT since we're updating
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if necessary
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("User updated successfully:", data);
                            // Close the modal and refresh the task list or perform any other updates needed
                            document.getElementById('editUserModal').style.display = 'none';
                            fetchUsers('USER'); // Fetch tasks again to update the UI
                        })
                        .catch(error => {
                            console.error('Error updating task:', error);
                            alert('An error occurred while updating the task. Please try again.');
                        });
                    }

                    // Close modal functionality
                    document.getElementById('closeEditUserModal').onclick = function() {
                        document.getElementById('editUserModal').style.display = 'none';
                    };

                    window.onclick = function(event) {
                        const modal = document.getElementById('editUserModal');
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    };


                    function fetchTasks(status) {
                        fetch('/fetch-tasks?status=' + status, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if necessary
                            }
                        })
                        .then(response => {
                            console.log("Response status:", response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log("Data on fetch tasks:", data);
                            const userRole = '{{ auth()->user()->role }}';
                            let containerId = (status === 'all') ? 'allTasks' : (status === 'COMPLETED') ? 'completedTasks' : 'inProgressTasks';
                            let container = document.getElementById(containerId);
                            
                            container.innerHTML = '';
                            console.log("Number of tasks fetched:", data.length);

                            if (data.length > 0) {
                                data.forEach(task => {
                                    let taskCard = document.createElement('div');
                                    taskCard.className = 'task-card';
                                    taskCard.innerHTML = `
                                        <h4>${task.title}</h4>
                                        <p><strong>Description:</strong> ${task.description}</p>
                                        <p><strong>Email:</strong> ${task.assigned_to}</p>
                                        <p><strong>Status:</strong> ${task.completed}</p>
                                        <p><strong>Assigned To:</strong> ${task.user ? task.user.name : 'Unassigned'}</p>
                                    `;
                                    // Add action buttons
                                    taskCard.innerHTML += `
                                        <button onclick="editTask(${task.id})" class="btn btn-primary">Edit</button>
                                        ${userRole === 'ADMIN' ? `<button onclick="deleteTask(${task.id})" class="btn btn-danger">Delete</button>` : ''}
                                    `;

                                    container.appendChild(taskCard);
                                });
                            } else {
                                container.innerHTML = '<p>No tasks found.</p>';
                            }
                            document.querySelectorAll('.task-container').forEach((c) => c.style.display = 'none');
                            container.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching tasks:', error);
                            alert('An error occurred while fetching tasks. Please try again.');
                        });
                    }

                    // Function to edit a task
                    function editTask(taskId) {
                        console.log("Editing task with ID:", taskId);
                        fetch('/tasks/' + taskId, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const task = data.task; // Access the task object
                            if (task && task.id) {
                                document.getElementById('editTaskId').value = task.id;
                                document.getElementById('editTitle').value = task.title || '';
                                document.getElementById('editDescription').value = task.description || '';
                                document.getElementById('editCompleted').value = task.completed || '';
                                document.getElementById('assigneeto').value = task.assigned_to || '';
                                
                                // Set the form action for updating the task
                                document.getElementById('editTaskForm').action = `/tasks/${task.id}`;
                                // Check if the logged-in user is an admin
                                // Assuming you have a 'role' or similar column
                                const isAdmin = {{ Auth::user()->role === 'ADMIN' ? 'true' : 'false' }};

                                console.log("isadmin",isAdmin);
                                // Enable or disable fields based on user role
                                if (isAdmin) {
                                    // Admin can edit all fields
                                    document.getElementById('editTitle').disabled = false;
                                    document.getElementById('editDescription').disabled = false;
                                    document.getElementById('assigneeto').disabled = false;
                                    document.getElementById('editCompleted').disabled = false;
                                    //document.getElementById('deleteButton').style.display = 'inline-block'; // Show delete button for admin
                                } else {
                                    // Regular user can only edit the status
                                    document.getElementById('assigneeto').disabled = true;
                                    document.getElementById('editTitle').disabled = true;
                                    document.getElementById('editDescription').disabled = true;
                                    document.getElementById('editCompleted').disabled = false;
                                    // document.getElementById('deleteButton').style.display = 'none'; // Hide delete button for regular users
                                }
                                // Show the edit task modal after setting the values
                                document.getElementById('editTaskModal').style.display = 'block';
                            } else {
                                console.error('Invalid task data:', task);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching task for edit:', error);
                            alert('An error occurred while fetching task details. Please try again.');
                        });
                    }


                    // Prevent default form submission and handle with AJAX
                    document.getElementById('editTaskForm').addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent default form submission
                        submitForm(); // Call the function to submit via AJAX
                    });

                    // Function to submit the form via AJAX
                    function submitForm() {
                        const form = document.getElementById('editTaskForm');
                        const formData = new FormData();

                        const isAdmin = {{ Auth::user()->role === 'ADMIN' ? 'true' : 'false' }}; // Check if user is admin
                        // Always include the ID
                        formData.append('id', document.getElementById('editTaskId').value);

                        // Include all fields in the payload
                        if (isAdmin) {
                        formData.append('title', document.getElementById('editTitle').value);
                        formData.append('description', document.getElementById('editDescription').value);
                        formData.append('assigneeto', document.getElementById('assigneeto').value);
                        }
                        // Include completed status for both roles
                        formData.append('completed', document.getElementById('editCompleted').value);
                        // Handle cases for regular users to ensure they can only modify status
                        if (!isAdmin) {
                            // If the user is not an admin, send the current values for title, description, and assignee
                            // These values are taken from the task details fetched earlier
                            formData.append('title', document.getElementById('editTitle').value);
                            formData.append('description', document.getElementById('editDescription').value);
                            formData.append('assigneeto', document.getElementById('assigneeto').value);
                        }

                        fetch(form.action, {
                            method: 'POST', // Use PUT since we're updating
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if necessary
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Task updated successfully:", data);
                            // Close the modal and refresh the task list or perform any other updates needed
                            document.getElementById('editTaskModal').style.display = 'none';
                            fetchTasks('all'); // Fetch tasks again to update the UI
                        })
                        .catch(error => {
                            console.error('Error updating task:', error);
                            alert('An error occurred while updating the task. Please try again.');
                        });
                    }


                    function deleteUser(userId) {
                        console.log("userId",[userId]);
                        if (confirm("Are you sure you want to delete this task?")) {
                            fetch(`/users/${userId}`, {
                                method: 'DELETE', // Use DELETE method
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                                    'Content-Type': 'application/json' // Optional, depending on server setup
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log("user deleted successfully:", data);
                                fetchTasks('USER'); // Refresh the task list or update the UI as needed
                            })
                            .catch(error => {
                                console.error('Error deleting task:', error);
                                alert('An error occurred while deleting the task. Please try again.');
                            });
                        }
                    }

                    function deleteTask(taskId) {
                        if (confirm("Are you sure you want to delete this task?")) {
                            fetch(`/tasks/${taskId}`, {
                                method: 'DELETE', // Use DELETE method
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                                    'Content-Type': 'application/json' // Optional, depending on server setup
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log("Task deleted successfully:", data);
                                fetchTasks('all'); // Refresh the task list or update the UI as needed
                            })
                            .catch(error => {
                                console.error('Error deleting task:', error);
                                alert('An error occurred while deleting the task. Please try again.');
                            });
                        }
                    }


                    // Close the edit task modal
                    document.getElementById('closeEditTaskModal').onclick = function() {
                        document.getElementById('editTaskModal').style.display = 'none';
                    }

                    // Close the modal when clicking outside the modal
                    window.onclick = function(event) {
                        const modal = document.getElementById('editTaskModal');
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    }     

                    const navButtons = document.querySelectorAll('.navBtn');
                    navButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            // Remove the 'active' class from all buttons
                            navButtons.forEach(b => b.classList.remove('active'));
                            // Add the 'active' class to the clicked button
                            this.classList.add('active');
                        });
                    });

                </script>
            </main>
        </div>
        <footer>
            <p>© {{ date('Y') }} Your Application</p>
        </footer>
    </body>
</html>
