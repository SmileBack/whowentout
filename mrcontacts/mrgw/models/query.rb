class Query < ActiveRecord::Base
  has_and_belongs_to_many :students
  attr_accessible :value, :num_results
end