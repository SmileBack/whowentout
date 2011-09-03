require './common'

require 'sqlite3'
require 'active_record'
require './extensions/activerecord_sqlite3_extensions'

ActiveRecord::Base.establish_connection(
  :adapter => 'sqlite3',
  :database => 'R:/students.db'
)

require './models/query'
require './models/student'
