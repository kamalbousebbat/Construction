<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'conf.php';

// Function to send JSON response
function sendJsonResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response = array_merge($response, $data);
    }
    
    echo json_encode($response);
    exit;
}

// Handle GET requests (Listing and Fetching)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // List all projects
    if (isset($_GET['action']) && $_GET['action'] === 'list') {
        $sql = "SELECT * FROM projects ORDER BY id DESC";
        $result = $conn->query($sql);
        
        if ($result) {
            $projects = [];
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
            
            sendJsonResponse(true, 'Liste des projets', ['projects' => $projects]);
        } else {
            sendJsonResponse(false, 'Erreur de récupération des projets');
        }
    }
    
    // Get specific project details
    elseif (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $project = $result->fetch_assoc();
            sendJsonResponse(true, 'Détails du projet', ['project' => $project]);
        } else {
            sendJsonResponse(false, 'Projet non trouvé');
        }
        $stmt->close();
    }
    else {
        sendJsonResponse(false, 'Action non reconnue');
    }
}

// Handle POST requests (Create, Update, Delete)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define upload directory
    $upload_dir = "uploads/";
    
    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Create a new project
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $requiredFields = ['title', 'description', 'project_date', 'status'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                sendJsonResponse(false, "Le champ $field est requis");
            }
        }
        
        // Process image upload if present
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Get file information
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            // Generate unique filename
            $unique_filename = uniqid() . '.' . $extension;
            $image_path = $upload_dir . $unique_filename;
            
            // Verify image type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowed_types)) {
                sendJsonResponse(false, "Erreur: Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
            }
            
            // Move uploaded file
            if (!move_uploaded_file($tmp_name, $image_path)) {
                sendJsonResponse(false, "Erreur lors de l'upload de l'image");
            }
        }
        
        // Sanitize inputs
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);
        $project_date = $conn->real_escape_string($_POST['project_date']);
        $status = $conn->real_escape_string($_POST['status']);
        
        // Create new project
        $sql = "INSERT INTO projects (title, image_url, description, project_date, category) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $title, $image_path, $description, $project_date, $status);
        
        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            sendJsonResponse(true, 'Projet ajouté avec succès', ['id' => $newId]);
        } else {
            sendJsonResponse(false, 'Erreur lors de l\'ajout du projet: ' . $stmt->error);
        }
        $stmt->close();
    }
    
    // Update an existing project
    elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
        if (empty($_POST['id'])) {
            sendJsonResponse(false, "ID du projet requis pour la mise à jour");
        }
        
        $id = intval($_POST['id']);
        
        $requiredFields = ['title', 'description', 'project_date', 'status'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                sendJsonResponse(false, "Le champ $field est requis");
            }
        }
        
        // Sanitize inputs
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);
        $project_date = $conn->real_escape_string($_POST['project_date']);
        $status = $conn->real_escape_string($_POST['status']);
        
        // Check if image is being updated
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Get file information
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            // Generate unique filename
            $unique_filename = uniqid() . '.' . $extension;
            $image_path = $upload_dir . $unique_filename;
            
            // Verify image type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowed_types)) {
                sendJsonResponse(false, "Erreur: Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
            }
            
            // Move uploaded file
            if (!move_uploaded_file($tmp_name, $image_path)) {
                sendJsonResponse(false, "Erreur lors de l'upload de l'image");
            }
            
            // Update with new image
            $sql = "UPDATE projects SET 
                    title = ?,
                    image_url = ?, 
                    description = ?, 
                    project_date = ?, 
                    category = ? 
                    WHERE id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $title, $image_path, $description, $project_date, $status, $id);
        } else {
            // Update without changing image
            $sql_get_image = "SELECT image_url FROM projects WHERE id = ?";
            $stmt_get_image = $conn->prepare($sql_get_image);
            $stmt_get_image->bind_param("i", $id);
            $stmt_get_image->execute();
            $result = $stmt_get_image->get_result();
            
            if ($result->num_rows > 0) {
                $project = $result->fetch_assoc();
                $image_path = $project['image_url']; // Use existing image path
                
                $sql = "UPDATE projects SET 
                        title = ?,
                        image_url = ?, 
                        description = ?, 
                        project_date = ?, 
                        category = ? 
                        WHERE id = ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $title, $image_path, $description, $project_date, $status, $id);
            } else {
                sendJsonResponse(false, "Projet non trouvé");
            }
            $stmt_get_image->close();
        }
        
        if ($stmt->execute()) {
            sendJsonResponse(true, 'Projet mis à jour avec succès', ['id' => $id]);
        } else {
            sendJsonResponse(false, 'Erreur de mise à jour du projet: ' . $stmt->error);
        }
        $stmt->close();
    }
    // Delete a project
    elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (!isset($_POST['id'])) {
            sendJsonResponse(false, 'ID du projet manquant');
        }
        
        $id = intval($_POST['id']);
        
        // Prepare delete statement
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                sendJsonResponse(true, 'Projet supprimé avec succès', ['id' => $id]);
            } else {
                sendJsonResponse(false, 'Aucun projet trouvé avec cet ID');
            }
        } else {
            sendJsonResponse(false, 'Erreur lors de la suppression: ' . $stmt->error);
        }
        $stmt->close();
    }
    else {
        sendJsonResponse(false, 'Action non reconnue');
    }
}
else {
    sendJsonResponse(false, 'Méthode non autorisée');
}

// Close database connection
$conn->close();