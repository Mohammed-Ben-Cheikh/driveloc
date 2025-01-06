<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: public");
} else if (isset($_SESSION['admin_id']) && $_SESSION['admin_id']){
    header("Location: dashboard");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveLoc</title>
    <link rel="stylesheet" href="./src/output.css">
    <link rel="stylesheet" href="./public/css/style.css">
</head>

<body class="max-w-screen-xl flex flex-col mx-auto p-4 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen gap-8">
    <nav
        class="relative bg-gradient-to-r from-blue-400 to-blue-600 rounded-[2rem] border-gray-200 shadow-2xl border-4 border-white/20 backdrop-blur-sm">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <div
                class="flex justify-center items-center rounded-lg w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="./public/img/logo.png" alt="Chef"
                            class="rounded-lg shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                    </div>
                </a>
            </div>
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="./authentification/login.php"
                    class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 m-2">Login</a>
                <a href="./authentification/signup.php"
                    class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 m-2">Sign
                    Up</a>
            </div>
        </div>
    </nav>
    <main class="space-y-16">
        <!-- Enhanced Hero Section -->
        <section
            class="relative overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-blue-600/10"></div>
                <div class="absolute inset-0 bg-pattern opacity-5"></div>
            </div>
            <div class="container mx-auto px-4 py-16 relative">
                <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8 animate-fade-in">
                        <h1 class="text-6xl font-bold text-gray-800 leading-tight">
                            L'Excellence <br />
                            <span class="text-blue-500 drop-shadow-lg">Gastronomique</span> <br />
                            à votre Service
                        </h1>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            Découvrez une expérience culinaire exclusive avec nos chefs étoilés.
                            Une fusion parfaite entre tradition et innovation gastronomique.
                        </p>
                        <div class="flex flex-wrap gap-6">
                            <a href="./authentification/signup.php"
                                class="group relative px-8 py-4 rounded-2xl overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600">
                                <div
                                    class="absolute inset-0 bg-white/20 transform -skew-x-12 translate-x-full group-hover:translate-x-0 transition-transform">
                                </div>
                                <span class="relative text-white font-semibold">Découvrir nos Services</span>
                            </a>
                            <a href="#contact"
                                class="group relative px-8 py-4 rounded-2xl overflow-hidden border-2 border-blue-500">
                                <div
                                    class="absolute inset-0 bg-blue-500 transform -skew-x-12 -translate-x-full group-hover:translate-x-0 transition-transform">
                                </div>
                                <span
                                    class="relative text-blue-500 group-hover:text-white font-semibold transition-colors">Contact
                                    Us</span>
                            </a>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="./public/img/herocar.jpg" alt="Chef"
                            class="rounded-[2rem] shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                        <div
                            class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl backdrop-blur-sm border border-white/20">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-gray-800">Plus de 1000</p>
                                    <p class="text-gray-500">Clients satisfaits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section
            class="relative overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8">
            <div class="slider" style="
            --width: 100px;
            --height: 50px;
            --quantity: 10;
        ">
                <div class="list">
                    <div class="item" style="--position: 1"><img src="./public/img/images/slider1_1.png" alt=""></div>
                    <div class="item" style="--position: 2"><img src="./public/img/images/slider1_2.png" alt=""></div>
                    <div class="item" style="--position: 3"><img src="./public/img/images/slider1_3.png" alt=""></div>
                    <div class="item" style="--position: 4"><img src="./public/img/images/slider1_4.png" alt=""></div>
                    <div class="item" style="--position: 5"><img src="./public/img/images/slider1_5.png" alt=""></div>
                    <div class="item" style="--position: 6"><img src="./public/img/images/slider1_6.png" alt=""></div>
                    <div class="item" style="--position: 7"><img src="./public/img/images/slider1_7.png" alt=""></div>
                    <div class="item" style="--position: 8"><img src="./public/img/images/slider1_8.png" alt=""></div>
                    <div class="item" style="--position: 9"><img src="./public/img/images/slider1_9.png" alt=""></div>
                    <div class="item" style="--position: 10"><img src="./public/img/images/slider1_10.png" alt=""></div>
                </div>
            </div>

            <div class="slider" reverse="true" style="
            --width: 200px;
            --height: 200px;
            --quantity: 9;
        ">
                <div class="list">
                    <?php
                    for ($i = 0; $i < 11; $i++) {
                        echo '<div class="item" style="--position: ' . $i . '"><img class="rounded-xl h-full w-96" src="./public/img/herocar.jpg" alt=""></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Testimonials Grid -->
        <section class="py-16 bg-white/50 backdrop-blur-sm border-4 border-gray-400 shadow-2xl p-8 rounded-[2rem]">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-blue-600">
                        Ce que disent nos clients
                    </span>
                </h2>
                <div class="flex overflow-x-auto custom-scrollbar gap-8 rounded-2xl p-4">
                    <!-- Testimonial Cards -->
                    <?php

                    for ($i = 1; $i <= 5; $i++) {
                        $user = $i;
                        $y = '';
                        $x = '';
                        switch ($user) {
                            case 1:
                                $y = '★★★★';
                                $x = '★';
                                break;
                            case 2:
                                $y = '★★★';
                                $x = '★★';
                                break;
                            case 3:
                                $y = '★★';
                                $x = '★★★';
                                break;
                            case 4:
                                $y = '★';
                                $x = '★★★★';
                                break;
                            case 5:
                                $y = '';
                                $x = '★★★★★';
                                break;
                        }
                        echo '
                            <div>
                                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all w-96">
                                    <div class="flex items-center mb-6">
                                        <img src="#" class="w-14 h-14 rounded-full object-cover mr-4" alt="Client">
                                        <div>
                                            <h4 class="font-bold text-lg">Marie Dubois</h4>
                                            <p class="text-blue-500">Événement privé</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 italic">"Une expérience inoubliable! Le chef a créé un menu
                                        parfaitement adapté à nos goûts. Le service était impeccable."</p>
                                    <div class="flex mt-4"><span class="text-orange-400">' . $x . '</span><span>' . $y . '</span></div>
                                </div>
                            </div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- Professional Statistics Section -->
        <section
            class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-blue-500 to-blue-600 shadow-2xl p-12">
            <h2 class="text-4xl font-bold text-center mb-16">
                <span class="bg-clip-text text-transparent bg-white">
                    Services d'Excellence
                </span>
            </h2>
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-pattern opacity-10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>
            <div class="container mx-auto px-4 relative">
                <div class="grid md:grid-cols-4 gap-8 text-center">
                    <div
                        class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 transform hover:scale-105 transition-all">
                        <h3 class="text-5xl font-bold mb-4 text-white">500+</h3>
                        <p class="text-white/90 text-lg">Chefs Qualifiés</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 transform hover:scale-105 transition-all">
                        <h3 class="text-5xl font-bold mb-4 text-white">10k+</h3>
                        <p class="text-white/90 text-lg">Clients Satisfaits</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 transform hover:scale-105 transition-all">
                        <h3 class="text-5xl font-bold mb-4 text-white">1500+</< /h3>
                            <p class="text-white/90 text-lg">Événements Réussis</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 transform hover:scale-105 transition-all">
                        <h3 class="text-5xl font-bold mb-4 text-white">50+</h3>
                        <p class="text-white/90 text-lg">Villes Desservies</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section Enhanced -->
        <section>
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Questions Fréquentes</h2>
                <div class="max-w-3xl mx-auto space-y-4">
                    <!-- FAQ Items -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Comment réserver un chef ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Réservez facilement en ligne ou par téléphone. Notre équipe vous
                                guidera dans le choix du chef et du menu parfait pour votre événement.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Quels types de cuisine proposez-vous ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Nos chefs maîtrisent une large gamme de cuisines : française,
                                italienne, asiatique, végétarienne, etc. Le menu est entièrement personnalisable selon
                                vos préférences.</p>
                        </div>
                    </div>
                    <!-- Add more FAQ items -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Quels sont les tarifs pour vos services ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Les tarifs varient en fonction du service demandé, du nombre de
                                convives et des spécificités du menu. Contactez-nous pour un devis personnalisé.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Proposez-vous des options végétariennes ou véganes
                                ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Oui, nos chefs peuvent préparer des menus entièrement végétariens
                                ou
                                véganes selon vos préférences et besoins alimentaires.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Comment se déroule un événement avec un chef à domicile
                                ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Le chef arrive chez vous avec tous les ingrédients nécessaires,
                                prépare le repas dans votre cuisine et assure le service. Vous pouvez ainsi profiter de
                                vos invités sans vous soucier de la cuisine.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Quels sont les délais de réservation ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Nous recommandons de réserver au moins deux semaines à l'avance
                                pour
                                garantir la disponibilité du chef et des ingrédients nécessaires.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <button
                            class="flex justify-between items-center w-full p-6 text-left focus:outline-none faq-toggle">
                            <span class="font-semibold text-lg">Proposez-vous des cours de cuisine pour enfants ?</span>
                            <svg class="w-6 h-6 text-blue-500 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="px-6 pb-6 hidden">
                            <p class="text-gray-600">Oui, nous proposons des cours de cuisine adaptés aux enfants pour
                                leur apprendre les bases de la cuisine de manière ludique et sécurisée.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enhanced Contact Section with Animation -->
        <section id="contact"
            class="relative overflow-hidden rounded-[2rem] bg-pattern bg-gradient-to-br from-blue-500/5 to-blue-600/5 backdrop-blur-sm border-4 border-white shadow-2xl p-12">
            <div class="container mx-auto p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Info Card -->
                    <div
                        class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 rounded-[2rem] backdrop-blur-sm border border-white/20 overflow-hidden group hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="space-y-8">
                                <!-- Company Info -->
                                <div class="flex items-center gap-4 p-4 bg-white/20 rounded-xl">
                                    <div class="bg-blue-500 p-3 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">Notre Adresse</h3>
                                        <p class="text-gray-600">123 Rue de la Gastronomie, 75000 Paris</p>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="flex items-center gap-4 p-4 bg-white/20 rounded-xl">
                                    <div class="bg-blue-500 p-3 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">Contactez-nous</h3>
                                        <p class="text-gray-600">contact@chef-now.com</p>
                                        <p class="text-gray-600">+33 1 23 45 67 89</p>
                                    </div>
                                </div>

                                <!-- Hours Info -->
                                <div class="flex items-center gap-4 p-4 bg-white/20 rounded-xl">
                                    <div class="bg-blue-500 p-3 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg">Heures d'ouverture</h3>
                                        <p class="text-gray-600">Lun-Ven: 9h-18h</p>
                                        <p class="text-gray-600">Sam-Dim: 10h-16h</p>
                                    </div>
                                </div>

                                <!-- Social Links -->
                                <div class="flex justify-center gap-6 pt-4">
                                    <a href="#"
                                        class="bg-blue-500 p-3 rounded-xl text-white hover:bg-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.378 14.192 5 15.115 5H18V0h-3.808C10.596 0 9 1.583 9 4.615V8z" />
                                        </svg>
                                    </a>
                                    <!-- Twitter/X -->
                                    <a href="#"
                                        class="bg-blue-500 p-3 rounded-xl text-white hover:bg-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                        </svg>
                                    </a>
                                    <!-- Instagram -->
                                    <a href="#"
                                        class="bg-blue-500 p-3 rounded-xl text-white hover:bg-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                        </svg>
                                    </a>
                                    <!-- LinkedIn -->
                                    <a href="#"
                                        class="bg-blue-500 p-3 rounded-xl text-white hover:bg-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div
                        class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-[2rem] shadow-xl border-4 border-white/20 ">
                        <form action="messages.php" method="post" class="p-8 space-y-6">
                            <h2 class="text-2xl font-bold text-white mb-6">Envoyez-nous un message</h2>

                            <div class="space-y-4">
                                <input type="text" placeholder="Votre nom"
                                    class="w-full p-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">

                                <input type="email" placeholder="Votre email"
                                    class="w-full p-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">

                                <textarea placeholder="Votre message" rows="6"
                                    class="w-full p-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-white text-blue-500 font-bold py-4 rounded-xl hover:bg-blue-50 transition-colors">
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gradient-to-t from-blue-400 to-blue-600 rounded-[2rem] shadow-2xl border-4 border-white/20 mt-8">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div
                    class="flex justify-center items-center rounded-[33px] w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                    <a href="index.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img src="./public/img/logo.png" class="rounded-lg" alt="Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="https://flowbite.com/" class="hover:underline">Flowbite</a>
                            </li>
                            <li>
                                <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="https://github.com/themesberg/flowbite" class="hover:underline ">Github</a>
                            </li>
                            <li>
                                <a href="https://discord.gg/4eeurUVvTy" class="hover:underline">Discord</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-bla sm:text-center">© 2023 <a href="https://flowbite.com/"
                        class="hover:underline">DriveLoc™</a>. All Rights Reserved.
                </span>
                <div class="flex mt-4 sm:justify-center sm:mt-0">
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 8 19">
                            <path fill-rule="evenodd"
                                d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Facebook page</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 21 16">
                            <path
                                d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z" />
                        </svg>
                        <span class="sr-only">Discord community</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 17">
                            <path fill-rule="evenodd"
                                d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Twitter page</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">GitHub account</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.094 20.094 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM8 1.707a8.821 8.821 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.758 45.758 0 0 0 8 1.707ZM1.642 8.262a8.57 8.57 0 0 1 4.73-5.981A53.998 53.998 0 0 1 9.54 7.222a32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.64 31.64 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM10 18.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 13.113 11a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Dribbble account</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // FAQ Toggle
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');

                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Intersection Observer for fade-in animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('section').forEach((section) => {
            observer.observe(section);
        });
    </script>
</body>

</html>