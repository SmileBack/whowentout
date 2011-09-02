require 'rubygems'

require 'sqlite3'
require 'active_record'
require 'sqlite_extensions'

require 'facets/enumerator'

require 'gwudirectory'

ActiveRecord::Base.establish_connection(
  :adapter => 'sqlite3',
  :database => 'R:/students.db'
)

class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries
  
  attr_accessible :name, :email
  
  validates :name, :presence => true
  validates :email, :presence => true, :uniqueness => true
  
  def self.gwu_query(q)
    q_parts = q.split('+')
    
    first_q_part = q_parts.shift
    
    students = Student.arel_table
    conditions = students[:name].matches('%' + first_q_part + '%').or(students[:email].eq(first_q_part + '@gwu.edu'))
    q_parts.each do |q_p|
      conditions = conditions.and(students[:name].matches('%' + q_p + '%').or(students[:email].eq(q_p + '@gwu.edu')))
    end
    
    where(conditions)
  end
end

class Query < ActiveRecord::Base
  has_and_belongs_to_many :students
  
  attr_accessible :value, :num_total_results, :num_returned_results, :num_in_db, :num_added_to_db
  
  validates :value, :presence => true, :uniqueness => true
  
  def has_all_students?
    num_in_db == num_total_results
  end
  
  def self.complete
    where(complete_condition)
  end
  
  def self.incomplete
    where(complete_condition.not)
  end
  
  def self.complete_condition
    arel_table[:num_in_db].eq(arel_table[:num_total_results])
  end
  
  def self.order_by_missing
    order('queries.num_total_results - queries.num_in_db DESC')
  end
  
  def self.with_pattern(values)
    regex = search_pattern_regex(values)
    where('value REGEXP ?', regex)
  end
  
  def self.search_pattern_regex(values)
    return '^' + values.map { |v| '[a-z]{' + v.to_s + '}' }.join('\\+') + '$'
  end
  
end

class GWUDirectoryImporter
  
  def initialize
    @dir = GWUDirectory.new
  end
  
  def already_saved?(q)
    Query.exists?(:value => q)
  end
  
  def student_exists?(email)
    Student.exists?(:email => email)
  end
  
  def query_redundant?(q)
    return true if q.length < 2 #querying 1 letter is worthless
    
   
    (2..q.length).each do |length|
      string_subsets(q, length).each do |subset|  #subsets of length 2 all the way to subsets of length q
        if Query.exists?(:value => subset)
          query = Query.find_by_value(subset)
          if query.has_all_students?
            puts "query #{q} redundant because #{subset} got all #{query.num_total_results} students"
            return true
          end
        end
      end
    end
    
    return false
  end
  
  def most_recent_query
    Query.last.value
  end
  
  def save_students(q)
    if already_saved?(q)
      puts "already queried #{q}. skipping."
      return
    end
    
    if query_redundant?(q)
      return
    end
    
    print "querying #{q} ... "
    Query.transaction do 
      result = @dir.search(q)

      query = Query.new
      query.value = q
      query.num_total_results = result[:count]
      query.num_returned_results = result[:students].length
      query.num_added_to_db = 0
      
      result[:students].each do |s|

        unless student_exists?(s[:email])
          student = Student.create :name => s[:name], :email => s[:email]
          query.num_added_to_db += 1
          add_to_query_tally(student, query.value.length)
        else
          student = Student.find_by_email(s[:email])
        end

        query.students << student
      end

      query.save
      puts " saved #{query.num_returned_results}/#{query.num_total_results} results! (#{query.num_added_to_db} new)"
    end
  end
  
  def update_database_count(q)
    query = Query.find_by_value(q)
    
    return -1 if query.nil?
    
    query.num_in_db = Student.gwu_query(query.value).length
    query.save
    
    return query.num_in_db
  end
  
  def range(length)
    ('a'*length..'z'*length)
  end
  
  def deduced_range(length)
    Enumerator.new do |y|
      range(length).each do |combo|
        y << combo unless combo[0] == combo[1]
      end
    end
  end
  
  def string_subsets(str, length)
    str.chars.each_cons(length).map { |c| c.join }
  end
  
  def student_name_subsets(str, len)
    subsets = []
    str.downcase.split(/[^a-z]+/).each do |segment|
      next if segment.length < len

      for i in 0..segment.length - len
        subsets << segment[i, len]
      end
    end
    return subsets.uniq
  end

  def student_query_subsets(student, max_query_length)
    subsets = []

    #if you type someones email_id exactly it turns up as a match
    email_id = student.email.split('@').first
    subsets << email_id if email_id.length <= max_query_length

    (2..max_query_length).each do |cur_query_length|
      subsets += student_name_subsets(student.name, cur_query_length)
    end

    return subsets
  end
  
  def add_to_query_tally(student, max_query_length)
    query_subsets = student_query_subsets(student, max_query_length)
    query_subsets.each do |subset|
      query = Query.find_by_value(subset)
      next if query.nil?
      query.num_in_db += 1
      query.save
    end
  end
  
  def subtract_from_query_tally(student, max_query_length)
    query_subsets = student_query_subsets(student, max_query_length)
    query_subsets.each do |subset|
      query = Query.find_by_value(subset)
      next if query.nil?
      query.num_in_db -= 1
      query.save
    end
  end
  
end

importer = GWUDirectoryImporter.new

def complete_queries
  Query.with_pattern([4, 1]).complete
end
  
def sorted_incomplete_queries
  Query.with_pattern([4, 1]).incomplete.order_by_missing
end

sorted_incomplete_queries.each do |query|
  puts "Starting on #{query.value}..."
  ('a'..'z').each do |char|
    pattern = query.value + '+' + char
    importer.save_students(pattern)
  end
end

puts "Finished. Great success."
sleep(2.days)
