require './gwudirectory.rb'

dir = GWUDirectory.new

letters = ARGV[0]

raise "You must pick a 2 letter job" if letters.length != 2

start = Time.now

("#{letters}aa".."#{letters}zz").each do |query|
  students = dir.search(query)
  total = dir.num_results
  students.each { |s| s.save }
  puts "#{query}: Saved #{students.length}/#{total} students ..."
end

finish = Time.new

puts "Script took #{finish - start} seconds to run."
