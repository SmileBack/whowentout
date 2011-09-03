require 'rubygems'

require './database'
require './lib/gwudirectory'
require './lib/gwudirectoryimporter'

importer = GWUDirectoryImporter.new

def complete_queries
  Query.with_pattern([4, 1]).complete
end
  
def sorted_incomplete_queries
  Query.with_pattern([4, 1]).incomplete.order_by_missing
end

sleep(2.seconds)
sorted_incomplete_queries.each do |query|
  puts "Starting on #{query.value}..."
  ('a'..'z').each do |char|
    pattern = query.value + '+' + char
    importer.save_students(pattern)
  end
end

puts "Finished. Great success."
