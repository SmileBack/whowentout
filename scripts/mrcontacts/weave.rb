require 'rubygems'
require 'csv'

require_relative 'database'
require_relative 'lib/facebook_linker'

data = CSV.read("C:\\Users\\Venkat\\Desktop\\emails.csv")

linker = FacebookLinker.new

data.each do |row|
  provided_name = row[0]
  facebook_id = row[1]
  linker.cross_link_user(provided_name, facebook_id)
end

