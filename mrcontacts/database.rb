
def connect_to_database(college)
  require 'sqlite3'
  require 'active_record'

  require './models/query'
  require './models/student'

  config = {:adapter => 'sqlite3'}
  config[:database] = "data/#{college}_students.db"
  ActiveRecord::Base.establish_connection(config)
end
