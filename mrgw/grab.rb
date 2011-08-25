require 'rubygems'
require 'sqlite3'
require 'active_record'
require 'facets/enumerator'

require 'gwudirectory'

ActiveRecord::Base.establish_connection(
  :adapter => 'sqlite3',
  :database => 'data/students.db'
)

class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries
  
  attr_accessible :name, :email
  
  validates :name, :presence => true
  validates :email, :presence => true, :uniqueness => true
end

class Query < ActiveRecord::Base
  has_and_belongs_to_many :students
  
  attr_accessible :value, :num_total_results, :num_returned_results, :num_in_db
  
  validates :value, :presence => true, :uniqueness => true
  
  def has_all_students?
    num_in_db == num_total_results
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
      string_subsets(q, length).each do |subset|
        if already_saved?(subset)
          query = Query.find_by_value(subset)
          if query.has_all_students?
            puts "query #{q} redundant becuase #{subset} got all #{query.num_total_results} students"
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
    result = @dir.search(q)
    
    query = Query.new
    query.value = q
    query.num_total_results = result[:count]
    query.num_returned_results = result[:students].length
    
    result[:students].each do |s|
      
      unless student_exists?(s[:email])
        student = Student.create :name => s[:name], :email => s[:email]
      else
        student = Student.find_by_email(s[:email])
      end
      
      query.students << student
    end
    
    query.save
    puts " saved #{query.num_returned_results}/#{query.num_total_results} results!"
  end
  
  def update_database_count(q)
    query = Query.find_by_value(q)
    
    return -1 if query.nil?
    
    count = Student.where('name LIKE ? OR email = ?', "%#{q}%", "#{q}@gwu.edu").size
    query.num_in_db = count
    query.save
    return count
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
  
end

importer = GWUDirectoryImporter.new

puts importer.most_recent_query

#dir = GWUDirectory.new
#results = dir.search('clea')
#puts results
#('aaaa'..'zzzz').each do |combo|
#  importer.save_students(combo)
#end

