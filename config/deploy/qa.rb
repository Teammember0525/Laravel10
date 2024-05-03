role :app, %w[10.10.90.211]
role :web, %w[10.10.90.211]
role :db,  %w[10.10.90.211]

set :branch, :develop
set :user, :ubuntu

set :ssh_options, {
  user: fetch(:user),
  forward_agent: true,
  proxy: Net::SSH::Proxy::Command.new('ssh ubuntu@la-nat.atgames.net -Cn -W %h:%p')
}
