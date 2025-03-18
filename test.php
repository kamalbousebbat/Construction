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
    if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
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
    }
}

// Handle POST requests (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update a project
    if (isset($_POST['action']) && ($_POST['action'] === 'create' || $_POST['action'] === 'update')) {

        // Vérifier si un fichier a été uploadé
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/"; // Dossier où seront stockées les images
        }   
        // Création du dossier d'upload s'il n'existe pas
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Récupérer les informations du fichier
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
        // Générer un nom de fichier unique
        $unique_filename = uniqid() . '.' . $extension;
        $image_path = $upload_dir . $unique_filename;
        
        // Vérifier que c'est bien une image
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowed_types)) {
            $reponse['message'] = "Erreur: Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            echo json_encode($reponse);
            exit;
        }
        if (move_uploaded_file($tmp_name, $image_path)){

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

            // Create new project
            if ($_POST['action'] === 'create') {
                $sql = "INSERT INTO projects (title,image_url, description, project_date, category) 
                        VALUES (?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $title,$image_path, $description, $project_date, $status);

                if ($stmt->execute()) {
                    $newId = $conn->insert_id;
                    sendJsonResponse(true, 'Projet ajouté avec succès', ['id' => $newId]);
                } else {
                    sendJsonResponse(false, 'Erreur lors de l\'ajout du projet: ' . $stmt->error);
                }
                $stmt->close();
            }
            
            // Update existing project
            if ($_POST['action'] === 'update') {
                if (empty($_POST['id'])) {
                    sendJsonResponse(false, "ID du projet requis pour la mise à jour");
                }

                $id = intval($_POST['id']);

                // $sql = "UPDATE projects SET 
                //         title = ?,
                //         image_url=?, 
                //         description = ?, 
                //         project_date = ?, 
                //         category = ? 
                //         WHERE id = ?";
                
                //     $stmt = $conn->prepare($sql);
                //     $stmt->bind_param("sssssi", $title,$image_path, $description, $project_date, $status, $id);
                
                if(empty($_FILES['image'])){
                    $sql = "UPDATE projects SET 
                        title = ?,
                        description = ?, 
                        project_date = ?, 
                        category = ? 
                        WHERE id = ?";
                        // echo "<script>alert('Ceci est une alerte générée depuis PHP !');</script>";
                
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssi", $title, $description, $project_date, $status, $id);
                }else{
                    $sql = "UPDATE projects SET 
                        title = ?,
                        image_url=?, 
                        description = ?, 
                        project_date = ?, 
                        category = ? 
                        WHERE id = ?";
                        // echo "<script>alert('Ceci est une alerte générée depuis PHP !');</script>";
                
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssi", $title, $image_path, $description, $project_date, $status, $id);
                }
                

                if ($stmt->execute()) {
                    sendJsonResponse(true, 'Projet mis à jour avec succès', ['id' => $id]);
                } else {
                    sendJsonResponse(false, 'Erreur de mise à jour du projet: ' . $stmt->error);
                }
                $stmt->close();
            }

        }
    }
    

    // Delete a project
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
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
}

// Unrecognized action
// sendJsonResponse(false, 'Action non reconnue');

// Close database connection
$conn->close();