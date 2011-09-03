require 'yaml'

def config(env)
  cfg = YAML::load(open('config.yml'))
  cfg[env.to_s]
end
