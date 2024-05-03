# config valid for current version and patch releases of Capistrano
lock '~> 3.14.1'

set :application, 'ArcadeNet-Leaderboard'
set :name, 'acnet-lb'

set :repo_url, 'git@scm.atgames.net:atgames/leaderboard-web.git'
set :deploy_to, "/opt/www/#{fetch(:name)}"

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, "/var/www/my_app_name"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
append :linked_files, '.env'

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for local_user is ENV['USER']
# set :local_user, -> { `git config user.name`.chomp }

# Default value for keep_releases is 5
# set :keep_releases, 5

# Uncomment the following to require manually verifying the host key before first deploy.
# set :ssh_options, verify_host_key: :secure

# # cap-ec2
set :ec2_config, 'config/ec2.yml'
set :ec2_contact_point, :private_ip

set :laravel_version, 5.8

# The user that the server is running under (used for ACLs)
# set :laravel_server_user, 'www-data'

# set :laravel_dotenv_file, "#{shared_path}/.env"

# Whether to upload the dotenv file on deploy
set :laravel_upload_dotenv_file_on_deploy, false

set :laravel_5_acl_paths, [] # Ignore setfacl not permitted. After first time deployed.