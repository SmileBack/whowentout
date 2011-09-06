require 'facets/enumerator'

class Query < ActiveRecord::Base
  has_and_belongs_to_many :students
  
  has_many :created, :class_name => 'Student', :foreign_key => 'created_by_id'
  
  attr_accessible :value, :qtype, :num_total_results, :num_returned_results, :num_in_db, :num_added_to_db
  
  validates :value, :presence => true, :uniqueness => true
  
  def has_all_students?
    num_in_db == num_total_results
  end

  def recount_num_in_db
    self.num_in_db = Student.gwu_query(value).length
    save
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
    arel_table[:num_in_db].gteq(arel_table[:num_total_results]) \
      .and( arel_table[:num_total_results].gteq(0) )
  end
  
  def self.prioritize_by_missing
    order('queries.num_total_results - queries.num_in_db DESC')
  end
  
  def self.with_pattern(*values)
    where(with_pattern_condition values)
  end

  def self.with_pattern_condition(*values)
    values.flatten!
    like = values.map { |c| '_' * c }.join('+')
    conditions = arel_table[:value].matches(like)

    (0..like.length-1).each do |c|
      next if like[c] == '+'
      nl = '_' * like.length
      nl[c] = '+'
      conditions = conditions.and(arel_table[:value].matches(nl).not)
    end
    return conditions
  end

end
