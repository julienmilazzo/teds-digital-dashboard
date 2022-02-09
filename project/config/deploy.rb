# config valid for current version and patch releases of Capistrano
lock "~> 3.16.0"

set :application, "sentinelle"
set :repo_url, "git@github.com:julienmilazzo/teds-digital-dashboard.git"

set :branch, ENV['BRANCH'] if ENV['BRANCH']
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, "/var/www/my_app_name"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
set :format_options, command_output: true, log_file: "var/log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
append :linked_files, ".env"

# Default value for linked_dirs is []
append :linked_dirs, "log", "vendor", "public/bundles", "public/images", "public/audio"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for local_user is ENV['USER']
# set :local_user, -> { `git config user.name`.chomp }

# Default value for keep_releases is 5
# set :keep_releases, 5

# Uncomment the following to require manually verifying the host key before first deploy.
set :ssh_options, {:forward_agent => true}

set :composer_install_flags, '--no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts'

namespace :deploy do
  task :migrate do
    symfony_console('doctrine:migrations:migrate', '--no-interaction')
  end
  task :assets do
    symfony_console('assets:install')
  end
end

# namespace :cache do
#   task :clear do
#     on roles(:app) do |host|
#       with rails_env: fetch(:rails_env) do
#         within current_path do
#           execute :bundle, :exec, "rake cache:clear"
#         end
#       end
#     end
#   end
# end
#
# after 'deploy:finished', 'cache:clear'
before 'deploy:updated', 'deploy:set_permissions:chown'
after 'deploy:finished', 'deploy:assets'
# after 'deploy:finished', 'deploy:migrate'
