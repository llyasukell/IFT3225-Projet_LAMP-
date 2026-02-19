<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') exit(json_encode(["status" => "error"]));
    header("Location: connexion.php"); exit();
}

$user_id = $_SESSION['user_id'];

// ACTION : SUPPRIMER UNE PHOTO
if (isset($_POST['action']) && $_POST['action'] === 'delete_photo') {
    $p_id = $_POST['photo_id'];
    $check = $conn->prepare("SELECT p.photo_path FROM trip_photos p JOIN trips t ON p.trip_id = t.id WHERE p.id = ? AND t.user_id = ?");
    $check->bind_param("ii", $p_id, $user_id);
    $check->execute();
    $res = $check->get_result();
    if ($row = $res->fetch_assoc()) {
        if(file_exists("uploads/".$row['photo_path'])) unlink("uploads/".$row['photo_path']);
        $del = $conn->prepare("DELETE FROM trip_photos WHERE id = ?");
        $del->bind_param("i", $p_id);
        $del->execute();
        echo json_encode(["status" => "success"]);
    }
    exit();
}

// ACTION : MODIFIER INFOS ET AJOUTER PHOTOS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $trip_id = $_POST['id'];
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $region = $_POST['region'];

    $stmt = $conn->prepare("UPDATE trips SET title=?, location=?, region=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssii", $title, $location, $region, $trip_id, $user_id);
    
    if ($stmt->execute()) {
        if (!empty($_FILES['trip_photos']['name'][0])) {
            foreach ($_FILES['trip_photos']['tmp_name'] as $k => $tmp_name) {
                if ($_FILES['trip_photos']['error'][$k] === 0) {
                    $fname = time() . "_" . $_FILES['trip_photos']['name'][$k];
                    if (move_uploaded_file($tmp_name, "uploads/" . $fname)) {
                        $ins = $conn->prepare("INSERT INTO trip_photos (trip_id, photo_path) VALUES (?, ?)");
                        $ins->bind_param("is", $trip_id, $fname);
                        $ins->execute();
                    }
                }
            }
        }
        echo json_encode(["status" => "success"]);
    }
    exit(); 
}

$trip_id = $_GET['id']; 
$stmt = $conn->prepare("SELECT * FROM trips WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $trip_id, $user_id);
$stmt->execute();
$voyage = $stmt->get_result()->fetch_assoc();
if (!$voyage) die("Accès refusé.");

// Récupérer les photos actuelles
$photos_res = $conn->query("SELECT * FROM trip_photos WHERE trip_id = $trip_id");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <style>
    .galerie-edit { display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0; }
    .item-photo { position: relative; width: 80px; }
    .item-photo img { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
    .btn-del { position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; cursor: pointer; font-size: 10px; padding: 2px 5px; }
  </style>
</head>
<body>
  <div class="page-connexion">
    <div class="tuile-creation">
      <h2>Modifier le voyage</h2>
      <form id="formModifier" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $trip_id; ?>">
        <label>Titre</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($voyage['title']); ?>" required>
        <label>Pays / Lieu</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($voyage['location']); ?>" required>
        <label>Région</label>
        <select name="region">
            <option value="Europe" <?php if($voyage['region']=='Europe') echo 'selected'; ?>>Europe</option>
            <option value="Asie" <?php if($voyage['region']=='Asie') echo 'selected'; ?>>Asie</option>
            <option value="Amérique" <?php if($voyage['region']=='Amérique') echo 'selected'; ?>>Amérique</option>
            <option value="Afrique" <?php if($voyage['region']=='Afrique') echo 'selected'; ?>>Afrique</option>
        </select>

        <label>Photos actuelles (cliquez sur X pour supprimer)</label>
        <div class="galerie-edit">
            <?php while($p = $photos_res->fetch_assoc()): ?>
                <div class="item-photo" id="photo-<?php echo $p['id']; ?>">
                    <img src="uploads/<?php echo $p['photo_path']; ?>">
                    <button type="button" class="btn-del" onclick="supprimerPhoto(<?php echo $p['id']; ?>)">X</button>
                </div>
            <?php endwhile; ?>
        </div>

        <label>Ajouter des photos (plusieurs possibles)</label>
        <input type="file" name="trip_photos[]" accept="image/*" multiple>

        <button type="submit" style="margin-top: 15px; width: 100%;">Enregistrer</button>
      </form>
    </div>
  </div>

  <script>
    function supprimerPhoto(id) {
        if(!confirm("Supprimer cette photo ?")) return;
        const fd = new FormData();
        fd.append('action', 'delete_photo');
        fd.append('photo_id', id);
        fetch('modifier.php', { method: 'POST', body: fd })
        .then(r => r.json()).then(data => {
            if(data.status === 'success') document.getElementById('photo-'+id).remove();
        });
    }

    document.getElementById('formModifier').addEventListener('submit', function(e) {
        e.preventDefault(); 
        fetch('modifier.php', { method: 'POST', body: new FormData(this) })
        .then(r => r.json()).then(data => {
            if (data.status === 'success') { alert('Mis à jour !'); window.location.href = 'MesVoyages.php'; }
        });
    });
  </script>
</body>
</html>