require 'rubygems'

require './database'
require './lib/gwudirectory'
require './lib/gwudirectoryimporter'

importer = GWUDirectoryImporter.new

def incomplete_first_names
  first_name_tallies = Student.first_name_tallies
  first_names = []
  Query.with_pattern(4).incomplete.each do |q|
    first_names += first_name_tallies.select { |k, v| k.downcase.include?(q.value) }.keys
  end
  first_names.uniq.sort
end

def total_unprocessed_names
  queries = Query.arel_table
  Query.where(queries[:qtype].eq('first_name').or(queries[:qtype].eq('last_name')))
end

def query_first_names
  Query.where(:qtype => 'first_name').prioritize_by_missing.each do |q|
    ('aa'..'zz').each do |suffix|
      pattern = q.value + '+' + suffix
      importer.save_students(pattern, 'first_name_combos')
    end
  end
end

def query_last_names
  Query.where(:qtype => 'last_name').prioritize_by_missing.each do |q|
    ('aa'..'zz').each do |suffix|
      pattern = q.value + '+' + suffix
      importer.save_students(pattern, 'last_name_combos')
    end
  end
end


