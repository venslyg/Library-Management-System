<?php

class UserRepository
{
    private mysqli $db;

    public function __construct(mysqli $connection)
    {
        $this->db = $connection;
    }

    //function to add a user
    public function addUser(array $user): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, role) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $user['name'], $user['email'], $user['role']);
        return $stmt->execute();
    }

    //function to delete a user
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    //function to update a user
    public function updateUser(array $user): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?"
        );
        $stmt->bind_param("sssi", $user['name'], $user['email'], $user['role'], $user['id']);
        return $stmt->execute();
    }

    //function to view all users
    public function getAllUsers(): array
    {
        $result = $this->db->query("SELECT * FROM users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Example usage:
//database connection
$connection = new mysqli("localhost", "username", "password", "database");
//calling the repository
$repo = new UserRepository($connection);

$user = [
    'id' => 1,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'admin'
];

if ($repo->addUser($user)) {
    echo "User added successfully";
}
