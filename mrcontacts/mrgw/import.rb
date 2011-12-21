require 'rubygems'
require 'mechanize'

require './event.rb'
require './database.rb'

require './lib/gwu_directory.rb'
require './lib/gwu_directory_importer.rb'

dir = GWUDirectory.new

dir.subscribe :on_login do |username, password|
  puts "logged in as #{username}"
end

dir.subscribe :on_search do |keywords, num_results, pages|
  puts "searched for #{keywords} and got #{num_results} results with #{pages} pages"
end

dir.subscribe :on_load_page do |keywords, page|
  puts "loaded page #{page} for '#{keywords}'"
end

importer = GWUDirectoryImporter.new(dir)

importer.subscribe :on_skip do |query|
  puts "skipped query #{query}"
end

importer.subscribe :on_save_student do |student|
  puts "saved #{student.name}, #{student.email}"
end

importer.subscribe :on_save_students do |students|
  puts "saved #{students.length} students to db"
end

dir.login('dberen27', 'Apple12345678!')
importer.import ARGV[0]