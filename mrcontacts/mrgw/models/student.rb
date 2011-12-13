class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries
  
  belongs_to :created_by, :class_name => 'Query'
  
  attr_accessible :name, :first_name, :last_name, :email
  
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

  def self.first_name_tallies
    students = arel_table
    count_col = students[:first_name].count
    first_name_counts = students.group(students[:first_name]) \
                                .project(students[:first_name].count.as('count'), students[:first_name]) \
                                .having(count_col.gt(10)) \
                                .order(count_col)
                              
    tallies = Hash.new { |h, k| 0 }
    find_by_sql(first_name_counts).each do |student|
      tallies[student.first_name] = student.count
    end
    
    return tallies
  end

  def self.last_name_tallies
    students = arel_table
    count_col = students[:last_name].count
    last_name_counts = students.group(students[:last_name]) \
                                .project(students[:last_name].count.as('count'), students[:last_name]) \
                                .having(count_col.gt(10)) \
                                .order(count_col)

    tallies = Hash.new { |h, k| 0 }
    find_by_sql(last_name_counts).each do |student|
      tallies[student.last_name] = student.count
    end

    return tallies
  end

end
