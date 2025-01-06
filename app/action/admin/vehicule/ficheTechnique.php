<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Technique - DriveLoc</title>
    <link rel="stylesheet" href="../../../src/output.css">
</head>

<body class="max-w-screen-xl flex bg-gradient-to-br from-gray-50 to-blue-50 flex-col mx-auto p-4">
    <main class="space-y-8">
        <section
            class="relative overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8">
            <?php
            if (isset($_GET['id'])) {
                require_once '../../../database/Database.php';
                require_once '../../../controller/vehicules.php';
                $id = $_GET['id'];
                $vehicule = Vehicule::getByCategorie($id);
                if ($vehicule) {
                    ?>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Image Section -->
                        <div class="relative group">
                            <div
                                class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                            </div>
                            <img src="<?php echo htmlspecialchars($vehicule['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($vehicule['nom']); ?>"
                                class="rounded-[2rem] shadow-2xl w-full h-[400px] object-cover">

                            <!-- Availability Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="px-4 py-2 rounded-full text-white font-semibold <?php
                                echo match ($vehicule['disponibilite']) {
                                    'Disponible' => 'bg-green-500',
                                    'Réservé' => 'bg-yellow-500',
                                    'Indisponible' => 'bg-red-500',
                                    default => 'bg-gray-500'
                                };
                                ?>">
                                    <?php echo htmlspecialchars($vehicule['disponibilite']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="space-y-6">
                            <h1 class="text-4xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>
                            </h1>
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-blue-600">
                                    <?php echo number_format($vehicule['prix_a_loue'], 2); ?>€
                                </span>
                                <span class="text-gray-500">/ jour</span>
                            </div>

                            <!-- Key Features Grid -->
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div class="flex items-center gap-2 bg-white/80 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-gray-600">Année:
                                        <?php echo htmlspecialchars($vehicule['annee']); ?></span>
                                </div>
                                <div class="flex items-center gap-2 bg-white/80 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span
                                        class="text-gray-600"><?php echo htmlspecialchars($vehicule['type_vitesse']); ?></span>
                                </div>
                            </div>

                            <!-- Detailed Specs -->
                            <div class="bg-white/80 rounded-xl p-6 space-y-4">
                                <h3 class="text-xl font-semibold text-gray-800">Caractéristiques</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <p class="text-gray-600">Carburant: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['carburant']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Places: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nombre_de_places']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Portes: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nombre_de_portes']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Kilométrage: <span
                                                class="font-medium"><?php echo number_format($vehicule['kilometrage'], 0); ?>
                                                km</span></p>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-gray-600">Climatisation: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['climatisation']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Vitesses: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nb_vitesses']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Toit: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['toit_panoramique']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Catégorie: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['categorie_nom']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="bg-white/80 rounded-xl p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($vehicule['description'])); ?></p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4">
                                <?php if ($vehicule['disponibilite'] === 'Disponible'): ?>
                                    <a href="../reservation/create.php?vehicule=<?php echo $vehicule['id_vehicule']; ?>"
                                        class="flex-1 bg-blue-600 text-white text-center py-3 rounded-xl hover:bg-blue-700 transition">
                                        Réserver maintenant
                                    </a>
                                <?php endif; ?>
                                <a href="javascript:history.back()"
                                    class="px-6 py-3 border-2 border-blue-600 text-blue-600 rounded-xl hover:bg-blue-50 transition">
                                    Retour
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<div class="text-center text-red-600">Véhicule non trouvé.</div>';
                }
            } else {
                echo '<div class="text-center text-red-600">ID du véhicule non spécifié.</div>';
            }
            ?>
        </section>
    </main>
</body>

</html>