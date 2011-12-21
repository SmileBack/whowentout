require './common'

require 'sqlite3'
require 'active_record'

ActiveRecord::Base.establish_connection(config('database'))

require './models/query'
require './models/student'
