require 'rubygems'
require './gwudirectory'
require './dbhash'

students = DbHash.new 'data/students.db'
actual_counts = DbHash.new 'data/actual_counts.db'
queries = DbHash.new 'data/queries.db'
remaining_combinations = DbHash.new 'data/remaining_combinations.db'

def four_subsets(str)
  subsets = []
  str.split(/\s+/).each do |segment|
    for i in 0..segment.length - 4
      subsets << segment[i, 4]
    end
  end
  return subsets.uniq.sort.reject { |s| s[/^[a-z]+$/] == nil }
end

lt10 = 0

remaining_combinations.each do |query, combo|
  on_site = combo[:on_site]
  in_system = combo[:in_system]
  puts "#{query}: #{on_site}, #{in_system}, #{on_site - in_system}"
  if on_site - in_system <= 10
    lt10 += 1
  end
end

puts "#{remaining_combinations.length} overages"
puts "#{lt10} lt 10 overages"