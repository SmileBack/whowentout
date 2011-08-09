require 'rubygems'
require './gwudirectory'
require './dbhash'

puts "Starting grab.rb..."
#sleep(10)

dir = GWUDirectory.new
db = DbHash.new 'data/students.db'
queries = DbHash.new 'data/queries.db'
remaining_combinations = DbHash.new 'data/remaining_combinations.db'

start = Time.now

remaining_combinations.each do |query, combo|
  query = ""
end

finish = Time.new

puts "Script took #{finish - start} seconds to run."
