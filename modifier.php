<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo json_encode(["status" => "error", "message" => "Non connecté"]);
        exit();
    }
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

# TRAITEMENT DE LA MODIFICATION (APPEL ASYNCHRONE FETCH) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $trip_id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $region = $_POST['region'];

    $update_sql = "UPDATE trips SET title = ?, location = ?, region = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssii", $title, $location, $region, $trip_id, $user_id);
    
    if ($update_stmt->execute()) {
        
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur SQL"]);
    }
    exit(); 
}


if (!isset($_GET['id'])) {
    die("ID manquant.");
}

$trip_id = $_GET['id']; 

$sql = "SELECT * FROM trips WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $trip_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Voyage introuvable ou non autorisé.");
}
$voyage = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le voyage</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="page-connexion">
    <div class="tuile-creation">
      <h2>Modifier le voyage</h2>

      <form id="formModifier" method="post">
        <input type="hidden" name="id" value="<?php echo $trip_id; ?>">
        
        <label>Titre</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($voyage['title']); ?>" required>

        <label>Pays / Lieu</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($voyage['location']); ?>" required>

        <label>Région</label>
        <select name="region">
            <option value="Europe" <?php if($voyage['region'] == 'Europe') echo 'selected'; ?>>Europe</option>
            <option value="Asie" <?php if($voyage['region'] == 'Asie') echo 'selected'; ?>>Asie</option>
            <option value="Amérique" <?php if($voyage['region'] == 'Amérique') echo 'selected'; ?>>Amérique</option>
            <option value="Afrique" <?php if($voyage['region'] == 'Afrique') echo 'selected'; ?>>Afrique</option>
        </select>

        <button type="submit" style="margin-top: 15px; width: 100%;">Enregistrer les modifications</button>
      </form>
    </div>
  </div>

  <script>
    
    document.getElementById('formModifier').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        const formData = new FormData(this);

        fetch('modifier.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Modification réussie !');
                window.location.href = 'MesVoyages.php'; 
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
  </script>
</body>
</html>