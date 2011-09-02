require 'rubygems'

require 'sqlite3'
require 'active_record'
require 'extensions/activerecord_sqlite3_extensions'

ActiveRecord::Base.establish_connection(
  :adapter => 'sqlite3',
  :database => 'R:/students.db'
)

require 'models/query'
require 'models/student'

require 'lib/gwudirectory'
require 'lib/gwudirectoryimporter'

importer = GWUDirectoryImporter.new

def complete_queries
  Query.with_pattern([4, 1]).complete
end
  
def sorted_incomplete_queries
  Query.with_pattern([4, 1]).incomplete.order_by_missing
end

queries = Query.with_pattern([4, 1])
queries.each do |q|
  q.created.each { |s| puts s.name }
end

exit

sorted_incomplete_queries.each do |query|
  puts "Starting on #{query.value}..."
  ('a'..'z').each do |char|
    pattern = query.value + '+' + char
    importer.save_students(pattern)
  end
end

puts "Finished. Great success."
