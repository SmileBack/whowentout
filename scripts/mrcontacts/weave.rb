require 'rubygems'
require './database'

require 'fb_graph'

def get_name_from_facebook_id(facebook_id)
  user = FbGraph::User.fetch(facebook_id)
  return user.name
end

connect_to_database('gwu')

students = Student.where('name LIKE ?', '%bob%')
students.each { |s| puts s.name }






