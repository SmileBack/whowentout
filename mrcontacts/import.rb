require 'rubygems'

require './database'

require './lib/event'
require './lib/directory_importer'

require './lib/directory_logger'
require './lib/directory_importer_logger'

require './directories/gwu_directory'
require './directories/georgetown_directory'
require './directories/cua_directory'

def get_college_directory(college)
  if college == 'gwu'
    directory = GWUDirectory.new
    directory.login('dberen27', 'Apple12345678!')
    return directory
  elsif college == 'georgetown'
    GeorgetownDirectory.new
  elsif college == 'cua'
    CUADirectory.new
  end
end

def begin_import(college)
  connect_to_database(college)

  directory = get_college_directory(college)
  directory_importer = DirectoryImporter.new(directory)

  directory_logger = DirectoryLogger.new(directory)
  directory_importer_logger = DirectoryImporterLogger.new(directory_importer)

  ('aa'..'zz').each do |combination|
    directory_importer.import combination
  end
end

college = ARGV[0]
begin_import(college)
