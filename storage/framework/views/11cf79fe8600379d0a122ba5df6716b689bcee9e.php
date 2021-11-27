<?php $__env->startSection('step'); ?>
    <p class="pb-3 text-gray-800">
        <strong>Step 1</strong><br />
        Below you should enter your database connection details
        If you're not sure about these, contact your hosting provider
    </p>
    <?php if(isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-3">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm leading-5 text-red-700">
                        <?php echo $error; ?>

                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form method="post" action="<?php echo e(route('LaravelInstaller::install.setDatabase')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="database_hostname" class="block font-medium leading-5 text-gray-700 pb-2">Database host <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_hostname" type="text" name="database_hostname" value="<?php echo e($values['database_hostname'] ?? '127.0.0.1'); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="database_port" class="block font-medium leading-5 text-gray-700 pb-2">Database port <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_port" type="text" name="database_port" value="<?php echo e($values['database_port'] ?? '3306'); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="database_name" class="block font-medium leading-5 text-gray-700 pb-2">Database name <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_name" type="text" name="database_name" value="<?php echo e($values['database_name'] ?? 'laravel'); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="database_username" class="block font-medium leading-5 text-gray-700 pb-2">Database username <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_username" type="text" name="database_username" value="<?php echo e($values['database_username'] ?? 'root'); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="database_password" class="block font-medium leading-5 text-gray-700 pb-2">Database password</label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_password" type="password" name="database_password" value="<?php echo e($values['database_password'] ?? ''); ?>"
            >
        </div>
        <div class="mb-4">
            <label for="database_prefix" class="block font-medium leading-5 text-gray-700 pb-2">Database prefix</label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_prefix" type="text" name="database_prefix" value="<?php echo e($values['database_prefix'] ?? ''); ?>" placeholder="Optional"
            >
        </div>
        <hr /><br />
        <p class="pb-3 text-gray-800">
            <strong>Step 2</strong><br />
            Below you should enter your desired admin email.
        </p>
        <!-- <div class="mb-3">
            <label for="auth0domain" class="block font-medium leading-5 text-gray-700 pb-2">Auth0 Domain<span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="auth0domain" type="text" name="auth0domain" value="<?php echo e($values['auth0domain'] ?? ''); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="auth0clientid" class="block font-medium leading-5 text-gray-700 pb-2">Auth0 Client ID <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="auth0clientid" type="text" name="auth0clientid" value="<?php echo e($values['auth0clientid'] ?? ''); ?>" required
            >
        </div>

        <div class="mb-3">
            <label for="auth0clientsecret" class="block font-medium leading-5 text-gray-700 pb-2">Auth0 Client Secret <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="auth0clientsecret" type="password" name="auth0clientsecret" value="<?php echo e($values['auth0clientsecret'] ?? ''); ?>" required
            >
        </div> -->
        <div class="mb-3">
            <label for="adminemail" class="block font-medium leading-5 text-gray-700 pb-2">Admin email <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="adminemail" type="text" name="adminemail" value="<?php echo e($values['adminemail'] ?? ''); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="adminpassword" class="block font-medium leading-5 text-gray-700 pb-2">Admin Password <span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="adminpassword" type="text" name="adminpassword" value="<?php echo e($values['adminpassword'] ?? ''); ?>" required
            >
        </div>

        <hr><br />
        <p class="pb-3 text-gray-800">
            <strong>Step 3</strong><br />
            Some basics we need like your project name and link to it.
        </p>
        <div class="mb-3">
            <label for="projectname" class="block font-medium leading-5 text-gray-700 pb-2">Project name<span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="projectname" type="text" name="projectname" value="<?php echo e($values['projectname'] ?? 'My Project name'); ?>" required
            >
        </div>
        <div class="mb-3">
            <label for="projecturl" class="block font-medium leading-5 text-gray-700 pb-2">Project URL<span class="text-red-400">*</span></label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="projecturl" type="text" name="projecturl" value="<?php echo e($values['projecturl'] ?? ''); ?>" placeholder="https://yoursite.com" required
            >
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                Next step
                <svg class="fill-current w-5 h-5 ml-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('installer::install', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/packages/mobidonia/laravel-dashboard-installer/src/Providers/../Views/steps/database.blade.php ENDPATH**/ ?>