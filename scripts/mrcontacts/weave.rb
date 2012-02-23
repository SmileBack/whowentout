require 'rubygems'
require './database'

require 'fb_graph'

class String

  def last_name
    if include?(',')
      return split(/\s*,\s*/).first
    else
      return split(/\s+/).last
    end
  end

  def first_name
    if include?(',')
      return split(/\s*,\s*/).last
    else
      return split(/\s+/).first
    end
  end

end

connect_to_database('gwu')

def get_name_from_facebook_id(facebook_id)
  user = FbGraph::User.fetch(facebook_id)
  puts user.identifier
  return user.name
end

def lookup_student(full_name)
  matches = Student.where('name LIKE ?', full_name.last_name + '%' + ', ' + full_name.first_name[0,1] + '%')
  return matches.first if matches.length == 1

  matches = Student.where('name LIKE ?', full_name.last_name + ', ' + '%')
  return matches.first if matches.length == 1


end

#students = Student.where('name LIKE ?', '%bob%')
#students.each { |s| puts s.name }

#require 'csv'

#arrs = CSV.read("C:\\Users\\Venkat\\Desktop\\emails.csv")
#puts arrs.inspect

data = [
    ["Aaron Belowich", "abelowich", 960],
    ["Abby Schreider",	"1329090673",	1608],
    ["Adam Kiko Spandorfer", "adam.spandorfer",	1086]
]

#matches = Student.where('name LIKE ?', 'belowich%')
#matches.each { |s| puts s.name }

data.each do |row|
  puts row[0]
  puts lookup_student(row[0]).inspect
  puts get_name_from_facebook_id(row[1])
end










