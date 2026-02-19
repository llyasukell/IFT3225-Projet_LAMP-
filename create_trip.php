<?php
session_start();
require_once "config.php";

# Vérification de l'authentification
if(!isset($_SESSION['user_id'])){
    header("Location: connexion.php");
    exit();
}

# Traitement du formulaire de création de tuile
if($_SERVER['REQUEST_METHOD']==='POST'){
    $user_id=$_SESSION['user_id'];
    $title=trim($_POST['title']);
    $location=trim($_POST['location']);
    $region=$_POST['region'];
    $travel_date=$_POST['travel_date'];
    $description=trim($_POST['description']);

    # Gestion des erreurs
    $errors=[];
    if(empty($title)){$errors[]="Le titre est obligatoire.";}
    if(empty($location)){$errors[]="Le pays est obligatoire.";}
    if(empty($region)){$errors[]="La région doit être sélectionnée.";}
    if(empty($travel_date)){$errors[]="La date de voyage est obligatoire.";}

    if(!empty($errors)){
        $_SESSION['trip_errors']=$errors;
        $_SESSION['old_data']=$_POST; // pour pré-remplir le formulaire
        header("Location: PageCreationTuile.php");
        exit();
    }

    $image_path=""; 
    # Gestion du téléchargement de l'image    
    if(isset($_FILES['image']) && $_FILES['image']['error']===0){
        $dossier_cible="uploads/";
        if(!is_dir($dossier_cible)){mkdir($dossier_cible,0777,true);}
        $nom_fichier=time()."_".basename($_FILES["image"]["name"]);
        $chemin_complet=$dossier_cible.$nom_fichier;
        if(move_uploaded_file($_FILES["image"]["tmp_name"],$chemin_complet)){
            $image_path=$nom_fichier; 
        }else{
            $errors[]="Erreur lors du téléchargement de l'image.";
            $_SESSION['trip_errors']=$errors;
            $_SESSION['old_data']=$_POST;
            header("Location: PageCreationTuile.php");
            exit();
        }
    }

    # Insertion des données dans la base de données
    $stmt=$conn->prepare("INSERT INTO trips (user_id,title,location,region,travel_date,description,image_path) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("issssss",$user_id,$title,$location,$region,$travel_date,$description,$image_path);

    if($stmt->execute()){

        $trip_id = $conn->insert_id;

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

        header("Location: MenuApresCo.php"); 
        exit();
    }else{
        $_SESSION['trip_errors']=["Erreur lors de la création de la tuile : ".$conn->error];
        $_SESSION['old_data']=$_POST;
        header("Location: PageCreationTuile.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>