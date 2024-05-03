# frozen_string_literal: true

# # for AWE EC2
# set :ec2_contact_point, :public_dns
ec2_role :web
ec2_role :app
ec2_role :db

set :user, :ubuntu
set :branch, lambda {
  `git for-each-ref --format='%(*committerdate:raw)%(committerdate:raw) %(refname) %(*objectname) %(objectname)' refs/tags | sort -n | awk '{ print $3; }'`.split("/").last.strip
}

set :ssh_options, {
  user: fetch(:user),
  forward_agent: true,
  proxy: Net::SSH::Proxy::Command.new('ssh ubuntu@d2d-nat.direct2drive.com -Cn -W %h:%p')
}
