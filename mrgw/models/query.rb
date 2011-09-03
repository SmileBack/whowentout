require 'facets/enumerator'

class Query < ActiveRecord::Base
  has_and_belongs_to_many :students
  
  has_many :created, :class_name => 'Student', :foreign_key => 'created_by_id'
  
  attr_accessible :value, :num_total_results, :num_returned_results, :num_in_db, :num_added_to_db
  
  validates :value, :presence => true, :uniqueness => true
  
  def has_all_students?
    num_in_db == num_total_results
  end
  
  def self.queried_before(query)
    where(arel_table[:id].lt(query.id))
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
    puts "re = #{regex}"
    where('value REGEXP ?', regex)
  end
  
  def self.search_pattern_regex(values)
    return '^' + values.map { |v| '[a-z]{' + v.to_s + '}' }.join('\\+') + '$'
  end
  
end
