class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries
  
  belongs_to :created_by, :class_name => 'Query'
  
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
