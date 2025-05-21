<?php
// Ta clé API OpenWeatherMap
$apiKey = '5e611d4c7e6779351247eed784e77ac4';  // Remplace cette valeur par ta clé API réelle

// Liste des 100 villes marocaines et européennes pour lesquelles tu veux obtenir la météo

$villes = [
    'Dhaka',
    'Bruxelles',
    'Brasília',
    'Ottawa',
    'Santiago',
    'Pékin',
    'Bogotá',
    'Prague',
    'Copenhague',
    'Le Caire',
    'Addis-Abeba',
    'Helsinki',
    'Paris',
    'Berlin',
    'Athènes',
    'Budapest',
    'Reykjavík',
    'New Delhi',
    'Jakarta',
    'Téhéran',
    'Bagdad',
    'Dublin',
    'Jérusalem',
    'Rome',
    'Tokyo',
    'Amman',
    'Nairobi',
    'Kuala Lumpur',
    'Mexico',
    'Rabat',
    'Amsterdam',
    'Wellington',
    'Abuja',
    'Oslo',
    'Islamabad',
    'Lima',
    'Manille',
    'Varsovie',
    'Lisbonne',
    'Moscou',
    'Riyad',
    'Singapour',
    'Pretoria',
    'Séoul',
    'Madrid',
    'Stockholm',
    'Bangkok',
    'Vienne',
    'Tunis',
    'Hanoï',
    'Canberra'
];


// Trier les villes par ordre alphabétique
sort($villes);

// Par défaut, afficher la météo de la première ville dans la liste
$selectedVille = isset($_POST['ville']) ? $_POST['ville'] : $villes[0];

// Fonction pour récupérer la météo d'une ville
function getWeather($ville, $apiKey) {
    $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q=$ville&units=metric&lang=fr&appid=$apiKey";
    $response = @file_get_contents($weatherUrl);  // Utiliser le @ pour supprimer les warnings

    if ($response === FALSE) {
        return ['error' => 'Erreur lors de la récupération des données météo.'];
    }

    $weatherData = json_decode($response, true);
    if (isset($weatherData['cod']) && $weatherData['cod'] != 200) {
        return ['error' => 'Erreur avec l\'API ou ville introuvable.'];
    }

    $temperature = round($weatherData['main']['temp']);
    $humidity = $weatherData['main']['humidity'];
    $description = $weatherData['weather'][0]['description'];
    $iconCode = $weatherData['weather'][0]['icon'];  // Le code d'icône météo à utiliser pour l'affichage

    return [
        'temp' => $temperature,
        'humid' => $humidity,
        'desc' => $description,
        'icon' => $iconCode
    ];
}

// Récupérer les données météo pour la ville sélectionnée
$weather = getWeather($selectedVille, $apiKey);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo des Villes Marocaines et Européennes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #2c3e50; /* Bleu nuit */
        color: #ecf0f1;
    }

    .container {
        max-width: 800px;
        margin-top: 50px;
        background-color: #34495e; /* Gris bleu foncé */
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid #1abc9c;
    }

    h1 {
        text-align: center;
        font-size: 36px;
        margin-bottom: 25px;
        color: #f39c12; /* Orange clair */
    }

    .navbar {
        margin-bottom: 30px;
        background-color: #8e44ad;
    }

    .navbar-brand,
    .navbar-nav .nav-link {
        color: #fdfefe !important;
    }

    .navbar-nav .nav-link:hover {
        color: #f1c40f !important;
    }

    .form-label {
        font-size: 18px;
        color: #ecf0f1;
    }

    .form-select {
        font-size: 16px;
        background-color: #ecf0f1;
        color: #2c3e50;
    }

    .weather-city {
        text-align: center;
        margin-top: 30px;
    }

    .weather-city img {
        width: 100px;
        height: 100px;
    }

    .weather-city h3 {
        color: #e67e22;
        margin-bottom: 15px;
    }

    .weather-city p {
        font-size: 18px;
        margin: 6px 0;
    }

    .error {
        color: #e74c3c;
        font-size: 18px;
        text-align: center;
    }

    .btn {
        background-color: #9b59b6;
        color: white;
    }

    .btn:hover {
        background-color: #8e44ad;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin-top: 20px;
        }

        h1 {
            font-size: 28px;
        }
    }
</style>

</head>
<body>

<!-- Barre de navigation avec Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Météo des Villes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">À propos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Formulaire avec un menu déroulant pour choisir une ville -->
<div class="container">
    <h1>Vérifier la Météo</h1>
    <form method="POST">
        <label for="ville" class="form-label">Choisissez une ville :</label>
        <select name="ville" id="ville" class="form-select" onchange="this.form.submit()">
            <?php foreach ($villes as $ville): ?>
                <option value="<?php echo $ville; ?>" <?php echo $ville == $selectedVille ? 'selected' : ''; ?>><?php echo $ville; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<!-- Affichage des données météo -->
<div id="weatherResult" class="container mt-4">
    <?php if (isset($weather['error'])): ?>
        <p class="error"><?php echo $weather['error']; ?></p>
    <?php else: ?>
        <div class="weather-city">
            <h3><?php echo $selectedVille; ?></h3>
            <!-- Affichage de l'icône météo en fonction du code d'icône -->
            <img src="https://openweathermap.org/img/wn/<?php echo $weather['icon']; ?>@2x.png" alt="<?php echo $weather['desc']; ?>" class="weather-icon">
            <p><strong>Température :</strong> <?php echo $weather['temp']; ?> °C</p>
            <p><strong>Humidité :</strong> <?php echo $weather['humid']; ?> %</p>
            <p><strong>Conditions :</strong> <?php echo $weather['desc']; ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>