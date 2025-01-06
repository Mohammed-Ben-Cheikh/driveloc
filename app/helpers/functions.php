<?php

function getStatusClass($status) {
    return match ($status) {
        'en attente' => 'status-pending bg-yellow-500 text-white',
        'approuvée' => 'status-approved bg-green-500 text-white',
        'refusée' => 'status-rejected bg-red-500 text-white',
        'terminée' => 'status-completed bg-blue-500 text-white',
        'annulée' => 'status-cancelled bg-gray-500 text-white',
        default => ''
    };
}

function getStatusLabel($status) {
    return match ($status) {
        'en attente' => 'En attente',
        'approuvée' => 'Approuvée',
        'refusée' => 'Refusée',
        'terminée' => 'Terminée',
        'annulée' => 'Annulée',
        default => $status
    };
}

// ...other helper functions...
