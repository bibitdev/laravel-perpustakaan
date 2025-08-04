<?php

if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName) {
        $icons = [
            'Fiksi' => 'magic',
            'Non-Fiksi' => 'brain',
            'Sejarah' => 'landmark',
            'Sains' => 'flask',
            'Teknologi' => 'laptop-code',
            'Agama' => 'pray',
            'Pendidikan' => 'graduation-cap',
            'Kesehatan' => 'heartbeat',
            'Ekonomi' => 'chart-line',
            'Hukum' => 'balance-scale',
            'Seni' => 'palette',
            'Olahraga' => 'running',
            'Biografi' => 'user-circle',
            'Referensi' => 'book-open',
        ];

        return $icons[$categoryName] ?? 'book';
    }
}
