<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Installation - <?php echo e(config('app.name', 'Laravel')); ?></title>
    <link rel="shortcut icon" href="/images/default/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="h-screen w-screen bg-cover bg-no-repeat bg-center" style="background-image: url('/default/cover.jpg');">
    <div class="flex h-screen py-6">
        <div class="container m-auto">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-8 border-b border-gray-200 sm:px-6">
                    <div class="flex justify-center items-center">
                        <img alt="App logo" class="h-12" src="/images/default/icon.png">
                        <h2 class="pl-6 uppercase font-medium text-2xl text-gray-800"><?php echo e(config('app.name', 'Laravel')); ?> Installation</h2>
                    </div>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    <?php echo $__env->yieldContent('step'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH /var/www/html/packages/mobidonia/laravel-dashboard-installer/src/Providers/../Views/install.blade.php ENDPATH**/ ?>