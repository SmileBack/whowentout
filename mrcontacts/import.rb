require 'rubygems'

require './database'

require './lib/event'
require './lib/directory_importer'
require './lib/import_logger'

require './directories/gwu_directory'
require './directories/georgetown_directory'

def get_college_directory(college)
  if college == 'gwu'
    directory = GWUDirectory.new
    directory.login('dberen27', 'Apple12345678!')
    return directory
  elsif college == 'georgetown'
    GeorgetownDirectory.new
  end
end

def begin_import(college)
  connect_to_database(college)

  directory = get_college_directory(college)
  importer = DirectoryImporter.new(directory)
  logger = ImportLogger.new(directory, importer)

  ('aaa'..'zzz').each do |combination|
    importer.import combination
  end
end

college = ARGV[0]
begin_import(college)


