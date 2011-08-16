require 'rubygems'
require './dbhash'
require './student'

students = DbHash.new 'data/students.db'

#students.each do |k, student|
#  puts student.name
#end
File.open('students.txt', 'w') do |f|  
  students.each do |k, student|
    f.puts student.name + '%%' + student.email
  end
end

puts "done!";
