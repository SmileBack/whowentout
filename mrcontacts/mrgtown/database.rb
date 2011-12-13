require './common'

require 'sqlite3'
require 'active_record'

print config('database').inspect

ActiveRecord::Base.establish_connection(config('database'))

require './models/query'
require './models/student'