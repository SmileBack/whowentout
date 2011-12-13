require './lib/georgetown_directory'

require 'rubygems'
require 'mechanize'

dir = GeorgetownDirectory.new

results = dir.search ARGV[0]

pp results







