<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Projets - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        body::before {
            display: var(--display-after, none);
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px);
            background: rgba(0, 0, 0, 0.5);
            z-index: var(--index-after, 0);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: orange;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: darkorange;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Projets</h1>
        
        <button id="add-project-btn" class="btn">Ajouter un Projet</button>
        
        <div class="table-responsive">
            <table id="projects-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projects-list">
                    <!-- Projects will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal pour ajouter/modifier un projet -->
    <div id="project-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modal-title">Ajouter un Projet</h2>
            <form id="project-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="project-id" name="id">
                
                <div class="form-group">
                    <label for="title">Titre du Projet</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="project-date">Date</label>
                    <input type="date" id="project-date" name="project_date" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Statut</label>
                    <select id="status" name="status" required>
                        <option value="en cours">En Cours</option>
                        <option value="terminé">Terminé</option>
                        <option value="en attente">En Attente</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Enregistrer</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectsList = document.getElementById('projects-list');
        const addProjectBtn = document.getElementById('add-project-btn');
        const projectModal = document.getElementById('project-modal');
        const closeModalBtn = document.querySelector('.close-btn');
        const projectForm = document.getElementById('project-form');
        const modalTitle = document.getElementById('modal-title');

        // Charger les projets
        function loadProjects() {
            fetch('api.php?action=list')
                .then(response => response.json())
                .then(data => {
                    projectsList.innerHTML = ''; // Vider la liste actuelle
                    if (data.success) {
                        data.projects.forEach(project => {
                            const row = `
                                <tr data-id="${project.id}">
                                    <td>${project.id}</td>
                                    <td>${project.title}</td>
                                    <td><img src="${project.image_url}" alt="${project.title}" width="50"></td>
                                    <td>${project.description}</td>
                                    <td>${project.project_date}</td>
                                    <td>${project.category}</td>
                                    <td>
                                        <button class="btn edit-btn">Modifier</button>
                                        <button class="btn delete-btn">Supprimer</button>
                                    </td>
                                </tr>
                            `;
                            projectsList.innerHTML += row;
                        });
                        attachRowListeners();
                    } else {
                        console.error('Erreur de chargement des projets:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }

        // Ouvrir le modal pour ajouter un projet
        addProjectBtn.addEventListener('click', function() {
            modalTitle.textContent = 'Ajouter un Projet';
            projectForm.reset();
            document.getElementById('project-id').value = '';
            projectModal.style.display = 'block';
        });

        // Fermer le modal
        closeModalBtn.addEventListener('click', function() {
            projectModal.style.display = 'none';
        });

        // Soumettre le formulaire
        projectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const imageInput = document.getElementById('image');
            if (imageInput.files.length > 0) {
                // L'image est déjà incluse dans FormData grâce à new FormData(this)
                console.log("Image sélectionnée:", imageInput.files[0].name);
            } else if (formData.get('action') === 'create') {
                // Si c'est une création et qu'il n'y a pas d'image, alerter l'utilisateur
                alert('Veuillez sélectionner une image');
                return;
            }
            
            const action = formData.get('id') ? 'update' : 'create';
            formData.append('action', action);

            fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(action === 'create' ? 'Projet ajouté' : 'Projet mis à jour');
                    projectModal.style.display = 'none';
                    loadProjects();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        });

        // Attacher des écouteurs aux boutons de ligne
        function attachRowListeners() {
            // Boutons de suppression
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const projectId = row.dataset.id;
                    
                    if (confirm('Voulez-vous vraiment supprimer ce projet ?')) {
                        fetch('api.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `action=delete&id=${projectId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                                alert('Projet supprimé');
                            } else {
                                alert('Erreur: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                        });
                    }
                });
            });

            // Boutons de modification
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const projectId = row.dataset.id;
                    alert("we are good");
                    
                    // Charger les détails du projet pour modification
                    fetch(`api.php?action=get&id=${projectId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const project = data.project;
                                //  projectModal.style.display = 'block';
                                
                                // Remplir le formulaire
                                document.getElementById('project-id').value = project.id;
                                document.getElementById('title').value = project.title;
                                if (document.getElementById("image").hasAttribute('required')) {
                                    document.getElementById("image").removeAttribute('required');
                                    console.log('required a été annulé.');
                                }
                                document.getElementById('description').value = project.description;
                                document.getElementById('project-date').value = project.project_date;
                                document.getElementById('status').value = project.category;
                                
                                // Changer le titre du modal
                                modalTitle.textContent = 'Modifier le Projet';
                                
                                // Afficher le modal
                                projectModal.style.display = 'block';
                            } else {
                                alert('Erreur de chargement du projet');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                        });
                });
            });
        }

        // Charger les projets au démarrage
        loadProjects();
    });
    </script>
</body>
</html>
