<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
</head>
<body>

    <h1>Users List</h1>
    <a href="{{ route('users.create') }}" 
       style="padding: 10px 15px; background-color: green; color: white; text-decoration: none; border-radius: 5px;">
       Add User
    </a>
    <br>
    <br>
    
    @if ($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            

                            <!-- Delete Button -->
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="padding: 5px 10px; background-color: red; color: white; border: none; border-radius: 5px;">
                                        Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No users found.</p>
    @endif

</body>
</html>
