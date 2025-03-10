<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loan_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Préparer et lier
$stmt = $conn->prepare("INSERT INTO loan_requests (nom, prenom, age, profession, contact, revenu, addresse, situation, enfants, montant, date_pret, raison, langue, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssissssssiss", $nom, $prenom, $age, $profession, $contact, $revenu, $addresse, $situation, $enfants, $montant, $date_pret, $raison, $langue);

// Définir les paramètres et exécuter
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$age = $_POST['age'];
$profession = $_POST['profession'];
$contact = $_POST['contact'];
$revenu = $_POST['revenu'];
$addresse = $_POST['addresse'];
$situation = $_POST['situation'];
$enfants = $_POST['enfants'];
$montant = $_POST['amount'];
$date_pret = $_POST['date'];
$raison = $_POST['raison'];
$langue = $_POST['langue']; // Ajouter la langue sélectionnée
$stmt->execute();

$stmt->close();
$conn->close();

echo "Formulaire soumis avec succès!";
?>
