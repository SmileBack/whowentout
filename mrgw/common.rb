require 'yaml'

if ENV['COMPUTER'].nil?
  raise "The COMPUTER environment variable must be set."
  exit
end

def config(k=nil)
  cfg = YAML::load(open('config.yml'))
  
  env = ENV['COMPUTER']
  k.nil? ? cfg[env] : cfg[env][k]
end
