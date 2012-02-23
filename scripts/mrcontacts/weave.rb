require 'rubygems'
require 'csv'
require 'digest/sha1'

require_relative 'database'
require_relative 'lib/facebook_linker'


def get_bucket(student)
  hash = Digest::SHA1.hexdigest student.facebook_id
  return 1 if hash[0,1] <= "7"
  return 2
end

connect_to_database('gwu')

def get_students_from_bucket(target_bucket)
  students = []

  Student.where("facebook_id NOT NULL").each do |s|
    bucket = get_bucket(s)
    puts bucket
    if bucket == target_bucket
      students << s
    end
  end

  return students
end

students = get_students_from_bucket(2)

students.each do |s|
  puts "#{s.facebook_name}, #{s.email}"
end

emails = students.map { |s| s.email }



File.open('data/emails.txt', 'w') {|f| f.write emails.join(', ') }

puts "saved #{emails.length} emails!"

