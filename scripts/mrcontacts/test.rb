require 'csv'
require_relative 'lib/string_extensions'

name = "Alexander Bahia Sasoon"

data = CSV.read("C:\\Users\\Venkat\\Desktop\\emails.csv")

data.each do |row|
  name = row[0]
  if name.start_with?('Alex')
    puts "#{name} :: #{name.first_name} :: #{name.last_name}"
  end
end


