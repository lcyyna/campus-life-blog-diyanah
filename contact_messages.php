<?php
session_start();
require 'dbconnect.php';
// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle update request
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $stmt = $pdo->prepare("UPDATE contacts SET name = :name, email = :email, message = :message WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<p>Contact updated successfully!</p>";
    }

    // Handle delete request
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<p>Contact deleted successfully!</p>";
    }

    // Fetch contacts
    $stmt = $pdo->query("SELECT * FROM contacts");
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 0.5rem;
            text-align: left;
        }
        .update-form, .delete-form {
            display: inline-block;
            margin: 0.5rem;
        }
        .update-form input, .update-form textarea {
            display: block;
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
    <h1>Manage Contacts</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo htmlspecialchars($contact['id']); ?></td>
                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                <td><?php echo htmlspecialchars($contact['message']); ?></td>
                <td>
                    <form class="update-form" method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($contact['id']); ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                        <textarea name="message" rows="4" required><?php echo htmlspecialchars($contact['message']); ?></textarea>
                        <button type="submit" name="update">Update</button>
                    </form>
                    <form class="delete-form" method="get" action="">
                        <input type="hidden" name="delete" value="<?php echo htmlspecialchars($contact['id']); ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
