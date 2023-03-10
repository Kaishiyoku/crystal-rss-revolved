<?php $slack = isset($slack) ? $slack : null; ?>
<?php $name = isset($name) ? $name : null; ?>
<?php $noCleanup = isset($noCleanup) ? $noCleanup : null; ?>
<?php $release = isset($release) ? $release : null; ?>
<?php $env = isset($env) ? $env : null; ?>
<?php $date = isset($date) ? $date : null; ?>
<?php $branch = isset($branch) ? $branch : null; ?>
<?php $healthUrl = isset($healthUrl) ? $healthUrl : null; ?>
<?php $path = isset($path) ? $path : null; ?>
<?php $repo = isset($repo) ? $repo : null; ?>
<?php $_ENV = isset($_ENV) ? $_ENV : null; ?>
<?php $server = isset($server) ? $server : null; ?>
<?php $e = isset($e) ? $e : null; ?>
<?php $dotenv = isset($dotenv) ? $dotenv : null; ?>
<?php
    require __DIR__.'/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    try {
        $dotenv->load();
        $dotenv->required(['DEPLOY_SERVER', 'DEPLOY_REPOSITORY', 'DEPLOY_PATH'])->notEmpty();
    } catch (Exception $e)  {
        echo $e->getMessage();
        exit;
    }

    $server = $_ENV['DEPLOY_SERVER'] ?? null;
    $repo = $_ENV['DEPLOY_REPOSITORY'] ?? null;
    $path = $_ENV['DEPLOY_PATH'] ?? null;
    $healthUrl = $_ENV['DEPLOY_HEALTH_CHECK'] ?? null;
    $branch = $_ENV['DEPLOY_BRANCH] ?? 'master';

    if (substr($path, 0, 1) !== '/') {
        throw new Exception('Careful - your deployment path does not begin with /');
    }

    $date = (new DateTime('now', new DateTimeZone('Europe/Berlin')))->format('YmdHis');
    $env = isset($env) ? $env : 'production';

    $path = rtrim($path, '/');
    $release = $path . '/releases/' . $date;
?>

<?php $__container->servers(['web' => $server]); ?>

<?php $__container->startTask('init'); ?>
    if [ ! -d <?php echo $path; ?>/storage ]; then
        cd <?php echo $path; ?>

        git clone <?php echo $repo; ?> --branch=<?php echo $branch; ?> --depth=1 -q <?php echo $release; ?>

        echo "Repository cloned"
        mv <?php echo $release; ?>/storage <?php echo $path; ?>/storage
        ln -s <?php echo $path; ?>/storage <?php echo $release; ?>/storage
        echo "Storage directory set up"
        cp <?php echo $release; ?>/.env.example <?php echo $path; ?>/.env
        ln -s <?php echo $path; ?>/.env <?php echo $release; ?>/.env
        echo "Environment file set up"
        rm -rf <?php echo $release; ?>

        echo "Deployment path initialised. Edit <?php echo $path; ?>/.env then run 'envoy run deploy'."
    else
        echo "Deployment path already initialised (storage directory exists)!"
    fi
<?php $__container->endTask(); ?>

<?php $__container->startMacro('deploy'); ?>
    deployment_start
    change_storage_owner_to_deployment_user
    deployment_links
    deployment_composer
    deployment_migrate
    deployment_cache
    deployment_npm
    deployment_finish
    change_storage_owner_to_www_data
    health_check
    deployment_option_cleanup
<?php $__container->endMacro(); ?>

<?php $__container->startMacro('rollback'); ?>
    deployment_rollback
    health_check
<?php $__container->endMacro(); ?>

<?php $__container->startMacro('list_releases'); ?>
    list_releases
<?php $__container->endMacro(); ?>

<?php $__container->startMacro('cleanup'); ?>
    deployment_cleanup
<?php $__container->endMacro(); ?>

<?php $__container->startTask('deployment_start'); ?>
    cd <?php echo $path; ?>

    echo "Deployment (<?php echo $date; ?>) started"
    git clone <?php echo $repo; ?> --branch=<?php echo $branch; ?> --depth=1 -q <?php echo $release; ?>

    echo "Repository cloned"
<?php $__container->endTask(); ?>

<?php $__container->startTask('change_storage_owner_to_deployment_user'); ?>
    sudo chown -R forge:forge <?php echo $path; ?>/storage/*
    echo "Changed storage owner to deployment user"

    sudo chown -R forge:forge <?php echo $release; ?>/bootstrap/cache
    echo "Changed bootstrap/cache owner to deployment user"
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_links'); ?>
    cd <?php echo $path; ?>

    rm -rf <?php echo $release; ?>/storage
    ln -s <?php echo $path; ?>/storage <?php echo $release; ?>/storage
    echo "Storage directories set up"
    ln -s <?php echo $path; ?>/.env <?php echo $release; ?>/.env
    echo "Environment file set up"
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_composer'); ?>
    echo "Installing composer dependencies..."
    cd <?php echo $release; ?>

    composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_migrate'); ?>
    php <?php echo $release; ?>/artisan migrate --env=<?php echo $env; ?> --force --no-interaction
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_npm'); ?>
    echo "Installing npm dependencies..."
    cd <?php echo $release; ?>

    npm install --no-audit --no-fund --no-optional
    echo "Running npm..."
    npm run <?php echo $env === 'production' ? 'build' : 'dev'; ?> --silent
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_cache'); ?>
    php <?php echo $release; ?>/artisan view:clear --quiet
    php <?php echo $release; ?>/artisan cache:clear --quiet
    php <?php echo $release; ?>/artisan config:cache --quiet
    php <?php echo $release; ?>/artisan route:cache --quiet
    php <?php echo $release; ?>/artisan view:cache --quiet
    echo "Cache cleared"

    sudo chown -R www-data:www-data <?php echo $release; ?>/storage/*
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_finish'); ?>
    php <?php echo $release; ?>/artisan storage:link
    echo "Storage symbolic links created"
    ln -nfs <?php echo $release; ?> <?php echo $path; ?>/current
    echo "Deployment (<?php echo $date; ?>) finished"
<?php $__container->endTask(); ?>

<?php $__container->startTask('change_storage_owner_to_www_data'); ?>
    sudo chown -R www-data:www-data <?php echo $path; ?>/storage/*
    echo "Changed storage owner to www-data"

    sudo chown -R www-data:www-data <?php echo $release; ?>/bootstrap/cache
    echo "Changed bootstrap/cache owner to www-data"
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_cleanup'); ?>
    cd <?php echo $path; ?>/releases
    find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs -I '{}' sudo chown -R forge:forge '{}'
    echo "Changed releases owner to deployment user"
    find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
    echo "Cleaned up old deployments"
<?php $__container->endTask(); ?>

<?php $__container->startTask('deployment_option_cleanup'); ?>
    cd <?php echo $path; ?>/releases

    <?php if (!isset($noCleanup) && !$noCleanup): ?>
        find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs -I '{}' sudo chown -R forge:forge '{}'
        echo "Changed releases owner to deployment user"
        find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
        echo "Cleaned up old deployments"
    <?php endif; ?>
<?php $__container->endTask(); ?>


<?php $__container->startTask('health_check'); ?>
    <?php if (! empty($healthUrl)): ?>
        if [ "$(curl --write-out "%{http_code}\n" --silent --output /dev/null <?php echo $healthUrl; ?>)" == "200" ]; then
            printf "\033[0;32mHealth check to <?php echo $healthUrl; ?> OK\033[0m\n"
        else
            printf "\033[1;31mHealth check to <?php echo $healthUrl; ?> FAILED\033[0m\n"
        fi
    <?php else: ?>
        echo "No health check set"
    <?php endif; ?>
<?php $__container->endTask(); ?>


<?php $__container->startTask('deployment_rollback'); ?>
    cd <?php echo $path; ?>/releases

    <?php if($name): ?>
        ln -nfs <?php echo $path; ?>/releases/<?php echo $name; ?> <?php echo $path; ?>/current
        echo "Rolled back to <?php echo $name; ?>"
    <?php else: ?>
        ln -nfs <?php echo $path; ?>/releases/$(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1) <?php echo $path; ?>/current
        echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1)"
    <?php endif; ?>
<?php $__container->endTask(); ?>

<?php $__container->startTask('list_releases'); ?>
    cd <?php echo $path; ?>/releases
    echo "Releases:"
    ls -d -t */ | cut -f1 -d'/'
<?php $__container->endTask(); ?>

<?php /*
<?php $_vars = get_defined_vars(); $__container->finished(function($exitCode = null) use ($_vars) { extract($_vars); 
	 if (! isset($task)) $task = null; Laravel\Envoy\Slack::make($slack, '#deployments', "Deployment on {$server}: {$date} complete")->task($task)->send();
}); ?>
*/ ?>
