<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$modelsPath = app_path('Models');
$models = [];
foreach (glob($modelsPath . '/*.php') as $file) {
    $class = 'App\\Models\\' . basename($file, '.php');
    if (class_exists($class) && is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
        $models[] = $class;
    }
}

$errors = [];
foreach ($models as $modelClass) {
    $model = new $modelClass();
    $table = $model->getTable();
    
    if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
        $errors[] = "Model $modelClass references missing table: $table";
        continue;
    }
    
    if ($model->usesTimestamps()) {
        $hasCreatedAt = \Illuminate\Support\Facades\Schema::hasColumn($table, $model->getCreatedAtColumn());
        $hasUpdatedAt = \Illuminate\Support\Facades\Schema::hasColumn($table, $model->getUpdatedAtColumn());
        if (!$hasCreatedAt || !$hasUpdatedAt) {
            $errors[] = "Model $modelClass expects timestamps but table '$table' is missing created_at/updated_at.";
        }
    }
}
echo json_encode($errors, JSON_PRETTY_PRINT);
