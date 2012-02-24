require 'csv'
require_relative 'lib/facebook_linker'

linker = FacebookLinker.new(true)

File.open('data/test.txt', 'w') do |f|
  Student.where('facebook_id NOT NULL').each do |s|
    query = "UPDATE users SET email = '#{s.email}' WHERE facebook_id = '#{s.facebook_id}' AND email IS NULL;"
    f.puts query
    puts query
  end
end

