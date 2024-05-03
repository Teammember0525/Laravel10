ec2_role :web
ec2_role :app
ec2_role :db

set :deploy_to, "/opt/www/acnet-lb-stage"

set :branch, -> {
  `git for-each-ref --format='%(*committerdate:raw)%(committerdate:raw) %(refname) %(*objectname) %(objectname)' refs/remotes/origin/release/* | sort -n | awk '{ print $3; }'`.split("\n").last.gsub('refs/remotes/origin/','').strip
}
set :branch, :develop
set :user, :ubuntu

set :ssh_options, {
  user: fetch(:user),
  forward_agent: true,
  proxy: Net::SSH::Proxy::Command.new('ssh ubuntu@d2d-nat.direct2drive.com -Cn -W %h:%p')
}
