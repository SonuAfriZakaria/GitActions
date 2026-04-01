@servers(['web' => 'atmin@127.0.0.1'])

@setup
    $repository = 'git@127.0.0.1:rood/laravel.git';
    $app_dir = '/var/www/app';
    $releases_dir = $app_dir . '/releases';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir . '/' . $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    update_symlinks
@endstory

@task('clone_repository')
    echo "Cloning repository..."
    [ -d {{ $releases_dir }} ] || mkdir -p {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Installing dependencies..."
    cd {{ $new_release_dir }}
    composer install --optimize-autoloader --no-dev --no-interaction
@endtask

@task('update_symlinks')
    echo "Updating symlinks..."
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask
