class Query < ActiveRecord::Base
  has_and_belongs_to_many :students

  attr_accessible :first_name, :first_name_matches, :last_name, :last_name_matches
end
