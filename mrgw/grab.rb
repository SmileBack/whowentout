require './gwudirectory'
require './dbhash'

letters = ARGV[0]
letters = '' if letters.nil?

dir = GWUDirectory.new
db = DbHash.new 'data/students.db'
queries = DbHash.new 'data/queries.db'

start = Time.now

def get_range(letters)
  if 1 <= letters.length && letters.length <= 4
    start = 'a' * (4 - letters.length)
    finish = 'z' * (4 - letters.length)
    return (letters+start..letters+finish)
  else
    return ('aaaa'..'zzzz')
  end
end

get_range(letters).each do |query|
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
