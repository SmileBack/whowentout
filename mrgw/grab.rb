require './gwudirectory'
require './dbhash'

letters = ARGV[0]
raise "You must pick a 2 letter job" if letters.length != 2

dir = GWUDirectory.new
db = DbHash.new 'data/students.db'
queries = DbHash.new 'data/queries.db'

start = Time.now

("#{letters}aa".."#{letters}zz").each do |query|
  if queries.include?(query)
    puts "#{query}: Already queried. Skipped."
  else
    students = dir.search(query)
    total = dir.num_results
    students.each { |s| db[s.email] = s }
    puts "#{query}: Saved #{students.length}/#{total} students ..."
  end
end

finish = Time.new

puts "Script took #{finish - start} seconds to run."
